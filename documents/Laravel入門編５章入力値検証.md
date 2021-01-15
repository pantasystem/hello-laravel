# Laravel入門編 ５章　入力値検証 
前回作成したbmiは入力値の検証をしていないため、  
どんな値でも入力することができ、結果サーバーエラーが発生してしまいました。  
そこで今回は入力値をチェックして問題があればエラーを表示するようにします。

## 入力値チェック
現時点では何も入力値の検証を行っていないので、  
数字以外の文字を入力するとサーバーエラーが発生してしまいます。  
そこで入力値を検証して問題のあるデータならエラーを表示するようにします。

## Requestクラスを作成する
入力値を定義するBMIRequestクラスを作成します。
```
php artisan make:request BMIRequest
```

BMIRequestは```app/Http/Requests/BMIRequest.php```のように作成されます。
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BMIRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
```

authorizeメソッドの戻り値がfalseになっていますが、  
認証関連のチェックはしないので、trueを返します
```php
public function authorize()
{
    return true;
}
```

rules()メソッドに入力値のルールを入力します。  
requiredは必見パラメーターを意味し、  
numericは数値であることを意味しています。  
他にもメールアドレスやアルファベットなどいろいろなパターンがあるので、  
気になったら[ドキュメント](https://readouble.com/laravel/8.x/ja/validation.html#rule-numeric)を見てください。
```php
public function rules()
{
    return [
        'height' => ['required', 'numeric'],
        'weight' => ['required', 'numeric']
    ];
}
```

## BMIRequestを指定する
BMIControllerにApp\Http\Requests\BMIRequestをuseしてあげます。

> BMIController.php
```php
use App\Http\Requests\BMIRequest;
```

storeメソッドのRequestをBMIRequestにします。
> BMIController.php
```php
public function store(BMIRequest $request)
{
    // 個別に取得する
    $height = $request->input('height');
    $weight = $request->input('weight');


    $bmi = $weight / pow($height / 100, 2);
    //echo "height:" . $height . ", weight:"  .  $weight . ", bmi:" . $bmi;
    return redirect()->route('bmi')->with('bmi', $bmi);
}
```

これで入力値検証の適応完了です。

## エラー内容を表示したい。
入力値検証は実装することができたのですが、  
しかしまだエラーの内容を表示することができていません。

## bmi.blade.phpにエラーを表示する

@errorディレクティブを利用することにより、  
エラーとそのメッセージを表示することができます。  
また他にも方法はあるので気になったら[ドキュメント](https://readouble.com/laravel/8.x/ja/validation.html)を見てください。
```html
@extends('layouts.app')

@section('title')
BMIを測定
@endsection
@section('content')
<form method="POST" action="{{ route('bmi.store') }}">
    @csrf
    <div>
        身長:<input type="text" name="height">cm
    </div>
    @error('height')
        <div> {{ $message }}</div>
    @enderror

    <div>
        体重:<input type="text" name="weight">kg
    </div>

    @error('weight')
        <div>{{ $message }}</div>
    @enderror
    
    @if(session('bmi'))
    <div>
        BMIは{{ session('bmi') }}です。
    </div>
    @endif
    <button type="submit">送信</button>
        
</form>
@endsection
```


## 入力状態を維持したい
送信ボタンを押すとPOSTされて、  
BMIが計算され、その中身がsessionに書き込まれます。  
そしてリダイレクトされsessionの中身があればbmi.blade.phpに表示されます。  
現時点では結果表示時には入力状態は初期化されてしまっていますが、  
結果表示時でも身長、体重の入力状態を維持できるようにします。  

## old関数
old関数を利用することにより、以前の入力状態を表示することができます。  
これにより入力内容に不備があった場合リダイレクトで元の画面に戻りますが、  
通常では入力状態は消えてしまいますが、old関数を利用することにより復元することができます。

## 実装
bmi.blade.phpを以下のように改修します。