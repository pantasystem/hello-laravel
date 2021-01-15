# Laravel入門編 2章 Controller入門
前回Routeを触りました。  
今回はMVCモデルのControllerを触ります。

## Routeだけじゃダメなのか？
Routeに書いていくことももちろん可能です。  
しかしコード量が多くなると見通しが悪くなります。  
例えば一つのルートに付き20行それが10個あったとします。  
単純に計算して20 * 10 = 200行です。  
まだ少ない方なのでいいですが、これがさらに増えると見通しが悪くなりますし、  
どんなPathがあったかな？と思ったときに探すのが困難になります。  

## Controllerとは
ControllerとはMVCモデルで言うCのControllerで、  
Model(データベース読み書きのロジックや計算ロジック)とView(表示)をつなぐ役割があります。
## Controllerを作成する。
コマンドラインでプロジェクトディレクトリを開いてください。  
そして以下のようなコマンドを実行します。  
```
php artisan make:controller HelloController
```

うまくいけば「Controller created successfully.」といったメッセージとともに、  
```app/Http/Controllers/HelloController.php```が作成されます。
```
PS C:\Users\otsuk\Laravel\hello-laravel> php artisan make:controller HelloController
Controller created successfully.
```

HelloController.phpを開いてみます。  
どうやらHelloControllerというクラスが作成されたようです。  
このHelloControllerの中にメソッドという形で実装していきます。
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    //
}

```

## Controllerの実装
Controllerを作成したのですから、早速書いていきます。  
ブラウザにアクセス・・と言いたいところですが、まだです。  
**RouteからPathとControllerのメソッドをマッピングする必要があります。**
```php
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
}

```

## Routeの実装
```./routes/web.php```を開きます。

上のほうに以下のようなコードを書きます。  
これはJavaでいうところのimportのようなものです。
```php
use App\Http\Controllers\HelloController;
```

そして前回の調子でRouteを書くのですが少しだけ違います。  
前回は直接Route::getの第二引数にfunction()と書き始めていたのですが、
今回は第二引数に配列を渡しています。
```php
Route::get('/greet', [HelloController::class, 'greet']);
```

基本的なき方としては
```php
Route::メソッド(パス, [コントローラーのクラス::class, '処理先のメソッド名']);
```

またRoute::getのところをpostやputやdeleteなどに書き換えるだけで、  
様々なアクションに対応することができます。

## パラメターを受け取る
前回web.phpに作成した以下のコードをHelloControllerに書き換えたいと思います。  
```php
Route::get('/books/{bookNo}', function($bookNo){
    echo "<h1>" . $bookNo . "番の本ですよ！！</h1>";
});
```

web.phpは以下のようになります
```php
Route::get('/books/{bookNo}', [HelloController::class, 'findBook']);
```

HelloController.phpは以下のようになります。  
bookNoは引数として受け取ることができます。
```php
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
}
?>
```

## まとめ
RouteとControllerの関係について理解することができれいれば十分だと思います。  
次回はbladeを用いて効率的に画面を作成していきます。