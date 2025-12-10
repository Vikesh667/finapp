<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    /**
     * User Repository Service
     */
    public static function userRepository($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('userRepository');
        }

        return new \App\Repositories\Implementations\UserRepository();
    }

    /**
     * User Service
     */
    public static function userService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('userService');
        }

        return new \App\Services\UserService(
            static::userRepository(false)
        );
    }

    public static function customerRepository($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('customerRepository');
        }

        return new \App\Repositories\Implementations\CustomerRepository();
    }
}
