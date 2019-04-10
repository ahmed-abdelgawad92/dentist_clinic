<?php

namespace App\Policies;

use App\User;
use App\UserLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user log.
     *
     * @param  \App\User  $user
     * @param  \App\UserLog  $userLog
     * @return mixed
     */
    public function view(User $user, UserLog $userLog)
    {
        return $user->role == 1 || $user->role == 2;
    }
}
