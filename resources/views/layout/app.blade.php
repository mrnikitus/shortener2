<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') – Shortener2</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('scripts')
</head>
<body>
<div class="container">
    <nav class="navbar navbar-default navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Переключить навигацию</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#info" data-toggle="modal" title="О сервисе">Shortener2</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    @can('viewAll', \App\Models\Address::class)
                        <li @if (request()->url() == route('addresses.all')) class="active" @endif><a href="{{ route('addresses.all') }}">Все адреса</a></li>
                    @endcan
                    <li @if (request()->url() == route('addresses.index')) class="active" @endif><a href="{{ route('addresses.index') }}">Мои адреса</a></li>
                    <li @if (request()->url() == route('addresses.create')) class="active" @endif><a href="{{ route('addresses.create') }}">Добавить адрес</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if (request()->url() != route('addresses.create'))
                        <li>
                            <form class="navbar-form" method="post" action="{{ route('addresses.index') }}">
                                <div class="form-group @error('url') has-error @enderror">
                                    @csrf
                                    <input type="url" maxlength="255" name="url" class="urlinput form-control @error('url') form-control-danger @enderror" required placeholder="Быстрое добавление" value="{{ old('url') }}">
                                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-link"></span></button>
                                </div>

                            </form>
                        </li>
                    @endif
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Вы вошли как {{ request()->user()->username }}">{{ request()->user()->name }}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @can('create',\App\Models\User::class)
                                <li><a href="{{ route('users.index') }}">Список пользователей</a></li>
                                <li><a href="{{ route('users.create') }}">Добавить пользователя</a></li>
                                <li role="separator" class="divider"></li>
                            @endcan
                            <li><a href="{{ route('users.show', ['user'=>request()->user()]) }}">Редактировать профиль</a></li>
                            <li><a href="#" onclick="document.getElementById('logout-form').submit(); return false;">Выйти</a></li>
                            <form id="logout-form" method="post" action="{{ route('logout') }}"> @csrf </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @error('url')
        @if (request()->url() != route('addresses.create'))
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @endif
    @enderror
    @if (request()->session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ request()->session()->get('success') }}
        </div>
    @endif
    @if (request()->session()->has('warning'))
        <div class="alert alert-warning" role="alert">
            {{ request()->session()->get('warning') }}
        </div>
    @endif
    @yield('content')
</div>
<div id="info" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Shortener2 by mrNiKitus</h4>
            </div>
            <div class="modal-body">
                <p><strong>Сокращатель ссылок с подсчетом статистики, написанный на Laravel.</strong></p>
                <p>Версия 2.0 от 31.05.2022</p>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p><strong>Список используемых компонентов</strong></p>
                        <ul>
                            <li>Laravel Framework {{ app()->version() }}</li>
                            <li>Bootstrap 3.4.1</li>
                            <li>Google Charts</li>
                            <li>Laravel Fortify 1.13.0</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-left">
                    <p>Текущая страница: <strong>{{ request()->fullUrl() }}</strong><br>
                        Дата/время открытия страницы: <strong>{{ date('d/m/Y H:i:s') }} UTC</strong><br>
                        Ваш IP: <strong>{{ request()->ip() }}</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <a target="_blank" class="btn btn-primary" href="https://mrnikitus.ru/">Сайт разработчика</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>
