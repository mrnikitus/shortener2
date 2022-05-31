<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Оптимизация запроса: выборка из таблицы всех пользователей, а также дополнительный столбец `addresses_count`, содержащий количество
        // неудаленных адресов для каждого пользователя
        $users_query = DB::table('users')->leftJoin('addresses','addresses.user_id','=','users.id')->whereNull('addresses.deleted_at')
            ->select('users.*', DB::raw('Count(addresses.id) as addresses_count'))->groupBy('users.id')
            ->orderBy('users.id', 'desc')->paginate(30);
        $users = User::hydrate($users_query->items());
        return response()->view('users.index', ['users'=>$users, 'users_query' => $users_query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->password = Hash::make($validated['password']);
        if (!array_key_exists('role', $validated)) {
            $user->role = 0;
        } else {
            $user->role = $validated['role'];
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'Пользователь '.$user->username.' был успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->view('users.show', ['user' => $user]);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\Http\Response
//     */
//    public function edit(User $user)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        if($request->user()->can('update', $user) and array_key_exists('password', $validated)) {
            $user->password = Hash::make($validated['password']);
        }
        if ($request->user()->can('changeRole', $user) and array_key_exists('role', $validated)) {
            $user->role = $validated['role'];
        }
        $user->save();
        $request->session()->flash('success', 'Данные пользователя успешно изменены');
        return response()->view('users.show', ['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if (request()->query('force') == request()->session()->token()) {
            $this->authorize('forceDelete', $user);
            $user->forceDelete();
            return redirect()->route('users.index')->with('warning','Пользователь '.$user->username.' безвозвратно удален.');
        }
        if ($user->trashed()) {
            $this->authorize('restore', $user);
            $user->restore();
            return redirect()->route('users.index')->with('success','Пользователь '.$user->username.' успешно восстановлен.');
        }
        else {
            $this->authorize('delete', $user);
            $user->deleteOrFail();
            return redirect()->route('users.index')->with('warning','Пользователь '.$user->username.' успешно удален.');
        }
    }
}
