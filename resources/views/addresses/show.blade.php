@extends('layout.app')

@section('title','Просмотр адреса "'.$address->name.'"')

@section('content')
    <ol class="breadcrumb">
        @if (request()->user()->id == $address->user_id)
            <li><a href="{{ route('addresses.index') }}">Мои адреса</a></li>
        @else
            <li><a href="{{ route('addresses.all') }}">Все адреса</a></li>
        @endif
        <li class="active">{{ $address->name }}</li>
    </ol>
    <h2 @if ($address->not_in_use) class="text-danger"@endif>{{ $address->name }}</h2>
    @if (!$address->user)
        <p><strong>Пользователь: </strong> {{ \App\Models\User::withTrashed()->find($address->user_id)->username }} <em>(удален)</em></p>
    @elseif (request()->user()->id != $address->user->id)
        @can('update', $address->user)
            <p><strong>Пользователь: </strong><a href="{{ route('users.show', ['user' => $address->user]) }}">{{ $address->user->name }}</a></p>
        @else
            <p><strong>Пользователь: </strong>{{ $address->user->name }}</p>
        @endcan
    @endif
    <p><strong>URL: </strong><a target="_blank" href="{{ $address->url }}">{{ $address->url }}</a></p>
    <p><strong>Сокращённая ссылка: </strong><a href="{{ route('addresses.slug', ['slug'=>$address->slug]) }}" target="_blank">{{ route('addresses.slug', ['slug'=>$address->slug]) }}</a></p>
    <p><strong>Количество переходов: </strong>{{ $address->clicks }}</p>
    <a class="btn btn-default" href="{{ route('addresses.statistic', ['address' => $address->id]) }}"><span class="glyphicon glyphicon-stats"></span> Статистика ссылки</a>
    @can('delete', $address)
        <a class="btn btn-danger" href="#remove" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span> Удалить ссылку</a>
    @endcan
    <h3>Изменение данных ссылки</h3>
    <form method="post">
        <x-form name="name" displayName="Название" required="true" description="Название сайта" value="{{ old('name', $address->name) }}"></x-form>
        <div class="form-group">
            <div id="div_id_in_use" class="checkbox">
                <label for="id_in_use" class=""><input type="checkbox" name="in_use" class="checkboxinput" id="id_in_use" @if(!old()) @if(!$address->not_in_use) checked @endif @elseif(old('in_use')) checked @endif>В использовании</label>
                <div id="hint_id_in_use" class="help-block">Используется ли данный адрес</div>
            </div>
        </div>
        @csrf
        @method('put')
        <button type="submit" class="btn btn-primary">Изменить</button>
    </form>
    <div id="remove" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить ссылку</h4>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить данную ссылку?</p>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('addresses.show', ['address'=>$address]) }}">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger" id="removelink">Да</button>
                        <button type="button" id="removedismiss" class="btn btn-default" data-dismiss="modal">Нет</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
