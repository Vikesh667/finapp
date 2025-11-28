<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use CodeIgniter\Controller;

class UserController extends Controller
{

    public function user_list()
    {
        $model = new UserModel();
        $search = $this->request->getGet('search');

        $builder = $model->searchUsers($search);
        $data = [
            'users'  => $builder->paginate(10, 'users'),
            'pager'  => $model->pager,
            'search' => $search,
        ];

        return view('user/list', $data);
    }

    public function add()
    {
        $model = new UserModel();

        if (! $this->validate($model->getValidationRules(), $model->getValidationMessages())) {
            return redirect()
                ->back()
                ->with('error', implode("\n", $this->validator->getErrors())) // FIXED
                ->withInput();
        }

        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        if ($model->insert($data)) {
            return redirect()
                ->to(base_url('admin/user-list'))
                ->with('success', 'User added successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Failed to add user. Please try again.');
    }



    public function edit($id = null)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (! $user) {
            return redirect()->to(base_url('admin/user-list'))->with('error', 'User not found.');
        }

        return view('user/edit', ['user' => $user]);
    }

    public function update()
    {
        $model = new UserModel();
        $id = $this->request->getPost('id');

        if (! $id || ! $model->find($id)) {
            return redirect()->to(base_url('admin/user-list'))->with('error', 'Invalid user ID.');
        }

        $data = [
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'phone'    => trim($this->request->getPost('phone')),
            'address'  => trim($this->request->getPost('address')),
            'country'  => trim($this->request->getPost('country')),
            'state'    => trim($this->request->getPost('state')),
            'city'     => trim($this->request->getPost('city')),
        ];

        if ($password = $this->request->getPost('password')) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(base_url('admin/user-list'))->with('success', 'User updated successfully.');
    }

    public function delete($id = null)
    {
        $userModel   = new \App\Models\UserModel();
        $customerModel = new \App\Models\CustomerModel();

        // 1️⃣ Check if user exists
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/user-list'))->with('error', 'User not found.');
        }

        // 2️⃣ Get admin user (auto transfer target)
        $admin = $userModel->where('role', 'admin')->first();
        if (!$admin) {
            return redirect()->to(base_url('admin/user-list'))->with('error', 'Admin not found.');
        }

        // 3️⃣ Transfer this user’s clients to Admin (Approach 1)
        $customerModel
            ->where('user_id', $id)
            ->set(['user_id' => $admin['id']])
            ->update();

        // 4️⃣ Optionally, you can store a record of who originally handled those clients
        // Uncomment if your table has a `previous_user_id` column
        /*
    $clientModel
        ->where('user_id', $admin['id'])
        ->set(['previous_user_id' => $id])
        ->update();
    */

        // 5️⃣ Delete or deactivate user
        $userModel->delete($id);

        // 6️⃣ Redirect with confirmation
        return redirect()
            ->to(base_url('admin/user-list'))
            ->with('success', 'User deleted successfully and their clients have been transferred to admin. You can reassign them later if needed.');
    }


    public function uploadProfile()
    {
        $session = session();
        $userId  = $session->get('user_id');
        $role    = $session->get('role');

        if (! $userId) {
            return redirect()->to(base_url('/login'))->with('error', 'Please login first.');
        }

        $file = $this->request->getFile('profile_image');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'Invalid file.');
        }

        $path = FCPATH . 'assets/uploads/logos';
        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($path, $newName);

        (new UserModel())->update($userId, ['profile_image' => $newName]);
        $session->set('profile_image', $newName);

        $redirect = ($role === 'admin') ? 'admin/app-settings' : 'user/app-settings';

        return redirect()->to(base_url($redirect))->with('success', 'Profile updated successfully.');
    }

    public function removeProfile()
    {
        $session = session();
        $userId  = $session->get('user_id');
        $role    = $session->get('role');

        if (! $userId) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $model = new UserModel();
        $user  = $model->find($userId);

        if (! $user || empty($user['profile_image'])) {
            return redirect()->back()->with('error', 'No profile image to remove.');
        }

        $filePath = FCPATH . 'assets/uploads/logos/' . $user['profile_image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $model->update($userId, ['profile_image' => null]);
        $session->remove('profile_image');

        $redirect = ($role === 'admin') ? 'admin/app-settings' : 'user/app-settings';

        return redirect()->to(base_url($redirect))->with('success', 'Profile image removed.');
    }

    public function getUsers()
    {
        $userModel = new UserModel();
        $users = $userModel->where('role', 'user')->findAll();
        return $this->response->setJSON($users);
    }
    public function getCountry()
    {
        return $this->response->setJSON((new CountryModel())->findAll());
    }

    public function getStateByCountry($countryId)
    {
        return $this->response->setJSON((new StateModel())->where('country_id', $countryId)->findAll());
    }

    public function getCityByState($stateId)
    {
        return $this->response->setJSON((new CityModel())->where('state_id', $stateId)->findAll());
    }


    public function profile_update($id = null)
    {
        $model = new UserModel();
        $user  = $model->find($id);

        if (! $user) {
            return redirect()->to(base_url('admin/user-list'))->with('error', 'User not found.');
        }

        return view('user/profile-update', ['user' => $user]);
    }

    public function save_profile()
    {
        $model   = new UserModel();
        $session = session();
        $id      = $this->request->getPost('id');

        $existing = $model->find($id);
        if (! $existing) {
            return redirect()->back()->with('error', 'Invalid user.')->withInput();
        }

        // Handle image
        $file        = $this->request->getFile('profile_image');
        $oldImage    = $this->request->getPost('old_image');
        $newImage    = $oldImage;

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newImage = $file->getRandomName();
            $file->move(FCPATH . 'assets/uploads/logos', $newImage);

            if ($oldImage && file_exists(FCPATH . 'assets/uploads/logos/' . $oldImage)) {
                unlink(FCPATH . 'assets/uploads/logos/' . $oldImage);
            }
        }

        // Update data
        $data = [
            'name'          => trim($this->request->getPost('name')),
            'email'         => trim($this->request->getPost('email')),
            'phone'         => trim($this->request->getPost('phone')),
            'address'       => trim($this->request->getPost('address')),
            'city'          => trim($this->request->getPost('city')),
            'state'         => trim($this->request->getPost('state')),
            'country'       => trim($this->request->getPost('country')),
            'profile_image' => $newImage,
        ];

        $model->skipValidation(true)->update($id, $data);

        // Update session
        $session->set([
            'user_name'     => $data['name'],
            'user_email'    => $data['email'],
            'profile_image' => $newImage,
            'address'       => $data['address'],
            'city'          => $data['city'],
            'state'         => $data['state'],
            'country'       => $data['country'],
        ]);

        $redirect = ($session->get('role') === 'admin')
            ? 'admin/app-settings'
            : 'user/app-settings';

        return redirect()->to(base_url($redirect))->with('success', 'Profile updated successfully.');
    }
}
