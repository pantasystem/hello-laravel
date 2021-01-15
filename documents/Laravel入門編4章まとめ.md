# Laravel入門編4章まとめ
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

Route::post('/bmi', [BMIController::class, 'store'])->name('bmi.store');
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