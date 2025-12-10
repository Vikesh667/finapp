<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\ClientModel;
use App\Models\ClientUserModel;
use App\Models\CustomerModel;
use App\Models\TransactionModel;

class UserController extends BaseController
{

    protected $service;

    public function __construct()
    {
        $this->service = service('userService');
    }


    public function user_list()
    {
        return view('user/list');
    }

    public function user_list_data()
    {
        $page   = (int) ($this->request->getGet('page') ?? 1);
        $search = $this->request->getGet('search');

        $data = $this->service->getPaginatedUsers($page, $search);

        return $this->response->setJSON($data);
    }


    public function add()
    {
        $model = new UserModel();

        if (!$this->validate($model->getValidationRules(), $model->getValidationMessages())) {
            return redirect()
                ->back()
                ->with('error', implode("\n", $this->validator->getErrors()))
                ->withInput();
        }

        $data = $this->request->getPost();

        try {
            service('userService')->createUser($data);

            return redirect()
                ->to(base_url('admin/user-list'))
                ->with('success', 'User added successfully.');
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }



    public function edit($id = null)
    {
        // Get user using Service Layer
        $user = $this->service->getUser($id);

        if (!$user) {
            return redirect()
                ->to(base_url('admin/user-list'))
                ->with('error', 'User not found.');
        }

        return view('user/edit', ['user' => $user]);
    }


    public function update()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()
                ->to(base_url('admin/user-list'))
                ->with('error', 'Invalid user ID.');
        }

        $data = $this->request->getPost();

        try {
            // Service handles password hashing & update logic
            $this->service->updateUser($id, $data);

            return redirect()
                ->to(base_url('admin/user-list'))
                ->with('success', 'User updated successfully.');
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }


    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $userModel = new \App\Models\UserModel();
        $customerModel = new \App\Models\CustomerModel();

        $user = $userModel->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        // Get admin for auto transfer
        $admin = $userModel->where('role', 'admin')->first();
        if (!$admin) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Admin not found'
            ]);
        }

        // Transfer this user’s clients to admin
        $customerModel->where('user_id', $id)
            ->set(['user_id' => $admin['id']])
            ->update();

        // Delete user
        $userModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'User deleted successfully and clients transferred to admin'
        ]);
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



    // user dashboard
    public function dashboard()
    {
        $clientModel = new ClientModel();
        $clientUserModel = new ClientUserModel();
        $customerModel = new CustomerModel();
        $userModel = new UserModel();
        $role = session()->get('role');
        $userId = session()->get('user_id');
        $transactionModel = new TransactionModel();
        $clients = $clientModel->select('clients.id, clients.company_name, clients.name,clients.url,clients.logo,created_by')
            ->join('client_users', 'client_users.client_id = clients.id', 'left')
            ->groupStart()
            ->where('clients.user_id', $userId)
            ->orWhere('client_users.user_id', $userId)
            ->groupEnd()
            ->groupBy('clients.id')
            ->orderBy('clients.company_name', 'ASC')
            ->get()
            ->getResultArray();



        $totalCustomer = $customerModel->where('user_id', $userId)->where('is_deleted', 0)->countAllResults();
        $transactionCount = $transactionModel->where('user_id', $userId)->countAllResults();
        $totalPaid = $transactionModel->where('user_id', $userId)->selectSum('paid_amount')->get()->getRow()->paid_amount ?? 0;
        $totalRemaining = $transactionModel->where('user_id', $userId)->selectSum('remaining_amount')->get()->getRow()->remaining_amount ?? 0;
        $totalLienceKeys = $transactionModel->where('user_id', $userId)->selectSum('total_code')->get()->getRow()->total_code ?? 0;
        $totalCode =  $transactionModel->where('user_id', $userId)->selectSum('code')->get()->getRow()->code ?? 0;
        $extraCode =  $transactionModel->where('user_id', $userId)->selectSum('extra_code')->get()->getRow()->extra_code ?? 0;
        $recentTransaction = $transactionModel->where('user_id', $userId)
            ->select('recipt_no')
            ->orderBy('created_at', 'DESC')
            ->get(1)
            ->getRow();
        $recentFiveTransaction = $transactionModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll(5);
        $recentTransactionCode = $recentTransaction->recipt_no ?? 'No Transactions Yet';
        $totals = $transactionModel
            ->select("
        SUM(CASE WHEN gst_applied = 1 THEN grand_total ELSE 0 END) AS amount_with_gst,
        SUM(CASE WHEN gst_applied = 0 THEN total_amount ELSE 0 END) AS amount_without_gst,
        SUM(
            CASE
                WHEN gst_applied = 1 THEN grand_total
                ELSE total_amount
            END
        ) AS overall_amount
    ", false)
            ->where('user_id', $userId)
            ->first();
        $todayRvenue = $transactionModel
            ->where('user_id', $userId)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->selectSum('paid_amount')
            ->get()
            ->getRow()
            ->paid_amount ?? 0;

        $thisMonthRevenue = $transactionModel
            ->where('user_id', $userId)
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->selectSum('paid_amount')
            ->get()
            ->getRow()
            ->paid_amount ?? 0;

        // ================= MONTHLY REVENUE GRAPH (Last 6 Months) =================
        $monthlyRevenueResults = $transactionModel->getMonthlyRevenueData(6, $userId);
        $data['monthlyLabels']   = json_encode($monthlyRevenueResults['labels']);
        $data['monthlyRevenues'] = json_encode($monthlyRevenueResults['data']);


        // ================= TODAY REVENUE =================
        $db = \Config\Database::connect();
        $todayRevenue = $db->table('transactions')
            ->selectSum('paid_amount')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->where('user_id', $userId)
            ->get()->getRow()->paid_amount ?? 0;
        $data['todayRevenue'] = (float)$todayRevenue;


        $startOfMonth = date('Y-m-01');
        $endOfMonth   = date('Y-m-t');

        $daily = $db->table('transactions')
            ->select("DATE(created_at) as day, SUM(paid_amount) as total")
            ->where("created_at >=", $startOfMonth)
            ->where("created_at <=", $endOfMonth)
            ->where('user_id', $userId)
            ->groupBy("DATE(created_at)")
            ->orderBy("DATE(created_at)", "ASC")
            ->get()->getResultArray();

        $thisMonthLabels = [];
        $thisMonthValues = [];

        foreach ($daily as $row) {
            $thisMonthLabels[] = date('d M', strtotime($row['day']));
            $thisMonthValues[] = (float)$row['total'];
        }



        $gstTotals = $transactionModel
            ->where('user_id', $userId)
            ->select('SUM(cgst) as total_cgst, SUM(sgst) as total_sgst, SUM(igst) as total_igst')
            ->get()
            ->getRow();
        $data['totalCgst'] = (float) $gstTotals->total_cgst;
        $data['totalSgst'] = (float) $gstTotals->total_sgst;
        $data['totalIgst'] = (float) $gstTotals->total_igst;

        return view(
            'user/dashboard',
            [
                'totals' => $totals,
                'clients' => $clients,
                'totalTransactions' => $transactionCount,
                'totalPaid' => $totalPaid,
                'totalRemaining' => $totalRemaining,
                'totalLicenseKeys' => $totalLienceKeys,
                'totalCode' => $totalCode,
                'extraCode' => $extraCode,
                'recentTransactionCode' => $recentTransactionCode,
                'totalCustomer' => $totalCustomer,
                'recentFiveTransaction' => $recentFiveTransaction,
                'todayRevenue' => $todayRvenue,
                'thisMonthRevenue' => $thisMonthRevenue,
                'monthlyLabels'   => json_encode($monthlyRevenueResults['labels']),
                'monthlyRevenues' => json_encode($monthlyRevenueResults['data']),
                'thisMonthLabels' => json_encode($thisMonthLabels),
                'thisMonthValues' => json_encode($thisMonthValues),

                'totalCgst' => $data['totalCgst'],
                'totalSgst' => $data['totalSgst'],
                'totalIgst' => $data['totalIgst'],
            ]
        );
    }
}
