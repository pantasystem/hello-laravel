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

