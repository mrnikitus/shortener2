<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Statistic;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Address::class, 'address');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls_query = DB::table('addresses')->where('user_id','=',request()->user()->id)->whereNull('deleted_at')->
            orderBy('addresses.id','desc')->paginate(30);
        $urls = Address::hydrate($urls_query->items());
        return response()->view('addresses.index', ['urls' => $urls, 'urls_query' => $urls_query]);
    }

    public function addresses_all() {
        $this->authorize('viewAll', Address::class);
        $urls_query = DB::table('addresses')->leftJoin('users','users.id','=','addresses.user_id')->
            select('addresses.*','users.name as user_name', 'users.role as user_role', 'users.id as user_id', 'users.deleted_at as user_deleted_at')->orderBy('addresses.id','desc')->paginate(30);
        $urls = Address::hydrate($urls_query->items());
        return response()->view('addresses.all', ['urls' => $urls, 'urls_query' => $urls_query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('addresses.create');
    }

    /**
     * Генерация короткого адреса
     *
     * @param int $length
     * @return string
     */
    private function generate_slug(int $length = 6) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFHIJKLMNOPQRSTUVWXYZ1234567890';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAddressRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAddressRequest $request)
    {
        $validated = $request->validated();
        $address = new Address;
        $address->url = $validated['url'];
        $address->name = $validated['name'];
        $address->user_id = $request->user()->id;
        if (!array_key_exists('slug',$validated) or !$validated['slug']) {
            $gen_slug = $this->generate_slug();
            while (Address::where('slug',$gen_slug)->count()!=0) {
                $gen_slug = $this->generate_slug();
            }
            $address->slug = $gen_slug;
        } else {
            $address->slug = $validated['slug'];
        }
        $address->save();
        return redirect()->back()->with('success','Адрес "'.$address->name.'" был успешно добавлен.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        return response()->view('addresses.show', ['address' => $address]);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  \App\Models\Address  $address
//     * @return \Illuminate\Http\Response
//     */
//    public function edit(Address $address)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAddressRequest  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $validated = $request->validated();
        $address->name = $validated['name'];
        if($validated['in_use']) {
            $address->not_in_use = false;
        } else {
            $address->not_in_use = true;
        }
        $address->save();
        $request->session()->flash('success','Адрес успешно изменен.');
        return response()->view('addresses.show', ['address'=>$address]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Address $address)
    {
        if (request()->query('force') == request()->session()->token()) {
            $this->authorize('forceDelete', $address);
            $address->forceDelete();
            return redirect()->route('addresses.all')->with('warning','Адрес "'.$address->name.'" безвозвратно удален.');
        }
        if ($address->trashed()) {
            $this->authorize('restore', $address);
            $address->restore();
            return redirect()->route('addresses.all')->with('success','Адрес "'.$address->name.'" успешно восстановлен.');
        }
        else {
            $this->authorize('delete', $address);
            $address->deleteOrFail();
            return redirect()->route(request()->user()->can('viewAll', Address::class) ? 'addresses.all' : 'addresses.index')->with('warning','Адрес "'.$address->name.'" успешно удален.');
        }
    }

    /**
     * Статистика адреса
     *
     * @param Address $address
     * @return \Illuminate\Http\Response
     */
    public function statistic(Address $address) {
        // Авторизация пользователя
        $this->authorize('statistic', $address);
        // Получения выборки для статистики (с пагинацией) и для диаграммы за последний месяц
        $statistic_query = DB::table('statistics')->selectRaw('*, DATE_FORMAT(created_at,"%d/%m/%Y") as date, DATE_FORMAT(created_at,"%H:%i:%s") as time')
            ->where('address_id', '=', $address->id)->orderByDesc('created_at')->paginate(30);
        $statistic = $statistic_query->groupBy('date');
        $diagram_query = DB::table('statistics')->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, Count(*) as count')
            ->where('address_id','=',$address->id)->where('created_at', '>=', Carbon::today()->subMonth())
            ->where('created_at', '<=', Carbon::tomorrow()->subSecond())->groupBy('date')->orderBy('date')->get();
        if(count($diagram_query) > 0) {
            // Если для диаграммы выборка не равна нулю, создается период, начало которого - месяц назад, конец - сегодняшний день, а интервал - 1 день
            $period = new DatePeriod(Carbon::today()->subMonth(), new DateInterval('P1D'), Carbon::tomorrow());
            // Создается массив diagram с пока что нулевым числом кликов
            foreach ($period as $day) {
                $diagram[$day->format('Y-m-d')] = 0;
            }
            // Добавляется последний день, так как цикл не добавляет его
//            $last_day = new DateTime($diagram_query[count($diagram_query)-1]->date);
//            $diagram[$last_day->format('Y-m-d')] = 0;
//            $diagram[Carbon::today()->format('Y-m-d')] = 0;
            // Все записи из выборки для диаграммы заносятся в массив
            foreach ($diagram_query as $day) {
                if(isset($diagram[$day->date])) $diagram[$day->date] = $day->count;
            }
        } else $diagram = array();
        return response()->view('addresses.statistic', ['address' => $address, 'diagram' => $diagram, 'statistic' => $statistic, 'statistic_query' => $statistic_query]);
    }

    /**
     * Переключение адреса "в использовании/не в использовании"
     *
     * @param Address $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function in_use(Address $address) {
        $this->authorize('update',$address);
        if ($address->not_in_use) {
            $address->not_in_use = false;
            $flag = false;
            $address->save();
        } else {
            $address->not_in_use = true;
            $flag = true;
            $address->save();
        }
        $msg = ($flag) ? 'неактивным' : 'активным';
        return redirect()->back()->with('success','Адрес "'.$address->name.'" помечен '.$msg.'.');
    }

    /**
     * Перенаправление
     *
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(string $slug) {
        // Проверка на отсутствие недопустимых символов (для избежания SQL-инъекций)
        if (preg_match('/^[\pL\pM\pN_-]+$/u', $slug) == 0) abort(404);
        // Получение адреса по сокращению из базы данных при условии, что пользователь, загрузивший ссылку, не удален
        $address = DB::table('addresses')->join('users','users.id','=','addresses.user_id')
            ->select('addresses.*')->where('slug','=',$slug)->where('not_in_use', '=', 0)->whereNull('users.deleted_at')->first();
        if(!$address) abort(404);
        // Запись информации об адресе в таблицу
        $statistic = new Statistic(['address_id' => $address->id, 'ip' => request()->ip()]);
        $statistic->save();
        // Обновление числа переходов в таблице адресов
        DB::update('update addresses set addresses.clicks = (select count(*) from statistics where statistics.address_id = ?) where addresses.id = ?', [$address->id, $address->id]);
        return redirect($address->url);
    }
}
