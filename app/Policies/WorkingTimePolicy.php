<?php

namespace App\Policies;

use App\User;
use App\WorkingTime;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingTimePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the working time.
     *
     * @param  \App\User  $user
     * @param  \App\WorkingTime  $workingTime
     * @return mixed
     */
    public function view(User $user, WorkingTime $workingTime)
    {
        // 
    }

    /**
     * Determine whether the user can create working times.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == 1 || $user->role == 2;
    }

    /**
     * Determine whether the user can update the working time.
     *
     * @param  \App\User  $user
     * @param  \App\WorkingTime  $workingTime
     * @return mixed
     */
    public function update(User $user, WorkingTime $workingTime)
    {
        return $user->role == 1 || $user->role == 2;
    }

    /**
     * Determine whether the user can delete the working time.
     *
     * @param  \App\User  $user
     * @param  \App\WorkingTime  $workingTime
     * @return mixed
     */
    public function delete(User $user, WorkingTime $workingTime)
    {
        return $user->role == 1 || $user->role == 2; 
    }
    
    /**
     * Determine whether the user can restore the working time.
     *
     * @param  \App\User  $user
     * @param  \App\WorkingTime  $workingTime
     * @return mixed
     */
    public function restore(User $user, WorkingTime $workingTime)
    {
        //
    }
    
    /**
     * Determine whether the user can permanently delete the working time.
     *
     * @param  \App\User  $user
     * @param  \App\WorkingTime  $workingTime
     * @return mixed
     */
    public function forceDelete(User $user, WorkingTime $workingTime)
    {
        return $user->role == 1 || $user->role == 2; 
    }
}
