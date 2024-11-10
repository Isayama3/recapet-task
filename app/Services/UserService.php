<?php

namespace App\Services;

use App\Base\Services\BaseService;
use App\Repositories\UserRepository;

class UserService extends BaseService
{
    protected UserRepository $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        parent::__construct($UserRepository);
        $this->UserRepository = $UserRepository;
    }

    public function getUserByPhoneOrEmail($phone = null, $email = null)
    {
        return $this->UserRepository->getUserByPhoneOrEmail($phone, $email);
    }
}
