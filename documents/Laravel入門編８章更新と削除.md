# Laravel入門編８章更新と削除
前回はマイグレーションと基本的な読み込みと書き込みについて説明ました。  
今回は前回作成したメモアプリを使いながら、データの更新と削除を説明します。

## GET, POST, PUT, DELETE
HTMLのForm属性を使用するとき、  
methodにGET,POSTを指定していると思います。  
HTMLのFormはなぜかサポートしていませんが、  
実はHTTPにはGET,POST以外にPUT, DELETE,他にもいろいろ存在します。

## それぞれの役割

簡単に分類すると以下の表のような感じになります。  
※あくまでもSQLとは関係はありませんが、わかりやすさのために比較しています。  

|method|役割|SQLで例えると|
|-|-|-|-|
|GET|リソースの取得|SELECT|
|POST|リソースの作成|INSERT|
|PUT|リソースの更新|UPDATE|
|DELETE|リソースの削除|DELETE|

あくまでも設計の話ですが、  
更新時にPUT、削除時にDELETEメソッドが使われたりします。  
もちろん更新時にPOST、削除時にPOSTを使うこともあります。  
あくまでも設計の問題なのでその時その時のケースに合わせて使ってほしいです。  

## ルートの仕様
更新と削除のルートを追加したルートの仕様です。  
先ほど説明したPUT, DELETEを使用しています。
|path|メソッド|名前|コントローラー@メソッド|説明|
|-|-|-|-|-|
|/notes|get|notes|NoteController@index|投稿一覧|
|/notes/{noteId}|get|get|NoteController@show|投稿の詳細画面|
|/notes/new|get|notes.new|NoteController@new|投稿作成画面|
|/notes|post|notes.create|NoteController@store|投稿POST先|
|/notes/{noteId}/update|get|notes.edit|NoteController@edit|編集画面|
|/notes/{noteId}|put|notes.update|NoteController@update|メモを更新する|
|/notes/{noteId}|delete|notes.delete|NoteController@delete|メモを削除する|
## ルートを作成する
前回から引き続きweb.phpルートに更新と削除のための仕様変更分の実装をします。  
```php
Route::get('/notes', [NoteController::class, 'index'])->name('notes');
Route::get('/notes/new', [NoteController::class, 'new'])->name('notes.new');
Route::get('/notes/{noteId}', [NoteController::class, 'show'])->name('get')->where(['noteId' => '[0-9]+']);
Route::post('/notes', [NoteController::class, 'store'])->name('notes.create');

// 更新と削除
Route::put('/notes/{noteId}', [NoteController::class, 'update'])->name('notes.update')->where(['noteId' => '[0-9]+']);
Route::delete('/notes/{noteId}', [NoteController::class, 'delete'])->name('notes.delete')->where(['noteId' => '[0-9]+']);
```

## 更新画面を作成する
更新するための画面を作成します。  
ほとんど新規作成画面(new_note.blade.php)と変わりませんが変化した部分を説明していきます。

```php
@extends('layouts.app')

@section('title')
メモを編集
@endsection
@section('content')
<form method="POST" action="{{ route('notes.update', ['noteId' => $note->id]) }}">
    @csrf
    @method('PUT')

    <div>
        タイトル:<input type="text" name="title" value="{{ old('title', $note->title) }}">
        @error('title') 
            <p> {{ $message }}</p> 
        @enderror
    </div>
    <div>
        <p>本文:</p>
        <textarea type="text" name='text'>
            {{ old('text', $note->text)}}    
        </textarea>
        @error('text')
            <p> 
                {{ $message }}
            </p>
        @enderror
    
    </div>
    <div>
        <button type="submit">更新</button>
    </div>
</form>
@endsection      
```
