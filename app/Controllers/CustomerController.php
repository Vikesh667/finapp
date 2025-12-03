<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CityModel;
use App\Models\CustomerModel;
use App\Models\ServiceModel;
use App\Models\ClientModel;
use App\Models\UserModel;
use App\Models\ClientUserModel;
use App\Models\CountryModel;
use App\Models\CustomerReassignHistoryModel;
use App\Models\StateModel;
use App\Models\TransactionHistoryModal;

class CustomerController extends BaseController
{
    //  List Customers
    public function customer_list()
    {
        $userModel     = new UserModel();
        $clientModel   = new ClientModel();
        $serviceModel  = new ServiceModel();

        $data['users']    = $userModel->findAll();     // dropdown for Admin only
        $data['clients']  = $clientModel->findAll();   // Client filter for both
        $data['services'] = $serviceModel->findAll();  // Service filter for both
        $data['role']     = session()->get('role');    // Send role to view

        return view('customer/customer-list', $data); // table loads by AJAX
    }

   public function listData()
{
    $session = session();
    $customerModel = new CustomerModel();
    $role   = $session->get('role');
    $userId = $session->get('user_id');

    $page      = (int) ($this->request->getGet('page') ?? 1);
    $search    = $this->request->getGet('search');
    $clientId  = $this->request->getGet('client_id');
    $serviceId = $this->request->getGet('service_id');

    $limit  = 10;
    $offset = ($page - 1) * $limit;

    // Base filters (role + assigned + created + client)
    $builder = $customerModel->getFilterdCustomer($role, $userId, $clientId);

    // Filter by service
    if (!empty($serviceId)) {
        $builder->where('clients.service_id', $serviceId);
    }

    // Search filter (fixed column names)
    if (!empty($search)) {
        $builder->groupStart()
            ->like('customers.name', $search)
            ->orLike('customers.shop_name', $search)
            ->orLike('clients.company_name', $search)  // client name
            ->orLike('createdBy.name', $search)        // created by
            ->orLike('assignedTo.name', $search)       // assigned user
            ->orLike('customers.device_type', $search)
        ->groupEnd();
    }

    $builder->where('customers.is_deleted', 0);

    // Clone for correct filtered count
    $countBuilder = clone $builder;

    // Fetch current page results
    $customers = $builder->orderBy('customers.id', 'DESC')->findAll($limit, $offset);

    // Count filtered
    $filtered = $countBuilder->countAllResults();

    // Count all records
    $total = $customerModel->where('is_deleted', 0)->countAllResults();

    return $this->response->setJSON([
        'customers'    => $customers,
        'current_page' => $page,
        'per_page'     => $limit,
        'total'        => $total,
        'filtered'     => $filtered,
        'total_pages'  => ceil($filtered / $limit),
        'role'         => $role
    ]);
}




    // ➕ Add Customer
    public function add_customer()
    {
        $customerModel = new CustomerModel();
        $countryModel  = new CountryModel();
        $stateModel    = new StateModel();
        $cityModel     = new CityModel();
        $session = session();

        $data = $this->request->getPost();
        $role = $session->get('role');
        $loggedUserId = $session->get('user_id');
        $clientId = $this->request->getPost('client_id');
        $userId = ($role === 'admin') ? $this->request->getPost('user_id') : $loggedUserId;

        $countryId = $this->request->getPost('country');
        $stateId = $this->request->getPost('state');
        $cityId = $this->request->getPost('city');

        $data['country'] = $countryModel->where('id', $countryId)->findColumn('name')[0] ?? null;
        $data['state']   = $stateModel->where('id', $stateId)->findColumn('name')[0] ?? null;
        $data['city']    = $cityModel->where('id', $cityId)->findColumn('name')[0] ?? null;

        $rules = $customerModel->getValidationRules();
        $messages = $customerModel->getValidationMessages();

        if (!$this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->with('error', implode("<br>", $this->validator->getErrors()))   // ⭐ FIXED
                ->withInput();
        }

        $customerModel->saveCustomer($data, $userId, $clientId, $loggedUserId);

        return redirect()
            ->to(base_url($role === 'admin' ? 'admin/customer-list' : 'user/customer-list'))
            ->with('success', 'client added successfully!');
    }

    public function edit_customer($id = null)
    {
        $customerModel = new CustomerModel();
        $customer = $customerModel->find($id);
        if (!$customer) {
            return redirect()->to(base_url('admin/customer-list'))->with('error', 'Customer not found');
        }

        $data['customer'] = $customer;
        return view('customer/edit-customer', $data);
    }

    public function updateCustomer()
    {
        $data = $this->request->getPost();
        $session = session();
        $customerModel = new CustomerModel();

        $role = $session->get('role');
        $loggedUserId = $session->get('user_id');
        $customerId = $this->request->getPost('id') ?? null;
        $existingCustomer = $customerId ? $customerModel->find($customerId) : null;

        $userId = ($role === 'admin')
            ? ($this->request->getPost('user_id') ?? $existingCustomer['user_id'] ?? $loggedUserId)
            : $loggedUserId;

        $clientId = $this->request->getPost('client_id') ?? $existingCustomer['client_id'] ?? null;

        $result = $customerModel->skipValidation(true)->saveCustomer($data, $userId, $clientId, $loggedUserId, $customerId);

        return $result
            ? redirect()->to('admin/customer-list')->with('success', 'Customer updated successfully!')
            : redirect()->back()->with('error', 'Failed to update customer!');
    }

    public function delete_customer($id = null)
    {
        $customerModel = new CustomerModel();
        $role = session()->get('role');
        $userId = session()->get('user_id');

        if (!$id || !$customerModel->find($id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Customer not found!'
            ]);
        }

        $customer = $customerModel->find($id);

        // only admin can delete any, user can delete only own customers
        if ($role !== 'admin' && $customer['user_id'] != $userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You do not have permission to delete this customer!'
            ]);
        }

        $customerDeleted = $customerModel->update($id, [
            'is_deleted' => 1,
            'deleted_by' => $userId,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        if ($customerDeleted === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete customer!'
            ]);
        } else {
              $notificationModel = new \App\Models\NotificationModel();
              if($role === 'user') {
                 $notificationModel->insert([
                  'user_id' => $customer['user_id'],
                  'message' => "Customer '{$customer['name']}' has been deleted.",
                  'is_read' => 0,
                  'created_at' => date('Y-m-d H:i:s')
              ]);
              }
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Customer deleted successfully!'
            ]);
        }
    }


    //  Get all customers of a client

    public function getCustomers()
    {
        $session  = session();
        $role     = $session->get('role');
        $loggedIn = $session->get('user_id');
        $customerModel = new CustomerModel();

        // Get values from GET/POST
        $clientId = $this->request->getGetPost('client_id');
        $userId   = $this->request->getGetPost('user_id'); // only used when admin selects user

        if (!$clientId) {
            return $this->response->setJSON([]);
        }

        if ($role === 'admin') {
            // Admin chooses user manually
            if (!$userId) {
                return $this->response->setJSON([]); // stop if admin didn't select user
            }

            $customers = $customerModel
                ->where('client_id', $clientId)
                ->where('user_id', $userId)
                ->orderBy('name', 'ASC')
                ->findAll();
        } else {
            // Normal user: filter automatically by logged in user
            $customers = $customerModel
                ->where('client_id', $clientId)
                ->where('user_id', $loggedIn)
                ->orderBy('name', 'ASC')
                ->findAll();
        }

        return $this->response->setJSON($customers);
    }


    public function getCustomersByUser($userId = null)
    {
        $session = session();
        $loggedIn = $session->get('user_id');
        $customerModel = new CustomerModel();

        // If userId not provided use logged user
        $userId = $userId ?? $loggedIn;

        $customers = $customerModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON($customers);
    }

    //  Get all users assigned to a specific client
    public function getClientUsers($clientId)
    {
        $clientUserModel = new ClientUserModel();

        $users = $clientUserModel
            ->select('users.id, users.name')
            ->join('users', 'users.id = client_users.user_id', 'left')
            ->where('client_users.client_id', $clientId)
            ->findAll();

        return $this->response->setJSON($users);
    }

    public function reassign_customer()
    {
        $userModel = new UserModel();
        $customerId = $this->request->getPost('customer_id');
        $newUserId  = $this->request->getPost('new_user_id');
        $adminId = session()->get('user_id');
        $user = $userModel->find($newUserId);
        $newUserName = $user['name'];

        if (!$customerId || !$newUserId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Missing customer or user ID'
            ]);
        }

        $customerModel = new \App\Models\CustomerModel();
        $clientUserModel = new \App\Models\ClientUserModel();
        $transactionModel = new \App\Models\TransactionModel();
        $transactionHistoryModel = new \App\Models\TransactionHistoryModal();
        $assignHistory = new \App\Models\CustomerReassignHistoryModel();

        $customer = $customerModel->find($customerId);
        if (!$customer) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Customer not found'
            ]);
        }

        $clientId = $customer['client_id'];
        $oldUserId = $customer['user_id'];

        // Ensure new user belongs to same client
        $isAssigned = $clientUserModel
            ->where('client_id', $clientId)
            ->where('user_id', $newUserId)
            ->countAllResults();

        if ($isAssigned == 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Selected user is not assigned to this client'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update ownership
        $customerModel->update($customerId, ['user_id' => $newUserId]);

        $transactionModel
            ->where('customer_id', $customerId)
            ->set(['user_id' => $newUserId])
            ->update();

        $transactionHistoryModel
            ->where('customer_id', $customerId)
            ->set(['user_id' => $newUserId])
            ->update();

        $assignHistory->insert([
            'customer_id'   => $customerId,
            'client_id'     => $clientId,
            'old_user_id'   => $oldUserId,
            'new_user_id'   => $newUserId,
            'reassigned_by' => $adminId,
            'type'          => 'single',
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Database transaction failed'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Customer reassigned successfully',
            'new_user_name' => $newUserName

        ]);
    }



    //  View Customer Details
    public function customer_detail($id = null)
    {
        $customerModel = new CustomerModel();
        $transactionModel = new \App\Models\TransactionModel();
        $transactionHistoryModel = new TransactionHistoryModal();
        $clientModel = new ClientModel();
        $userModel = new UserModel();
        $customer = $customerModel->find($id);
        if (!$customer) {
            return redirect()->to(base_url('customer-list'))->with('error', 'Customer not found');
        }

        $transactions = $transactionModel->where('customer_id', $id)->orderBy('created_at', 'DESC')->findAll();
        $paymentHistory = $transactionHistoryModel->where('customer_id', $id)->findAll();
        $companyName = $clientModel->find($customer['client_id']);
        $createdBy = $userModel->find($customer['created_by']);
        return view('customer/customer-details', [
            'customer' => $customer,
            'transactions' => $transactions,
            'paymentHistroy' => $paymentHistory,
            'companyName'    => $companyName,
            'createdBy'      => $createdBy
        ]);
    }
    public function bulk_reassign_customers()
    {
        $customerModel = new CustomerModel();
        $transactionModel = new \App\Models\TransactionModel();
        $transactionHistoryModel = new \App\Models\TransactionHistoryModal();
        $clientUserModel = new \App\Models\ClientUserModel();
        $assignHistory = new \App\Models\CustomerReassignHistoryModel();
        $clientId    = $this->request->getPost('client_id');
        $fromUserId  = $this->request->getPost('from_user_id');
        $toUserId    = $this->request->getPost('to_user_id');

        $loggedRole = session()->get('role');
        $loggedAdminId = session()->get('user_id');


        if (!$clientId || !$fromUserId || !$toUserId) {
            return redirect()->back()->with('error', 'Missing data.');
        }

        if ($fromUserId == $toUserId) {
            return redirect()->back()->with('error', 'Cannot reassign to same user.');
        }

        // Verify toUser belongs to same client
        // If admin wants to assign customer to themselves
        if ($toUserId == $loggedAdminId && $loggedRole == 'admin') {
            // allow direct assignment, skip validation
        } else {
            // validate only for normal users
            $checkToUser = $clientUserModel
                ->where('client_id', $clientId)
                ->where('user_id', $toUserId)
                ->countAllResults();

            if ($checkToUser == 0) {
                return redirect()->back()->with('error', 'Selected user is not assigned to this client.');
            }
        }


        $db = \Config\Database::connect();
        $db->transStart();

        // STEP 1: Update customers
        $customerModel
            ->where('client_id', $clientId)
            ->where('user_id', $fromUserId)
            ->set(['user_id' => $toUserId])
            ->update();

        // STEP 2: Update transactions
        $transactionModel
            ->where('client_id', $clientId)
            ->where('user_id', $fromUserId)
            ->set(['user_id' => $toUserId])
            ->update();

        // STEP 3: Update transaction history
        $transactionHistoryModel
            ->where('client_id', $clientId)
            ->where('user_id', $fromUserId)
            ->set(['user_id' => $toUserId])
            ->update();

        $movedCustomer = $customerModel
            ->where('client_id', $clientId)
            ->where('user_id', $toUserId)
            ->findAll();

        foreach ($movedCustomer as $cust) {
            $assignHistory->insert([
                'customer_id' => $cust['id'],
                'client_id' => $clientId,
                'old_user_id' => $fromUserId,
                'new_user_id' => $toUserId,
                'reassigned_by' => $loggedAdminId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Failed to reassign all customers.');
        }

        return redirect()->back()->with('success', 'All customers reassigned successfully!');
    }

    public function customer_assign_history()
    {
        $customerReassignHistoryModel = new CustomerReassignHistoryModel();
        $data['assign_history'] = $customerReassignHistoryModel->getAllHistory();

        return view('customer/customer_reassign_history', $data);
    }


    public function delete_customer_history()
    {
        return view('customer/customer_delete_history');
    }

    public function customerDeleteHistory()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('customers');
        $builder->select('customers.id, customers.name, customers.email, customers.shop_name, customers.deleted_at, customers.deleted_by, clients.company_name as client_names, users.name as deleted_by_name');
        $builder->join('clients', 'clients.id = customers.client_id', 'left');
        $builder->join('users', 'users.id = customers.deleted_by', 'left');
        $builder->where('customers.is_deleted', 1);
        $builder->orderBy('customers.deleted_at', 'DESC');
        $query = $builder->get();
        $data = [
            'deleted_customers' => $query->getResultArray()
        ];
        return $this->response->setJSON($data);
    }
}
