<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BMIRequest;

class BMIController extends Controller
{
    //

    public function index()
    {
        return view('bmi');
    }

    public function store(BMIRequest $request)
    {
        // 個別に取得する
        $height = $request->input('height');
        $weight = $request->input('weight');


        $bmi = $weight / pow($height / 100, 2);
        //echo "height:" . $height . ", weight:"  .  $weight . ", bmi:" . $bmi;
        return redirect()->route('bmi')->with('bmi', $bmi);
    }
}
