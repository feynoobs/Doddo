<?php

use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/{any?}', fn() => view('app'))->where('any', '.+');