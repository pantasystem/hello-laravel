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
Route::get('/notes/{noteId}/edit', [NoteController::class, 'edit'])->name('notes.edit')->where(['noteId' => '[0-9]+']);
Route::put('/notes/{noteId}', [NoteController::class, 'update'])->name('notes.update')->where(['noteId' => '[0-9]+']);
Route::delete('/notes/{noteId}', [NoteController::class, 'delete'])->name('notes.delete')->where(['noteId' => '[0-9]+']);
```

## 更新画面を作成する
更新するための画面を作成します。  
ほとんど新規作成画面(new_note.blade.php)と変わりませんが、  
変化した特に重要な部分を説明していきます。

```html
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

### 変更箇所１
単純に送信先が変わったのでそれに合わせ変更しました。
```html
<form method="POST" action="{{ route('notes.update', ['noteId' => $note->id]) }}">

```

### 変更箇所 2
先ほど説明しましたがデフォルトではHTMLのformのメソッドははGETとPOST以外サポートしていません。  
そこでLaravel側でPUTとして扱われるようにするために以下のコードを追加しました。
```html
@method('PUT')
```

### 変更箇所３
oldヘルパ関数のの第二引数にControllerから渡されてきたnoteのタイトルを渡しています。  
こうすることによって、以前入力したデータがなければ第二引数のタイトルが表示されます。  
つまり、編集時初めて開いたときは編集対象のメモが表示され、  
入力内容に何らかの問題があった場合はoldヘルパ関数によって入力内容が保持されます。
```
タイトル:<input type="text" name="title" value="{{ old('title', $note->title) }}">
```


## Controllerの実装をする
そろそろ慣れてきたころだと思うので、  
editとupdate同時に実装します。
> NoteController.php

```php
public function edit($noteId)
{
    $note = Note::findOrFail($noteId);

    // 編集データの初期値としてnoteが必要なので渡しています。
    return view('edit_note', ['note' => $note]);
}
```

引き続き、今度はput先を実装します。  
入力内容が作成時と完全に一致しているためCreateNoteRequestを流用しています。  
~~気持ち悪いですが面倒なので放置します~~  

> NoteController.php
```php
public function update(CreateNoteRequest $request, $noteId)
{
    // 更新対称のNoteを取得します。
    $note = Note::findOrFail($noteId);

    // title, textを連想配列で取得します。
    $params = $request->only('title', 'text');

    // 取得したNoteインスタンスに送られてきたデータ(title, text)を適応します。
    $note->fill($params);

    // Noteインスタンスの状態を保存します。
    $note->save();

    // 詳細画面に遷移する
    return redirect()->route('get', ['noteId' => $noteId]);

}

```

## 編集画面へ遷移できるようにする
現時点ではいずれの画面からも編集画面へ遷移することができないので、  
詳細画面から編集画面へ遷移できるようにしたいと思います。
notes_detailを開きます。  
編集先のリンクを追加します。
> notes_detail
```html
@extends('layouts.app')

@section('title')
{{ $note->title }}
@endsection

@section('content')
<div>
    <h2>{{ $note->title }}</h2>
    <p>
        {{ $note->text }}
    </p>
    <a href="{{ route('notes')}}">一覧へ</a>
    <a href="{{ route('notes.edit', ['noteId' => $note->id])}}">編集</a>
</div>
@endsection
```

## 動作確認
正しく実装できていれば、更新後詳細画面へ遷移すると思います。

## 削除処理の仕様
詳細画面に削除ボタンを置いて削除したいのですが、  
削除してしまう前に確認画面を表示するようにしたいです。  
確認画面はいくつかの方法が存在します。  
* PHPで何とかする
* JavaScriptで何とかする
* 現実は非情である

ですが今回は削除が本題であって、確認画面を作成するのはメインではありません。  
そのため「現実は非情である」を選択します。  
そのため仕様は詳細画面に削除ボタンを置いて押したら削除すようにします。

## 削除ボタンの実装
DELETEメソッドを使用するのでFORMを使用して実装します。
```html
@extends('layouts.app')

@section('title')
{{ $note->title }}
@endsection

@section('content')
<div>
    <h2>{{ $note->title }}</h2>
    <p>
        {{ $note->text }}
    </p>
    <a href="{{ route('notes')}}">一覧へ</a>
    <a href="{{ route('notes.edit', ['noteId' => $note->id])}}">編集</a>
    <form method="POST" action="{{ route('notes.delete', ['noteId' => $note->id])}}">
        @csrf
        @method('DELETE')
        <button type="submit">完全に削除する</button>
    </form>
</div>
@endsection
```

## NoteControllerにdeleteメソッド実装する
取得したNoteインスタンスに対してdeleteメソッドを呼び出して、  
一覧画面へリダイレクトしているだけの至って単純な実装です。
```php
public function delete($noteId)
{
    $note = Note::findOrFail($noteId);

    // 削除する
    $note->delete();

    // 一覧画面へ遷移する
    return redirect()->route('notes');
}
