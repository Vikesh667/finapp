<?php
namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface {
    public function transferCustomers(int $oldUserId,int $newUserId);
}