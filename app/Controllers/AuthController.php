<?php

namespace App\Controllers;

use App\Models\LoginHistoryModel;
use App\Models\UserModel;


class AuthController extends BaseController
{
    public function login()
    {
        //  If already logged in → redirect based on role
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');

            if ($role === 'admin') {
                return redirect()->to(base_url('admin/'));
            } elseif ($role === 'user') {
                return redirect()->to(base_url('user/'));
            }
        }

        return view('auth/app-login');
    }

    public function authenticate()
    {
        $session = session();

        $userModel = new UserModel();
        $logHistryModel = new LoginHistoryModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email and password are required');
        }

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
        // Login success
        $userModel->update($user['id'], ['force_logout' => 0]);

        //  Store session
        $session->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'role'       => $user['role'],
            'profile_image'       => $user['profile_image'],
            'address'   =>  $user['address'],
            'city'      =>  $user['city'],
            'state'     =>  $user['state'],
            'country'   =>  $user['country'],
            'isLoggedIn' => true,

        ]);

        $ip = $this->request->getIPAddress();

        $apiURL = "http://ipwho.is/" . $ip;
        $location = json_decode(file_get_contents($apiURL), true);

        if (!empty($location['success']) && $location['success'] == 1) {
            $city    = $location['city'] ?? 'Unknown';
            $state   = $location['region'] ?? 'Unknown';
            $country = $location['country'] ?? 'Unknown';
        } else {
            // Localhost fallback
            $city = 'Localhost';
            $state = 'Localhost';
            $country = 'Localhost';
        }

        $fullLocation = "$city, $state, $country";


        // Browser + Platform
        $agent = $this->request->getUserAgent();
        $browser  = $agent->getBrowser();
        $platform = $agent->getPlatform();

        // INSERT INTO DB
        $logHistryModel->insert([
            'user_id'     => $user['id'],
            'login_time'  => date('Y-m-d H:i:s'),
            'ip_address'  => $this->request->getIPAddress(), // Store original IP
            'user_agent'  => $browser,        // Full user agent
            'platform'    => $platform,
            'location'    => $fullLocation,
            'status'      => 'success'
        ]);

        // --------------------------------------------
        // DELETE OLD LOGIN HISTORY (KEEP ONLY LAST 10)
        // --------------------------------------------
        $db = \Config\Database::connect();

        // Count total login logs (ALL USERS)
        $total = $db->table('login_history')->countAllResults();

        if ($total > 10) {

            // Number of extra rows to delete
            $extra = $total - 10;

            // Get oldest rows globally
            $oldRows = $db->table('login_history')
                ->select('id')
                ->orderBy('login_time', 'ASC')  // oldest first
                ->limit($extra)
                ->get()
                ->getResultArray();

            $deleteIds = array_column($oldRows, 'id');

            if (!empty($deleteIds)) {
                $db->table('login_history')
                    ->whereIn('id', $deleteIds)
                    ->delete();
            }
        }


        if ($user['role'] === 'admin') {
            return redirect()->to(base_url('admin/'))->with('success', 'You are logged in successfully!');
        } else {
            return redirect()->to(base_url('user/'))->with('success', 'You are logged in successfully!');
        }
    }

    public function logout()
    {
        $loginModel = new LoginHistoryModel();
        $userModel = new UserModel();
        $userId     = session()->get('user_id');

        // Find the last login row for this user
        $lastLogin = $loginModel->where('user_id', $userId)
            ->where('status', 'success')
            ->orderBy('id', 'DESC')
            ->first();
        if ($lastLogin) {
            $loginModel->update($lastLogin['id'], [
                'logout_time' => date('Y-m-d H:i:s')
            ]);
        }
          session()->destroy();
     

        return redirect()->to(base_url('app-login'));
    }

    public function changed_password()
    {
        return view('user/change-password');
    }

    public function save_password()
    {
        $userModel = new UserModel();
        $session = session();
        $userId = $session->get('user_id');
        $oldPasswprd = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!password_verify($oldPasswprd, $user['password'])) {
            return redirect()->back()->with('error', 'old password is incorrect');
        }

        if ($newPassword != $confirmPassword) {
            return redirect()->back()->with('error', 'New password do not match');
        }

        if ($newPassword === $oldPasswprd) {
            return redirect()->back()->with('Error', 'Your new password and old password is matched');
        }

        $userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);
        $actionPath = ($session->get('role') === 'admin') ? base_url('admin/app-settings') : base_url('user/app-settings');
        $session->destroy();
        return redirect()->to($actionPath)->with('success', 'Password is changed successfully');
    }

    public function loging_history()
    {
        $db     = \Config\Database::connect();
        $model  = new LoginHistoryModel();
        $userId = session()->get('user_id');
        $role   = session()->get('role');

        $builder = $db->table('login_history')->select('*');

        if ($role !== 'admin') {
            $builder->where('user_id', $userId);
        }

        $data['history'] = $builder
            ->orderBy('login_time', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $data['lastLogin'] = $model->where('user_id', $userId)
            ->where('status', 'success')
            ->orderBy('login_time', 'DESC')
            ->first();

        return view('auth/login-history', $data);
    }

    // Admin Controller
    public function forceLogoutUser($userId)
    {
        $userModel = new UserModel();
        $userModel->update($userId, ['force_logout' => 1]);
        session()->destroy();
        return redirect()->back()->with('success', 'User has been forced to logout');
    }
}
