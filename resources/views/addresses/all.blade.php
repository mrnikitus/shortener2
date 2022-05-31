@extends('layout.app')

@section('title', 'Все адреса')

@section('content')
    <h1>Все адреса</h1>
    @if (count($urls) === 0)
        <div class="alert alert-warning" role="alert">Пусто :(</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-responsive" style="margin-bottom: 5px;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>URL</th>
                    <th>Сокращение</th>
                    <th>Число переходов</th>
                    <th>Дата добавления</th>
                    <th>Пользователь</th>
                    <th style="min-width: 120px;">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($urls as $url)
                    <tr @if($url->user_deleted_at) style="background-color: #cbcbcb" @elseif($url->trashed()) class="danger" @elseif($url->not_in_use) class="warning" @endif>
                        <td>{{ $url->id }}</td>
                        <td>
                            @if(!$url->trashed())
                                <a href="{{ route('addresses.show', ['address' => $url]) }}" title="Информация о ссылке"><strong>{{ $url->name }}</strong></a>
                            @else
                                <strong>{{ $url->name }}</strong>
                            @endif
                        </td>
                        <td><a target="_blank" href="{{ $url->url }}" title="Открыть ссылку в новой вкладке">{{ $url->url }} <span class="glyphicon glyphicon-new-window"></span></a></td>
                        <td><a href="#" onclick="copy('{{ $url->slug }}')" title="Скопировать сокращенную ссылку">{{ $url->slug }} <span class="glyphicon glyphicon-duplicate"></span></a></td>
                        <td>{{ $url->clicks }}</td>
                        <td>{{ $url->created_at->format('d/m/Y H:i:s') }} UTC</td>
                        @php $user = \App\Models\User::hydrate([['id' => $url->user_id, 'role' => $url->user_role, 'deleted_at' => $url->user_deleted_at]])->first() @endphp
                        <td>@if (request()->user()->can('update', $user) and !$user->trashed())<a href="{{ route('users.show', $url->user_id) }}">{{ $url->user_name }}</a> @else {{ $url->user_name  }} @endif</td>
                        <td style="/* width: 120px; */">
                            <div class="btn-group" role="group">
                                @if(!$url->trashed())
                                    <a href="{{ route('addresses.in_use', ['address' => $url ]) }}/" class="btn btn-default btn-sm" title="Сделать ссылку активной/неактивной"><span class="glyphicon glyphicon-asterisk"></span></a>
                                    <a href="{{ route('addresses.statistic', ['address' => $url]) }}" class="btn btn-default btn-sm" title="Посмотреть статистику ссылки"><span class="glyphicon glyphicon-stats"></span></a>
                                    @can('delete',$url)
                                        <a href="javascript:void(0);" onclick="showconfirm('{{ $url->name }}', {{ $url->id }})" class="btn btn-default btn-sm" title="Удалить ссылку"><span class="glyphicon glyphicon-trash"></span></a>
                                    @endcan
                                @else
                                    @can('restore',$url)
                                        <a href="javascript:void(0);" onclick="$('#form_restore').attr('action','{{ route('addresses.destroy', $url) }}').submit()" class="btn btn-default btn-sm" title="Восстановить адрес"><span class="glyphicon glyphicon-circle-arrow-up"></span></a>
                                    @endcan
                                    @can('forceDelete',$url)
                                        <a href="javascript:void(0);" onclick="showconfirm('{{ $url->name }}', {{ $url->id }}, true); return false;" class="btn btn-default btn-sm" title="Окончательно удалить"><span class="glyphicon glyphicon-remove-circle"></span></a>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $urls_query->links() }}
        <p><i>Даты и время указаны в формате UTC.<br>
                Жёлтым цветом выделены неактивные ссылки.<br>
                Красным цветом фона выделены удаленные ссылки.<br>
                Серым цветом выделены ссылки удаленных аккаунтов.</i></p>
        <form method="post" id="form_restore">
            @method('delete')
            @csrf
        </form>
        <div id="remove" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Удалить ссылку</h4>
                    </div>
                    <div class="modal-body">
                        <p>Вы действительно хотите <span id="permanent"></span> удалить ссылку &quot;<span id="linkname"></span>&quot;?</p>
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
            function copy(text) {
                navigator.clipboard.writeText('{{route('root')}}/'+text);
            }
            function showconfirm(name, id, force=false) {
                $("#linkname").text(name);
                if(force) {
                    $("#permanent").text('безвозвратно');
                    $("#removelink_form").attr('action',`{{ route('addresses.index') }}/${id}?force={{ csrf_token() }}`);
                } else {
                    $("#permanent").text('');
                    $("#removelink_form").attr('action',`{{ route('addresses.index') }}/${id}`);
                }
                $('#remove').modal('show');
            }
        </script>
    @endif
@endsection
