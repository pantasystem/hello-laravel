# Laravel入門編 ３章 blade入門
今まではControllerやRouteで直接echoやprintを使って表示させていましたが、  
しかしそれは構造的ではないためとても分かりにくいです。  
さらにphpをかけないデザイナーにとってもハードルが高いです。  
しかしbladeはHTMLらしさを失うことなく、PHPの変数などを表示することもできます。  

## bladeファイルを作成する
~~以下のコマンドで作成することができます。~~  
残念ながらbladeを作成するartisanコマンドは標準ではありません。  
```./resources/views/```に手動で
```hello.blade.php```ファイルを作成しましょう。

~~玄人っぽく見せたいので私はtouchコマンドで作成します。~~~
```
touch ./resources/views/hello.blade.php
```

VSCodeの場合はcodeコマンドでも作成できます。
```
code ./resources/views/hello.blade.php
```

## bladeにコードを書く

なんとなくhello.blade.phpにHTMLを書いてみます。
```html
<!DOCTYPE html>
<html>
    <head>
        <title>Hello Blade!!</title>
    </head>
    <body>
        <h1>bladeに入門！！</h1>
    </body>
</html>
```

## hello.blade.phpを返すようにする
web.php routerからhello.blade.phpを返すようにします。  
以前書いた以下のコードを改造します。

```php
Route::get('/hello', function(){
    echo "<h1>Hello Route</h1>";
});
```

このようになります。
```php
Route::get('/hello', function(){
    return view('hello');
});
```

```http://localhost:8000/hello```にアクセスして「bladeに入門！」と表示されれば正解です。

## Controllerに移す
前回せっかくControllerの使い方を覚えたので、  
さっき書いたコードをControllerに移したいと思います。  
> web.php
```php
Route::get('/hello', [HelloController::class, 'hello']);
```

> HelloController
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

    public function hello()
    {
        return view('hello');
    }
}

```

