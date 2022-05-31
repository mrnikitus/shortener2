<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('generate:users', function () {
    $this->info('Данная команда сгенерирует 40 пользователей с разными ролями и запишет их в базу данных');
    if($this->confirm('Вы действительно хотите сгенерировать 40 пользователей в базе данных?')) {
        \App\Models\User::factory()->count(40)->create();
        $this->comment('Пользователи созданы успешно');
    }
});
Artisan::command('generate:addresses', function () {
    $this->info('Данная команда сгенерирует 200 адресов, рандомно принадлежащих пользователям с id от 1 до 40.');
        if($this->confirm('Вы действительно хотите сгенерировать 200 адресов в базе данных?')) {
        \App\Models\Address::factory()->count(200)->create();
        }
    $this->comment('Адреса созданы успешно');
});
Artisan::command('generate:statistics', function () {
    $this->info('Данная команда сгенерирует 50000 посещений адресов, рандомно принадлежащих адресам с id от 1 до 200.');
    if($this->confirm('Вы действительно хотите сгенерировать 50000 посещений в базе данных?')) {
        $this->comment('Начата генерация статистики');
        \App\Models\Statistic::factory()->count(50000)->create();
        $this->comment('Статистика создана успешно');
        $addresses = \App\Models\Address::all();
        foreach ($addresses as $address) {
            $address->clicks = \App\Models\Statistic::where('address_id', $address->id)->count();
            $address->save();
        }
        $this->comment('Адреса обновлены успешно');
    }
});
Artisan::command('superuser', function () {
    $username = $this->ask('Введите имя пользователя (по умолчанию admin)');
    $username = $username ? : 'admin';
    $user = new \App\Models\User;
    $user->username = $username;
    $name = $this->ask('Введите имя (по умолчанию '.$username.')');
    $user->name = $name ? : $username;
    $email = $this->ask('Введите e-mail (необязательно)');
    $user->email = $email;
    password:
    $password = $this->secret('Введите пароль');
    $confirm = $this->secret('Повторите введенный пароль');
    if (isset($password) and isset($confirm) and $password == $confirm) {
        $user->password = \Illuminate\Support\Facades\Hash::make($password);
    } else {
        $this->error('Пароли не совпадают или вы не ввели их');
        goto password;
    }
    $user->role = 2;
    $user->save();
    $this->info('Пользователь создан успешно');
});
