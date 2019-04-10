<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    //check if the user is an admin 
    public function isAdmin(User $user)
    {
        return $user->role ==1 || $user->role == 2;
    }
    
    //check if admin or the user itself
    public function isAllowed(User $modifier, User $user)
    {
        return $user->role ==1 || $user->role == 2 || $modifier->id == $user->id;
    }
}
