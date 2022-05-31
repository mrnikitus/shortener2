@extends('layout.app')

@section('title', 'Добавить адрес')

@section('content')
    <h1>Добавить адрес</h1>
    <form method="post" action="{{ route('addresses.index') }}">
        <x-form name="name" displayName="Название" description="Название сайта. Если не введено, вместо него будет адрес сайта" value="{{ old('name') }}"></x-form>
        <x-form name="url" type="url" required="true" displayName="Адрес сайта" description="Адрес веб-сайта, который надо сократить. Набирайте его вместе с &quot;http://&quot; или &quot;https://&quot;" value="{{ old('url') }}"></x-form>
        <x-form name="slug" displayName="Короткий адрес" length="30" description="Короткий адрес, который следует после &quot;{{  route('root') }}/&quot;. Если поле пустое, генерируется автоматически. Значение должно быть уникальным. Просьба не использовать зарезервированные имена, так как в таком случае ссылка не будет работать" value="{{ old('slug') }}"></x-form>
        @csrf
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection
