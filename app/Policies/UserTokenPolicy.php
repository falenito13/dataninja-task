<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserToken;

class UserTokenPolicy
{

    public function create(User $user): bool
    {
        return $user->is_verified;
    }

    public function delete(User $user, UserToken $userToken): bool
    {
        return $user->id === $userToken->user_id;
    }
}
