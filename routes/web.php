<?php

use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
