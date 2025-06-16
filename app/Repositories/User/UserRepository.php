<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function softDelete($id)
    {
        $user = $this->getById($id);
        if ($user) {
            $user->deleted_at = now();
            $user->save();
            return $user;
        }
        return false;
    }
}