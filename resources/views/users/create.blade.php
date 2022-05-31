@extends('layout.app')

@section('title', 'Добавить пользователя')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('users.index') }}">Список пользователей</a></li>
        <li class="active">Добавить пользователя</li>
    </ol>
    <h1>Добавить пользователя</h1>
    <form method="post" action="{{ route('users.index') }}">
        <x-form name="name" displayName="Имя" description="Имя пользователя сервиса" value="{{ old('name') }}"></x-form>
        <x-form name="username" displayName="Логин" description="Логин, который будет использоваться для входа на сайт" required="true" value="{{ old('username') }}"></x-form>
        <x-form name="email" type="email" displayName="E-mail" value="{{ old('email') }}"></x-form>
        <x-form name="password" type="password" displayName="Пароль" required="true" value="{{ old('password') }}"></x-form>
        <x-form name="password_confirmation" type="password" displayName="Повторите пароль" required="true" value="{{ old('password_confirmation') }}"></x-form>
        @can('addRole', \App\Models\User::class)
            <div id="div_id_role" class="form-group @error('role') has-error @enderror">
                <label class="control-label requiredField">Роль пользователя<span class="asteriskField">*</span></label>
                <div class="controls">
                    @php
                        $roles = ['Пользователь', 'Модератор', 'Администратор'];
                    @endphp
                    @foreach($roles as $key=>$role_name)
                        <div class="radio">
                            <label><input type="radio" name="role" value="{{ $key }}" @if(old('role') == $key) checked @endif>{{ $role_name }}</label>
                        </div>
                    @endforeach
                    @error('role')
                    <ul class="help-block">
                        @foreach($errors->get('role') as $error)
                            <li><strong>{{ $error }}</strong></li>
                        @endforeach
                    </ul>
                    @enderror
                </div>
            </div>
        @endcan
        @csrf
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection
