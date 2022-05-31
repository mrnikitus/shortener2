@extends('layout.app')

@section('title','Статистика адреса "'.$address->name.'"')

@section('scripts')
    @if($address->clicks > 0 and count($diagram) > 0 )
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @endif
@endsection

@section('content')
    <ol class="breadcrumb">
        @if (request()->user()->id == $address->user_id)
            <li><a href="{{ route('addresses.index') }}">Мои адреса</a></li>
        @else
            <li><a href="{{ route('addresses.all') }}">Все адреса</a></li>
        @endif
        <li><a href="{{ route('addresses.show', ['address' => $address->id]) }}">{{ $address->name }}</a></li>
        <li class="active">Статистика адреса</li>
    </ol>
    <h2 @if ($address->not_in_use) class="text-danger"@endif>{{ $address->name }}</h2>
    @if (!$address->user)
        <p><strong>Пользователь: </strong> {{ \App\Models\User::withTrashed()->find($address->user_id)->username }} <em>(удален)</em></p>
    @elseif (request()->user()->id != $address->user->id)
        <p><strong>Пользователь: </strong><a href="{{ route('users.show', ['user' => $address->user]) }}">{{ $address->user->name }}</a></p>
    @endif
    <p><strong>URL: </strong><a href="{{ $address->url }}">{{ $address->url }}</a></p>
    <p><strong>Сокращённая ссылка: </strong><a href="{{ route('addresses.slug', ['slug'=>$address->slug]) }}" target="_blank">{{ route('addresses.slug', ['slug'=>$address->slug]) }}</a></p>
    <p><strong>Количество переходов: </strong>{{ $address->clicks }}</p>
    @if($address->clicks == 0)
        <div class="alert alert-warning" role="alert">Статистика недоступна, так как по ссылке еще никто не переходил.</div>
    @else
        @if(count($diagram) != 0)
            <h3>График количества переходов по ссылке по дням</h3>
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('date', 'Дата');
                    data.addColumn('number', 'Число переходов');
                    data.addRows([ @foreach($diagram as $key=>$value) [new Date('{{ $key }}'), {{ $value }}], @endforeach ]);
                    var options = {
                        title: 'График за период c {{ \Carbon\Carbon::parse(array_key_first($diagram))->format('d/m/Y') }} по {{ \Carbon\Carbon::parse(array_key_last($diagram))->format('d/m/Y') }}',
                        hAxis: {title: 'Даты',  titleTextStyle: {color: '#333'}},
                        vAxis: {minValue: 0},
                        animation: {startup: true, duration: 1000, easing: 'out'},
                        legend: {position: 'bottom'},
                        lineWidth: 2,
                        chartArea: {left: 50, right: 50, top: 50, bottom: 50},
                        pointsVisible: true,
                    };
                    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            </script>
            <div id="chart_div" style="width: 100%; height: 350px;"></div>
        @else
            <div class="alert alert-warning" role="alert">
                График недоступен, так как с момента создания адреса прошло меньше суток либо по нему никто не переходил за последний месяц.
            </div>
        @endif
    @endif
    @if($address->clicks > 0)
        <h3>Переходы по датам и времени</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" style="margin-bottom: 5px;">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>IP-адрес</th>
                    <th>ID</th>
                </tr>
                </thead>
                <tbody>
                @foreach($statistic as $day)
                    @foreach($day as $value)
                        <tr>
                            @if($loop->first) <td rowspan="{{ $loop->count }}">{{ $value->date }}</td> @endif
                            <td>{{ $value->time }}</td>
                            <td>{{ $value->ip ? : '–' }}</td>
                            <td>{{ $value->id }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $statistic_query->links() }}
        <p><i>Даты и время указаны в формате UTC</i></p>
    @endif
@endsection
