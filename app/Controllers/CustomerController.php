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
        $customerModel = new CustomerModel();
        $userModel     = new UserModel();
        $clientModel   = new ClientModel();
        $serviceModel  = new ServiceModel();

        $role   = session()->get('role');
        $userId = session()->get('user_id');
        $filterClientId = $this->request->getGet('client_id');

        $builder = $customerModel->getFilterdCustomer($role, $userId, $filterClientId);
        $data['customers'] = $builder->paginate(30, 'customers');

        $data['pager'] = $customerModel->pager;

        $data['users']   = $userModel->findAll();
        $data['clients'] = $clientModel->findAll();
        $data['services'] = $serviceModel->findAll();

        return view('customer/customer-list', $data);
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
            ->to(base_url($role === 'admin' ? 'admin/customer-list' : '/'))
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

        $result = $customerModel->saveCustomer($data, $userId, $clientId, $loggedUserId, $customerId);

        return $result
            ? redirect()->to('admin/customer-list')->with('success', 'Customer updated successfully!')
            : redirect()->back()->with('error', 'Failed to update customer!');
    }

    public function delete_customer($id = null)
    {
        $customerModel = new CustomerModel();
        $role = session()->get('role');

        if (!$id || !$customerModel->find($id)) {
            return redirect()->back()->with('error', 'Customer not found!');
        }

        $customerModel->delete($id);

        return redirect()
            ->to($role === 'admin' ? base_url('admin/customer-list') : base_url('user/customer-list'))
            ->with('success', 'Customer deleted successfully!');
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
        $customerModel = new \App\Models\CustomerModel();
        $clientUserModel = new \App\Models\ClientUserModel();
        $transactionModel = new \App\Models\TransactionModel();
        $transactionHistoryModel = new \App\Models\TransactionHistoryModal();
        $assignHistory = new \App\Models\CustomerReassignHistoryModel();
        $customerId = $this->request->getPost('customer_id');
        $newUserId  = $this->request->getPost('new_user_id');
        $adminId = session()->get('user_id');
        if (!$customerId || !$newUserId) {
            return redirect()->back()->with('error', 'Missing data.');
        }

        //  Find the customer
        $customer = $customerModel->find($customerId);
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        $clientId = $customer['client_id'];
        $oldUserId = $customer['user_id'];
        //  Ensure new user belongs to same client
        $isAssigned = $clientUserModel
            ->where('client_id', $clientId)
            ->where('user_id', $newUserId)
            ->countAllResults();

        if ($isAssigned == 0) {
            return redirect()->back()->with('error', 'This user is not assigned to the same client.');
        }
        $db = \Config\Database::connect();
        $db->transStart();

        //  Update ownership
        $updated = $customerModel->update($customerId, ['user_id' => $newUserId]);
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
            return redirect()->back()->with('error', 'Failed to reassign customer with transactions.');
        }
        if (!$updated) {
            return redirect()->back()->with('error', 'Failed to reassign customer.');
        }

        return redirect()->back()->with('success', 'Customer reassigned successfully.');
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
        $createdBy=$userModel->find($customer['created_by']);
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
}
