<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BMIController extends Controller
{
    //

    public function index()
    {
        return view('bmi');
    }

    public function store(Request $request)
    {
        // 個別に取得する
        $height = $request->input('height');
        $weight = $request->input('weight');

        echo "height:" . $height . ", weight:"  .  $weight;
    }
}