<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function(){
    echo "<h1>Hello Route</h1>";
});

Route::get('/books/{bookNo}', function($bookNo){
    echo "<h1>" . $bookNo . "番の本ですよ！！</h1>";
});