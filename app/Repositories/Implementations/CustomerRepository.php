<?php
namespace App\Repositories\Implementations;
use App\Models\CustomerModel;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface{
    protected $model;

    public function __construct()
    {
        $this->model=new CustomerModel();
    }
     public function transferCustomers(int $oldUserId, int $newUserId): bool
    {
        return (bool) $this->model
            ->where('user_id', $oldUserId)
            ->set(['user_id' => $newUserId])
            ->update();
    }
}
