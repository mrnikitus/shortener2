@extends('layout.app')

@section('title','Список пользователей')

@section('content')
    <h1>Список пользователей</h1>
    @if (count($users) === 0)
        <div class="alert alert-warning" role="alert">В системе пока нет ни одного пользователя :(</div>
    @else
    @can('create', \App\Models\User::class)
        <p><a class="btn btn-primary" href="{{ route('users.create') }}"><span class="glyphicon glyphicon-user"></span> Добавить пользователя</a></p>
    @endcan
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-responsive" style="margin-bottom: 5px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Логин</th>
                <th>E-mail</th>
                <th>Дата регистрации</th>
                <th>Количество адресов</th>
                <th>Роль</th>
                <th style="min-width: 120px;">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr @if($user->trashed()) class="danger" @endif>
                    <td>{{ $user->id }}</td>
                    <td>
                        @if (request()->user()->can('update',$user) and !$user->trashed())
                            <a href="{{ route('users.show', ['user' => $user]) }}" title="Информация о пользователе"><strong>{{ $user->name }}</strong></a>
                        @else
                            <strong>{{ $user->name }}</strong>
                        @endif
                    </td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email ? : '–' }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i:s') }} UTC</td>
                    <td>{{ $user->addresses_count }}</td>
                    <td>
                        @switch($user->role)
                            @case(0) Пользователь @break
                            @case(1) Модератор @break
                            @case(2) Администратор @break
                        @endswitch
                    </td>
                    <td style="/* width: 120px; */">
                        <div class="btn-group" role="group">
                            @if(!$user->trashed())
                                @can('delete', $user)
                                    <a href="#" onclick="showconfirm('{{ $user->name }}', {{ $user->id }})" class="btn btn-default btn-sm" title="Удалить пользователя"><span class="glyphicon glyphicon-trash"></span></a>
                                @endcan
                            @else
                                @can('restore',$user)
                                    <a href="javascript:void(0);" onclick="$('#form_restore').attr('action','{{ route('users.destroy', $user) }}').submit()" class="btn btn-default btn-sm" title="Восстановить пользователя"><span class="glyphicon glyphicon-circle-arrow-up"></span></a>
                                @endcan
                                @can('forceDelete',$user)
                                    <a href="javascript:void(0);" onclick="showconfirm('{{ $user->name }}', {{ $user->id }}, true); return false;" class="btn btn-default btn-sm" title="Окончательно удалить"><span class="glyphicon glyphicon-remove-circle"></span></a>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $users_query->links() }}
    <p><i>Даты и время указаны в формате UTC.<br>
            Красным цветом выделены удаленные пользователи.</i></p>
    <form method="post" id="form_restore" action="">
        @method('delete')
        @csrf
    </form>
    <div id="remove" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Удалить пользователя</h4>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите <span id="permanent"></span> удалить пользователя &quot;<span id="username"></span>&quot;?</p>
                </div>
                <div class="modal-footer">
                    <form id="removelink_form" method="post">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger" id="removelink">Да</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="removedismiss">Нет</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function showconfirm(name, id, force=false) {
            $("#username").text(name);
            if(force) {
                $("#permanent").text('безвозвратно');
                $("#removelink_form").attr('action',`{{ route('users.index') }}/${id}?force={{ csrf_token() }}`);
            } else {
                $("#permanent").text('');
                $("#removelink_form").attr('action',`{{ route('users.index') }}/${id}`);
            }
            $('#remove').modal('show');
        }
    </script>
    @endif
@endsection
