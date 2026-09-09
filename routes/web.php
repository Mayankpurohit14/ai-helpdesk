<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::view('/', 'pages.home');
Route::view('/login', 'pages.login');
Route::view('/register', 'pages.register');
Route::view('/dashboard', 'pages.dashboard');
Route::view('/tickets/create', 'pages.create-ticket');
Route::view('/tickets/{id}', 'pages.ticket-detail');
