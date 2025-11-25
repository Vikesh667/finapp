<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ClientModel extends Model
{
    protected $table         = 'clients';
    protected $primaryKey    = 'id';

    protected $allowedFields = [
        'user_id',       
        'created_by',
        'username',
        'name',
        'company_name',
        'email',
        'url',
        'logo',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name'  => 'required|alpha_space|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[clients.email,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'The name field is required.',
            'alpha_space' => 'The name can only contain letters and spaces.',
            'min_length'  => 'The name must be at least 3 characters long.',
            'max_length'  => 'The name cannot exceed 255 characters.',
        ],
        'email' => [
            'required'   => 'The email field is required.',
            'valid_email'=> 'Please provide a valid email address.',
            'is_unique'  => 'This email is already registered.',
        ],
    ];

    protected $skipValidation = false;


     
    public function getFilteredClients($role, $userId, $keyword = null, $filterUserId = null)
    {
        $builder = $this->select('clients.*, u.name AS user_name, c.name AS created_by_name')
            ->join('users AS u', 'u.id = clients.user_id', 'left')
            ->join('users AS c', 'c.id = clients.created_by', 'left')
            ->join('client_users AS cu', 'cu.client_id = clients.id', 'left')
            ->groupBy('clients.id')
            ->orderBy('clients.created_at', 'DESC');

        if ($role === 'user') {
            $builder->groupStart()
                ->where('clients.user_id', $userId)
                ->orWhere('clients.created_by', $userId)
                ->orWhere('cu.user_id', $userId)
            ->groupEnd();
        }

        if (!empty($filterUserId)) {
            $builder->groupStart()
                ->where('clients.user_id', $filterUserId)
                ->orWhere('cu.user_id', $filterUserId)
            ->groupEnd();
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('clients.name', $keyword)
                ->orLike('clients.email', $keyword)
                ->orLike('clients.company_name', $keyword)
            ->groupEnd();
        }

        return $builder;
    }



    public function saveClient(array $data, $file = null, $ownerId = null, $loggedInUserId = null, $clientId = null)
    {
        $logoName = $data['old_logo'] ?? '';

        //  Handle logo upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $logoName = $file->getRandomName();
            $file->move(FCPATH . 'assets/uploads/logos', $logoName);
        }

        $clientData = [
            'username'     => trim($data['username'] ?? ''),
            'name'         => trim($data['name'] ?? ''),
            'company_name' => trim($data['company_name'] ?? ''),
            'email'        => strtolower(trim($data['email'] ?? '')),
            'url'          => trim($data['url'] ?? ''),
            'created_by'   => $loggedInUserId ?? null,
        ];

        if (!empty($logoName)) {
            $clientData['logo'] = $logoName;
        }

        //  CREATE (allow no owner)
        if (empty($clientId)) {
            if (!empty($ownerId)) {
                $clientData['user_id'] = (int) $ownerId;
            } else {
                $clientData['user_id'] = null; // no owner yet
            }

            $insertId = $this->insert($clientData);
            if (!$insertId) {
                log_message('error', 'Client insert failed: ' . json_encode($this->errors()));
                throw new Exception('Failed to insert client record.');
            }
            return $insertId;
        }

        //  UPDATE (do not change owner automatically)
        return $this->update($clientId, $clientData);
    }

    public function deleteClientById($id, $role, $userId)
    {
        $client = $this->find($id);
        if (!$client) {
            return false;
        }

        if ($role === 'user' && (int) $client['created_by'] !== (int) $userId) {
            return false;
        }

        if (!empty($client['logo'])) {
            $logoPath = FCPATH . 'assets/uploads/logos/' . $client['logo'];
            if (is_file($logoPath)) {
                @unlink($logoPath);
            }
        }

        return $this->delete($id);
    }
}
