<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return 'hello, world';
});

Route::get('register', [Controller::class, 'show'])->name('articles.show');
