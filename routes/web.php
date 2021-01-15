<?php

use Illuminate\Support\Facades\Route;

// 忘れないで！！
use App\Http\Controllers\HelloController;
use App\Http\Controllers\BMIController;

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

Route::get('/hello', [HelloController::class, 'hello']);

Route::get('/books/{bookNo}', function($bookNo){
    echo "<h1>" . $bookNo . "番の本ですよ！！</h1>";
});

Route::get('/greet', [HelloController::class, 'greet']);

Route::get('/judge/{number}', function($number){
    return view('judge', ['number' => $number]);
})->where('number', '[0-9]+');

Route::get('/bmi', [BMIController::class, 'index'])->name('bmi');

Route::post('/bmi/send', [BMIController::class, 'store'])->name('bmi.store');
