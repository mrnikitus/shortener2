@extends('layout.mini')

@section('title','Вход в систему')

@section('content')
    <div class="container" style="max-width: 384px; min-width: 384px">
        <div class="panel panel-primary">
            <div class="panel-heading"><h3>Вход</h3></div>
            <div class="panel-body">
                <form method="post">
                    @csrf
                    <div id="div_id_username" class="form-group @error('username') has-error @enderror">
                        <label for="id_username" class="control-label requiredField">Имя пользователя<span class="asteriskField">*</span></label>
                        <div class="controls ">
                            <input type="text" name="username" autofocus="" autocapitalize="none" autocomplete="username" maxlength="150" class="textinput textInput form-control" required id="id_username" value="{{ old('username') }}">
                        </div>
                    </div>
                    <div id="div_id_password" class="form-group @error('password') has-error @enderror">
                        <label for="id_password" class="control-label  requiredField">Пароль<span class="asteriskField">*</span></label>
                        <div class="controls ">
                            <input type="password" name="password" autocomplete="current-password" class="textinput textInput form-control" required="" id="id_password"  value="{{ old('password') }}">
                        </div>
                    </div>
                    @if($errors->any())
                    <ul class="help-block" style="color: #a94442 !important;">
                        @foreach($errors->all() as $error)
                            <li><strong>{{ $error }}</strong></li>
                        @endforeach
                    </ul>
                    @endif
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
@endsection
