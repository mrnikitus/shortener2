@extends('layout.app')

@section('title', 'Пользователь '.$user->name)

@section('content')
    @can('create',\App\Models\User::class)
    <ol class="breadcrumb">
        <li><a href="{{ route('users.index') }}">Список пользователей</a></li>
        <li class="active">{{ $user->name }}</li>
    </ol>
    @endcan
    <h2>{{ $user->name }}</h2>
    <p><strong>ID: </strong>{{ $user->id }}</p>
    <p><strong>Дата регистрации: </strong>{{ $user->created_at->format('d/m/Y H:i:s') }} UTC</p>
    <p><strong>Количество адресов: </strong>{{ $user->current_count() }}</p>
    @can('delete', $user)
        <a class="btn btn-danger" href="#remove" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span> Удалить пользователя</a>
    @endcan
    <h3>Изменение данных пользователя</h3>
    <form method="post">
        <x-form name="name" displayName="Имя" description="Имя пользователя сервиса" required="true" value="{{ old('name', $user->name) }}"></x-form>
        <x-form name="username" displayName="Логин" description="Логин, который будет использоваться для входа на сайт" required="true" value="{{ old('username', $user->username) }}"></x-form>
        <x-form name="email" type="email" displayName="E-mail" value="{{ old('email', $user->email) }}"></x-form>
        @if (request()->user()->id == $user->id)
            <x-form name="old_password" type="password" displayName="Старый пароль" value="{{ old('old_password') }}"></x-form>
        @endif
        <x-form name="password" type="password" displayName="Новый пароль" value="{{ old('password') }}"></x-form>
        <x-form name="password_confirmation" type="password" displayName="Повторите новый пароль" value="{{ old('password_confirmation') }}"></x-form>
        @can('changeRole', $user)
            <div id="div_id_role" class="form-group @error('role') has-error @enderror">
                <label class="control-label requiredField">Роль пользователя<span class="asteriskField">*</span></label>
                <div class="controls">
                    @php
                      $roles = ['Пользователь', 'Модератор', 'Администратор'];
                    @endphp
                    @foreach($roles as $key=>$role_name)
                        <div class="radio">
                            <label><input type="radio" name="role" value="{{ $key }}" @if(!old()) @if($user->role == $key) checked @endif @elseif(old('role') == $key) checked @endif>{{ $role_name }}</label>
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
        @method('put')
        @csrf
        <button type="submit" class="btn btn-primary">Изменить</button>
    </form>
    @can('delete', $user)
        <div id="remove" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Удалить пользователя</h4>
                    </div>
                    <div class="modal-body">
                        <p>Вы действительно хотите удалить данного пользователя?</p>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="{{ route('users.show', ['user'=>$user]) }}">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger" id="removelink">Да</button>
                            <button type="button" id="removedismiss" class="btn btn-default" data-dismiss="modal">Нет</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
