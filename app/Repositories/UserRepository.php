<?php

namespace App\Repositories;

use App\Base\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUserByPhoneOrEmail($phone = null, $email = null)
    {
        return $this->model
            ->where(function ($q) use ($phone, $email) {
                $q->where('phone', $phone);
                if ($email)
                    $q->orWhere('email', $email);
            })->first();
    }
}
