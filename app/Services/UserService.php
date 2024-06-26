<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserService
{
    // get client users
    public function getClientUsers()
    {
        return User::client()->get();
    }

    // update user
    public function updateUser(array $data, User $user)
    {
        $user->update($data);
        return $user->wasChanged();
    }



}
