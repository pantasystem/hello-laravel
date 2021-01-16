# Laravel入門編 ７章 データベースへのアクセス
前回はLaravelのデータベースの全体像を紹介しました。  
今回はメモ帳アプリを開発しながら具体的なデータベースへのアクセス方法を説明します。  

## Migration
Laravelにはテーブルの作成や編集などのスキーマをPHPのコードで管理する、  
Migrationというものが存在します。

## 直接SQLでスキーマを管理してはだめなのか？
もちろんCREATE TABLEやALTER TABLEで直接テーブルを作成したり編集することも可能ですが、  
Gitなどでバージョンを管理することが難しく、メンテナンスも難しいです。  
途中からALTER TABLEで制約などを追加した場合、どの段階で変更が加わったのか、  
変更が適応されているのかを把握するのが困難です。  
LaravelのMigrationはPHPでテーブルの作成や編集をするため、  
バージョン管理もしやすく、またコマンド一つで実行してくれるため構築するのが楽です。  
またPHPがデータベースのスキーマを知ることができるので、テストなどを自動化するときに、  
自動的にデータベースを初期化したりすることが容易にできるので便利です。  

## メモ帳アプリの仕様
コードを書く前にまず、メモ帳アプリの仕様を決めておきます。  
メモ帳アプリは、タイトルと本文を投稿できることとします。  
タイトルの最大文字数は20文字で、入力は必須とします。  
本文の最大文字数は200文字で、入力は必至とします。  
今回はユーザーの識別はなしで実装します。

## データベースの定義
> テーブル名:notes  

|列名|型|主キー|NOT NULL|外部キー|説明|
|-|-|-|-|-|-|
|id|符号なしbigInt|YES|YES||主キーautoincrementします。|
|title|varchar(20)|NO|YES||タイトル|
|text|varchar(200)|NO|YES||本文|
|created_at|date|No|No||作成日時|
|updated_at|date|No|No||更新日時|

## ルートの仕様
|path|メソッド|名前|コントローラー@メソッド|説明|
|-|-|-|-|-|
|/notes|get|notes|NoteController@index|投稿一覧|
|/notes/{noteId}|notes.show|get|NoteController@show|投稿の詳細画面|
|/notes/new|get|notes.new|NoteController@new|投稿作成画面|
|/notes/create|post|notes.create|NoteController@store|投稿POST先|

## Noteモデルの作成
ここでMVCモデルのおさらいをします。  
MVCモデルは、プログラムの処理を大まかに３つに分けて開発を行う考え方の一つです。  
その３つはModel, View, Controllerに分けられます。  
* データベースや計算ロジックなどを実装するModel
* 表示を担当するView
* ViewとModelを仲介するController  

今までView(blade)、Controllerを触ってきました。  
今回はMVCの内のMであるModelを触ります。  

まずは、notesテーブルに対応するNoteモデルを作成します。  
**-m オプションは一緒にMigrationを作成してくれます。**
```
php artisan make:model Note -m
```

## Migrationの作成
migrationは```php artisan make:migration```コマンドで作成します。  
今回はModelの作成と同時にMigrationを作成したため、  
さらにMigrationを作る必要はありません。  
Migrationは命名などにルールがあるので後で[ドキュメント](https://readouble.com/laravel/8.x/ja/migrations.html)を読んでおくことをお勧めします

## Migrationの作成場所  
Migrationは./database/migrationsに作成されます。  
その中に先ほど作成した```年_月_日_時間_create_notes_table.php``` migrationファイルがあるのでエディターで開きます。  
(※年月日時間は作成時間によって変わります)

> 2021_01_16_052702_create_notes_table.php

開いてみると以下のような状態になっていると思います。  
CreateNotesTableクラスには  
upメソッドとdownメソッドがあることがわかります。  
|メソッド名|役割|
|-|-|
|up|migrationを実行するときに実行される。|
|down|migrationを取り消したいときに実行される。|


```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}

```

## id
主キーのことで型はunsigned bigInteger型の主キー制約でAUTOINCREMENTが設定されます。

## timestamp
date型のupdated_atとcreated_atが作成され、  
作成時、更新時に自動的に時間が記録されます。

## 列を実装する
列を作成する基本的な構文は以下のようになります。
```php
$table->対応する型のメソッド('列名');
```

varchar(20)のtitleを作成したいのでドキュメントを見たところ、  
varcharに対応するのはstringメソッドのようです。  
引数に設定したい列名を渡してあげます。
```php
$table->string('title', 20);
```

引き続きvarchar(200)の本文(text)を作成します。
```php
$table->string('text', 200);
```

他にもいろいろなデータ型の列を宣言することができます。  
[カラムの作成](https://readouble.com/laravel/8.x/ja/migrations.html#creating-columns)


ここまでのコード
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 20);
            $table->string('text', 200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
```
