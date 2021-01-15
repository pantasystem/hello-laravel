# Laravelの全体像
Laravelのコードを書き始める前に、  
まずはLaravelの全体像をなんとなく知った方が、  
今自分がどこにいるのかがわかりやすくなり、  
どこにファイルがあるのかわからないといった困惑から脱することができます。

## 環境構築
Laravelを触る前にまずPHPなどの環境を整える必要があります。  
今回はDockerを使用しますが、
前半はデータベースなどを使用しないので、  
入れなくても大丈夫です。

## PHPを導入
WindowsならPowershellやコマンドプロンプトを起動して、  
黒い画面にphp -vと入力します。  
以下のように表示されれば大丈夫です。
```
C:\Users\otsuk\Laravel>php -v
PHP 7.4.4 (cli) (built: Mar 17 2020 13:49:19) ( ZTS Visual C++ 2017 x64 )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
```

表示されなかった場合はXAMPPなどを導入してください。  


## Composerを導入
### Composerとは  
プログラミングはもちろん０から１００をすべて自分一人ですることも可能ですが、  
それはあまりにも途方なくとても労力のいる作業です。  
そこで他の人から提供されているソフトウェア(ソースコード群)ライブラリを利用することにより、  
面倒な作業を省きアプリケーションの本来重要な機能に集中することができます。  
Composerはこのライブラリをコマンド一つでダウンロードし、しかもライブラリが依存しているライブラリ  
の関係も管理してくれるのでとても楽です。

コマンドプロンプトにcomposer -Vと入力します。  
以下のようにComposer version xxxxxのように表示されればOKです。
```
C:\Users\otsuk\Laravel>composer -V
Composer version 1.10.10 2020-08-03 11:35:19
```

表示されなかった人はダウンロード＆インストールしてください。  
[Composer](https://getcomposer.org/)

## プロジェクトを作成する
コマンドラインでプロジェクトを作成したいディレクトリを開いてください。  
そしてコマンドラインで以下のように入力します。  
すると必要なファイルなどが自動でダウンロードされると思います。
```
composer create-project laravel/laravel --prefer-dist プロジェクト名
```

入力してみます。
しばらくするといろいろファイルがダウンロードされていきます。
```
C:\Users\otsuk\Laravel>composer create-project laravel/laravel --prefer-dist hello-laravel
Creating a "laravel/laravel" project at "./hello-laravel"
```

プロジェクトの作成が完了したら、  
それをVSCodeなどのエディターで開いてみましょう。  
VSCodeの場合codeコマンドを使用するとディレクトリごと開くことができるので便利です。
```
code 開きたいディレクトリ名
```

## ファイルを眺めてみる
プロジェクトディレクトリを見てみましょう  
たくさんのファイルやディレクトリが作成されました。  
目を回してしまいそうになりますが、  
しばらくすれば、これはあなたにとって大変心強い武器になってくれます。
```
PS C:\Users\otsuk\Laravel\hello-laravel> dir


    ディレクトリ: C:\Users\otsuk\Laravel\hello-laravel


Mode                LastWriteTime         Length Name
----                -------------         ------ ----
d-----       2021/01/15     18:48                app
d-----       2021/01/15     18:48                bootstrap
d-----       2021/01/15     18:48                config
d-----       2021/01/15     18:48                database
d-----       2021/01/15     18:48                public
d-----       2021/01/15     18:48                resources
d-----       2021/01/15     18:48                routes
d-----       2021/01/15     18:48                storage
d-----       2021/01/15     18:48                tests
d-----       2021/01/15     18:51                vendor
-a----       2021/01/15     18:48            220 .editorconfig
-a----       2021/01/15     18:51            862 .env
-a----       2021/01/15     18:48            811 .env.example
-a----       2021/01/15     18:48            111 .gitattributes
-a----       2021/01/15     18:48            191 .gitignore
-a----       2021/01/15     18:48            181 .styleci.yml
-a----       2021/01/15     18:48           1686 artisan
-a----       2021/01/15     18:48           1646 composer.json
-a----       2021/01/15     18:51         248604 composer.lock
-a----       2021/01/15     18:48           1853 docker-compose.yml
-a----       2021/01/15     18:48            473 package.json
-a----       2021/01/15     18:48           1202 phpunit.xml
-a----       2021/01/15     18:48           3780 README.md
-a----       2021/01/15     18:48            563 server.php
-a----       2021/01/15     18:48            559 webpack.mix.js
```

早速各種ファイルとディレクトリを説明したいところですが、  
いきなりControllerだとかMiddlewareだとかMigrationだとか言われても訳が分からないと思います。  
そこでまずはControllerやrouteなどのLaravelにおいて重要な概念を説明します。

## これまでのPHP
LaravelやCakePHPなどのフレームワークを一切使用することなく、  
PHPのみでしかも何も考えないで実装した場合以下のようなケースに心当たりがあるかもしれません。  
<b>入力値チェック、データベースの処理、計算処理、表示処理が一つの.phpファイルに同居している</b>  
これでは入力値のチェックをしているのか計算処理をしているのか表示処理をしているのか、  
自分が今何をしているのかわからなくなってしまいます。  
そこでコードをプログラムの処理を役割毎に分けて開発を行う考え方生まれました。

## MVCモデル
急に難しいワードが出てきたな、逃げようと思うかもしれませんが、恐れる必要はありません。  
MVCモデルとは、プログラムの処理を役割毎に分けて開発を行う考え方の一つです。  
MはModel、VはView、CはControllerの略称でそれぞれ以下のような意味があります。
* Modelはデータベースへのアクセスや計算処理などのロジック
* Viewは表示処理
* ControllerはModelとViewの繋ぎ  

LaravelはこのMVCモデルを採用しています。
<img src="what-mvc.png" width="600">

## LaravelとMVCとディレクトリ
MVCはコードをModel、View、Controllerの３つに機能別で分離する方法だと説明しました。  
LaravelはこのMVCの考え方を取り込んでいます。  
このMVCとディレクトリ、ファイルの対応状態についての説明をします。  
プロジェクトディレクトリを見てみましょう。  

### Controller
``` ./app/Http/Controllers ```ディレクトリを開いてみましょう。  
ここにModelとViewの繋ぎとなる<b>Controller</b>のファイルが入ります。  

### Model
```./app/Http/```ディレクトリを開いてみましょう。  
ここにModelのファイルが入ります。

### View
```./resources/views```ディレクトリを開いてみましょう。  
ここにbladeと呼ばれるテンプレートエンジンが入ります。  
テンプレートエンジンと呼ばれて何かわからないかもしれませんが、  
HTMLらしさを捨てることなくコーディングすることができます。

### route 
これはアクセスされたURLのパスと実行したい関数や、  
Controllerのメソッドなどへ処理をマッピングすることができます。  
以下のようにコードを書き
```php
Route::get('/hello', function(){
    echo "<h1>Hello ruote</h1>";
});
```

以下のURLでブラウザでアクセスすると  
画面には「Hello ruote」と表示されます。
```
※localhostの場合
http://localhost/hoge
```

## 開発サーバーを起動
nginxやapacheを使って環境構築をするのもいいのですが、  
開発環境のために本番環境のような環境を構築するのはやや面倒です。  
しかしLaravelでは一つのコマンドで開発用サーバーを起動することができます。  
プロジェクトディレクトリを開いたコマンドラインで以下のように入力してみましょう。
```
php artisan serve
```

うまくいけばこのようになります。
```
PS C:\Users\otsuk\Laravel\hello-laravel> php artisan serve
Starting Laravel development server: http://127.0.0.1:8000
[Fri Jan 15 20:10:38 2021] PHP 7.4.4 Development Server (http://127.0.0.1:8000) started
```

```http://127.0.0.1:8000```テストサーバーのURLが表示されているのでブラウザでアクセスしてみましょう。  
やった！！やりました！！！LaravelのWelcomeページが表示されました。  

<img src="./images/start-server.png" width="600">

## Laravelとコマンド
Laravelの開発用サーバーを```php artisan serve```コマンドで起動しました。  
ところでこのartisanとは何でしょう？  
artisan(アルチザン)コマンドと呼ばれるLaravelの非常に便利なコマンドで、  
必要なファイルの作成やデータベースの設定などいろいろなことをしてくれます。  

「覚える必要はありますか？」  
その必要はありません。  
何故ならよく使うコマンドは自然と覚えていきますしGoogle先生やドキュメントを見ればすぐに答えは得られます。  

## まとめ
今回は以下のことについてできていれば十分かと思います。  
* Composerって便利なんだー
* プロジェクト作成できた！！
* MVCモデルについての理解
* MVCモデルとLaravelの関係
* 開発用サーバーの起動
