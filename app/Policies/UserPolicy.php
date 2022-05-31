<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->role == 1 or $user->role == 2;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return $user->role == 2 or $user->id == $model->id or ($user->role == 1 and $model->role == 0);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role == 1 or $user->role == 2;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        return $user->role == 2 or $user->id == $model->id or ($user->role == 1 and $model->role ==0);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return ($user->id != $model->id) and ($user->role == 2 or ($user->role == 1 and $model->role == 0));
    }

    /**
     * Возможность изменять роли. Доступна только для администраторов (role = 2), самому себе менять нельзя.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function changeRole(User $user, User $model) {
        return $user->role == 2 and $user->id != $model->id;
    }

    /**
     * Возможность добавлять роли. Доступна только для администраторов (role = 2).
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function addRole(User $user) {
        return $user->role === 2;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return $user->role == 2 or ($user->role == 1 and $model->role == 0);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        return ($user->id != $model->id) and ($user->role == 2 or ($user->role == 1 and $model->role == 0));
    }
}
