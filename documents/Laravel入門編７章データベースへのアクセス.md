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
migrationは