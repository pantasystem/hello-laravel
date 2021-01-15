<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    //
    public function greet()
    {
        echo "<h1>Hello Controller</h1>";
    }

    public function findBook($bookNo)
    {
        echo "<h1>" . $bookNo . "番の本ですよ！！</h1>";
    }

    public function hello()
    {
        return view('hello');
    }
}
