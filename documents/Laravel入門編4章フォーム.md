# Laravel入門編4章フォーム
ここまでLaravelとMVCの関係や、  
routeやController、bladeなどの学習をしてきました。  
諸事情により説明できなかった部分もあったため、  
BMI計測機能を作りながら説明できなかったPOSTリクエストやフォームについて説明します。

## BMI計測機能
フォームに身長体重を入力して送信ボタンを押すと、  
BMIが表示されます。(~~JavaScriptだけでできるじゃん~~)

## Controllerを作成する
BMIControllerという名前のControllerを作成したいと思います。
```
php artisan make:controller BMIController
```


## routeを実装する
BMIControllerをuseするのを忘れないようにしてください。 
今回はあまり恩恵は少ないのですが、名前付きルートを使用しました。 
> web.php
```php
// 忘れないように！！
use App\Http\Controllers\BMIController;

// name()は名前付きルート
Route::get('/bmi', [BMIController::class, 'index'])->name('bmi');

Route::post('/bmi/send', [BMIController::class, 'store'])->name('bmi.store');
```


## 名前付きルート
名前付きルートはroute関数などで、  
そのルートの名前で呼び出します。  
するとそのルートのパスが帰ってきます。  
複雑なパスや、パスの変更が多いときにとても大きな恩恵を受けます。

> 名前付きルートの例
```php
Route::get('/books/{bookId}/reviews/{reviewId}', [ReviewController::class, 'show'])->name('reviews.show');

// bladeなどで
<a href="{{ route('reviews.show', ['bookId' => 3, 'reviewId' => 4])}}">レビュー詳細</a>

// このように展開されます
<a href="/books/3/reviews/4">レビュー詳細</a>
```


## bladeを追加する
bmi.blade.phpという名前で作成します。
```
touch ./resources/views/bmi.blade.php
```

POSTリクエストが送信できることを確認したいのでフォームを作成します。
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
    <div>
        体重:<input type="text" name="weight">kg
    </div>
    <button type="submit">送信</button>
        
</form>
@endsection

```

## @csrfって何？
CSRFとはWebアプリケーションの脆弱性を利用した攻撃手法の一種です。  
この@csrfはCSRF攻撃を予防するためのものです。  
@csrfはhtmlでは以下のように展開され、    
サーバーであらかじめ格納しておいた、  
csrfトークンと送信されたcsrfトークンを比較することによって、  
正規のリクエストであるかをチェックしています。
このため@csrfを忘れるとエラーが返ってきます。  
```
<input type="hidden" name="_token" value="csrfトークン">
```


## BMIControllerからbmi.blade.phpを返す
このままだとbmi.blade.phpを表示することができないので、  
routeで実装したようにBMIControllerにindexメソッドを追加して、  
そこからbmi.blade.phpを返すようにします。
> routes/web.php
```php
Route::get('/bmi', [BMIController::class, 'index'])->name('bmi');
```

> BMIController::class
```php
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
}
```

```/bmi```にアクセスすると以下のような画面が表示されると思います。  
<img src="images/bmi-form.png">

早速送信を押してみましょう。  
エラーが表示されて今いました。  
どうやらBMIControllerにstoreメソッドは存在しないぞ、と言われているようです。
```
BadMethodCallException
Method App\Http\Controllers\BMIController::store does not exist.
```
BMIControllerにPOST先のメソッドを実装していなかったので当然といえば当然ですね。

## POST先を実装する
web.phpを見たところPOST先はBMIControllerのstoreメソッドのようです。
```php
Route::post('/bmi', [BMIController::class, 'store'])->name('bmi.store');
```

本当にPOSTされたのかわかりにくいので、「POSTされた」と表示することにします。
```php
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

    public function store()
    {
        echo "POSTされた";
    }
}
```

再度送信ボタンを押してみます。  
うまくいけば「POSTされた」と表示されます。

## パラメーターを受け取る
storeメソッドの引数にRequest型の$request引数を追加してください。
```
public function store(Request $request)
{
    echo "POSTされた";
}
```

パラメーターは以下のようにして取得することができます。
```php
public function store(Request $request)
{
    // 個別に取得する
    $height = $request->input('height');
    $weight = $request->input('weight');

    // まとめて取得する(配列として取得される)
    $heightAndWeight = $request->only('height', 'weight');

    // すべて取得する(配列として取得される)
    $all = $request->all();
    echo "POSTされた";
}
```

いろいろな方法がありますが、今回は、inputで取得します。  
```php
public function store(Request $request)
{
    // 個別に取得する
    $height = $request->input('height');
    $weight = $request->input('weight');

    echo "height:" . $height . ", weight:"  .  $weight;
}

適当にフォームに入力して送信すると入力した値が画面に表示されると思います。
```

## 計算処理を実装する
本来はModelに実装すべきですが、本題はそこではないのでControllerに実装します。
```php
public function store(Request $request)
{
    // 個別に取得する
    $height = $request->input('height');
    $weight = $request->input('weight');


    $bmi = $weight / pow($height / 100, 2);
    echo "height:" . $height . ", weight:"  .  $weight . ", bmi:" . $bmi;
}
```

送信ボタンを押すとBMIが表示されると思います。

## bladeでBMIを表示する
POSTを受けた後  
/bmiへリダイレクトしてbmi.blade.phpでBMIを表示したいです。  
そのためにはまず、リダイレクトをします。
```php
public function store(Request $request)
{
    // 個別に取得する
    $height = $request->input('height');
    $weight = $request->input('weight');


    $bmi = $weight / pow($height / 100, 2);
    //echo "height:" . $height . ", weight:"  .  $weight . ", bmi:" . $bmi;

    // 名前付きルート「bmi」へリダイレクト
    return redirect()->route('bmi');
}
```

再度/bmiで送信ボタンを押すと今度は/bmi画面に戻ってくると思います。  

## リダイレクト先にデータを渡したい
これはエラーや成功メッセージなどにも使われます。  
最後に以下のようにwithを追加してください。  
これはフラッシュと呼ばれるもので、セッションの仕組みを利用しています。
```php
return redirect()->route('bmi')->with('bmi', $bmi);
```

## BMIを表示する。
bmi.blade.phpに処理を以下のように追加します。

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
    <div>
        体重:<input type="text" name="weight">kg
    </div>
    @if(session('bmi'))
    <div>
        BMIは{{ session('bmi') }}です。
    </div>
    @endif
    <button type="submit">送信</button>
        
</form>
@endsection
```

送信すると今度はBMIがbmi.blade.phpで表示されるようになりました。  

<img src="./images/bmi-session.png">

仕組みとしては、**フラッシュはセッションの仕組みを利用**しています。  
まずif文でセッションにbmiがあるかをチェックします。  
存在していればsessionからbmiを取り出して表示しています。
```html
@if(session('bmi'))
<div>
    BMIは{{ session('bmi') }}です。
</div>
@endif
```


## まとめ
今回はPOSTとLaravelのフォームについて解説しました。  
途中リダイレクトやセッションなどが出てきてややこしかったかもしれませんが、  
POSTとその値の取得の方法を理解できていれば及第点です。  
次回は入力値の検証「バリデーションについて解説します」