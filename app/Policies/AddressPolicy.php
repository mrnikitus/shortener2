<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Address $address)
    {
        return $address->user_id === $user->id or $user->role === 1 or $user->role === 2;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Address $address)
    {
        return $address->user_id === $user->id or $user->role === 1 or $user->role === 2;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Address $address)
    {
        $role = isset($address->user->role) ? $address->user->role : $address->user_role;
        return $address->user_id === $user->id or ($user->role === 1 and $role !== 2) or $user->role === 2;
    }

    /**
     * Политика просмотра статистики
     *
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function statistic(User $user, Address $address) {
        return $address->user_id === $user->id or $user->role === 1 or $user->role === 2;
    }

    /**
     * Просмотр всех адресов от всех пользователей
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAll(User $user)
    {
        return $user->role === 1 or $user->role === 2;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Address $address)
    {
        $role = isset($address->user->role) ? $address->user->role : $address->user_role;
        return $address->user_id === $user->id or ($user->role === 1 and $role !== 2) or $user->role === 2;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Address $address)
    {
        $role = isset($address->user->role) ? $address->user->role : $address->user_role;
        return $address->user_id === $user->id or ($user->role === 1 and $role !== 2) or $user->role === 2;
    }
}
