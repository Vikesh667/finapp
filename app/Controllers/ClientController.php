<?php

namespace App\Controllers;

use App\Models\ClientAssignHistoryModel;
use App\Models\ClientModel;
use App\Models\ClientUserModel;
use App\Models\CustomerModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Exception;

class ClientController extends BaseController
{
    public function client_list()
    {
        $clientModel = new ClientModel();
        $userModel   = new UserModel();

        $search       = $this->request->getGet('search');
        $filterUserId = $this->request->getGet('user_id');
        $role         = session()->get('role');
        $loggedUserId = session()->get('user_id');

        $builder = $clientModel->getFilteredClients(
            $role,
            $loggedUserId,
            $search,
            $filterUserId
        );

        $data = [
            'clients'      => $builder->paginate(10, 'clients'),
            'pager'        => $clientModel->pager,
            'search'       => $search,
            'user'         => $userModel->findAll(),
            'selectedUser' => $filterUserId,
        ];

        return view('client/client-list', $data);
    }
    public function client_list_data()
    {
        $clientModel = new ClientModel();

        $page         = (int) ($this->request->getGet('page') ?? 1);
        $search       = $this->request->getGet('search');
        $filterUserId = $this->request->getGet('user_id');
        $role         = session()->get('role');
        $loggedUserId = session()->get('user_id');

        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Base filtered query
        $builder = $clientModel->getFilteredClients($role, $loggedUserId, $search, $filterUserId);

        // Clone before applying limit/offset
        $countBuilder = clone $builder;

        // Apply LIMIT
        $clients = $builder->orderBy('clients.id', 'DESC')->findAll($limit, $offset);

        // Count search-filtered records
        $filtered = $countBuilder->countAllResults();

        // Count total clients (no search/no filter)
        $total = $clientModel->countAll();

        return $this->response->setJSON([
            'clients'      => $clients,
            'current_page' => $page,
            'per_page'     => $limit,
            'total'        => $total,
            'filtered'     => $filtered,
            'total_pages'  => ceil($filtered / $limit)
        ]);
    }



    public function add_client()
    {
        $clientModel = new \App\Models\ClientModel();
        $session     = session();

        $data   = $this->request->getPost();
        $logo   = $this->request->getFile('logo');
        $logged = $session->get('user_id');

        try {
            if (!$this->validate($clientModel->getValidationRules(), $clientModel->getValidationMessages())) {
                $errors = $this->validator->getErrors();
                return redirect()->back()->withInput()->with('error', implode('<br>', $errors));
            }

            //  No need to pass user_id now
            $clientId = $clientModel->saveClient($data, $logo, null, $logged);

            if ($clientId) {
                return redirect()->to(base_url('admin/client-list'))
                    ->with('success', 'Client added successfully (no user assigned yet).');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to add client.');
        } catch (\Exception $e) {
            log_message('error', 'Add client failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }



    public function edit_client($id = null)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/unauthorized');
        }

        $clientModel = new ClientModel();
        $client      = $clientModel->find($id);

        if (!$client) {
            return redirect()->to('admin/client-list')->with('error', 'Client not found.');
        }

        return view('client/edit-client', ['client' => $client]);
    }


    public function update_client()
    {
        $session     = session();
        $clientModel = new ClientModel();

        $clientId = $this->request->getPost('id');
        $client   = $clientModel->find($clientId);

        if (!$client) {
            return redirect()->to('admin/client-list')->with('error', 'Client not found.');
        }

        if ($session->get('role') === 'user') {
            return redirect()->to('/unauthorized');
        }

        $data   = $this->request->getPost();
        $logo   = $this->request->getFile('logo');

        $logged = $session->get('user_id');

        // Preserve or fallback to admin
        $ownerId = $client['user_id'] ?: $logged;

        try {
            $clientModel->saveClient($data, $logo, $ownerId, $logged, $clientId);
            return redirect()->to('admin/client-list')->with('success', 'Client updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }



    public function delete_client($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $clientModel = new ClientModel();
        $session     = session();

        $deleted = $clientModel->deleteClientById(
            $id,
            $session->get('role'),
            $session->get('user_id')
        );

        if (!$deleted) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client not found or unauthorized'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Client deleted successfully'
        ]);
    }



    public function assign_user()
    {
        $clientUserModel       = new \App\Models\ClientUserModel();
        $clientModel           = new \App\Models\ClientModel();
        $assignHistoryModel    = new \App\Models\ClientAssignHistoryModel();

        $clientId      = $this->request->getPost('client_id');
        $selectedUsers = $this->request->getPost('user_id') ?? [];
        $adminId       = session()->get('user_id');

        // Get current assigned users
        $existingUsers = $clientUserModel
            ->where('client_id', $clientId)
            ->findColumn('user_id') ?? [];

        // Convert to integers
        $existingUsers = array_map('intval', $existingUsers);
        $selectedUsers = array_map('intval', $selectedUsers);

        // ASSIGN NEW USERS
        foreach ($selectedUsers as $uid) {
            if (!in_array($uid, $existingUsers)) {

                // Insert into client_user table
                $clientUserModel->insert([
                    'client_id'   => $clientId,
                    'user_id'     => $uid,
                    'assigned_by' => $adminId
                ]);

                // Insert history for assigned
                $assignHistoryModel->insert([
                    'client_id' => $clientId,
                    'user_id'   => $uid,
                    'action'    => 'assigned',
                    'admin_id'  => $adminId
                ]);
            }
        }

        // UNASSIGN REMOVED USERS
        foreach ($existingUsers as $uid) {
            if (!in_array($uid, $selectedUsers)) {

                // Remove from client_user table
                $clientUserModel
                    ->where('client_id', $clientId)
                    ->where('user_id', $uid)
                    ->delete();

                // Insert history for unassigned
                $assignHistoryModel->insert([
                    'client_id' => $clientId,
                    'user_id'   => $uid,
                    'action'    => 'unassigned',
                    'admin_id'  => $adminId
                ]);
            }
        }

        // UPDATE MAIN CLIENT OWNER
        $finalAssigned = $clientUserModel
            ->where('client_id', $clientId)
            ->findColumn('user_id');

        if (!empty($finalAssigned)) {
            // first user becomes owner
            $clientModel->update($clientId, ['user_id' => $finalAssigned[0]]);
        } else {
            $clientModel->update($clientId, ['user_id' => null]);
        }

        return redirect()
            ->to(base_url('admin/client-list'))
            ->with('success', 'User assignment updated successfully.');
    }



    public function get_users_for_client($clientId)
    {
        try {
            $userModel = new \App\Models\UserModel();
            $clientUserModel = new \App\Models\ClientUserModel();

            // Fetch all users
            $allUsers = $userModel->select('id, name')->findAll();

            // Fetch assigned user IDs
            $assigned = $clientUserModel
                ->where('client_id', $clientId)
                ->findColumn('user_id') ?? [];
            // Return JSON response
            return $this->response->setJSON([
                'success'  => true,
                'users'    => $allUsers,
                'assigned' => $assigned,
            ]);
        } catch (\Throwable $e) {
            // Return error for debugging
            return $this->response->setJSON([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function getClientsByUser($userId)
    {
        $clientModel = new \App\Models\ClientModel();

        $clients = $clientModel->select('clients.id, clients.company_name, clients.name')
            ->join('client_users', 'client_users.client_id = clients.id', 'left')
            ->groupStart()
            ->where('clients.user_id', $userId)
            ->orWhere('client_users.user_id', $userId)
            ->groupEnd()
            ->groupBy('clients.id')
            ->orderBy('clients.company_name', 'ASC')
            ->get()
            ->getResultArray();  // ✔ Correct fetch

        return $this->response->setJSON($clients);
    }


    public function single_client($id = null)
    {
        $clientModel = new ClientModel();
        $customerModel = new CustomerModel();
        $transactionModel = new TransactionModel();
        $product = $clientModel->where('clients.id', $id)->first();
        $totalCustomer = $customerModel->where('customers.client_id', $id)->findAll();
        $transactions = $transactionModel->where('transactions.client_id', $id)->findAll();
        $totalCode = $transactionModel->where('transactions.client_id', $id)->selectSum('code')->get()->getRow()->code ?? 0;
        $totalExtracode = $transactionModel->where('transactions.client_id', $id)->selectSum('extra_code')->get()->getRow()->extra_code ?? 0;
        $totalPaidAmount = $transactionModel->where('transactions.client_id', $id)->selectSum('paid_amount')->get()->getRow()->paid_amount ?? 0;
        $totalPaindingAmount = $transactionModel->where('transactions.client_id', $id)->selectSum('remaining_amount')->get()->getRow()->remaining_amount ?? 0;
        $totalAmount = $transactionModel->where('transactions.client_id', $id)->selectSum('total_amount')->get()->getRow()->total_amount ?? 0;
        return view('products/dashboard', [
            'product' => $product,
            'customers' => $totalCustomer,
            'transactions' => $transactions,
            'totalCode'   => $totalCode,
            'totalextraCode' => $totalExtracode,
            'totalPaidAmount' => $totalPaidAmount,
            'totalPaindingAmount' => $totalPaindingAmount,
            'totalAmount' => $totalAmount
        ]);
    }

    public function client_assing_history()
    {
        $clientAssignHistory = new ClientAssignHistoryModel();
        $data['assign_history'] = $clientAssignHistory->getFullHistory();

        return view('client/client_assign_history', $data);
    }
}
