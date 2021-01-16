# Laravel入門編６章データベースの全体像
具体的な説明に入る前に、今後の流れと、  
これから越えなければならないハードルを簡単に説明します。  
ハードルと言いましたが、ここまで来れれば大丈夫です。

## これから越えなければならないハードル
* マイグレーション
* Eloquent ORM

## マイグレーション
マイグレーションとはテーブルの作成や編集を、  
SQLで直接行うのではなく、PHPのソースから管理しようという仕組みです。  
PHPのソースとして管理することにより、  
変更管理やテーブルの把握がしやすくなります。  
また本番環境などで１コマンドでパパっとテーブルをデータベースに作成できるところもメリットの一つです。

## Eloquent ORM
ORMとはObject Relation Mappingの略で、  
データベースの列とオブジェクトのフィールドをマッピングしてくれます。  
このようなライブラリをORMといい、大抵の言語にあります。  
そしてLaravelのORMのことをEloquentといいます。

例えば以下のようなテーブルがあった場合  

テーブル名: users
|列名|型|not null|primary key|
|----|--|-------|-----------|
|id|int|yes|yes
|name|varchar(25)|yes|no|
|age|int|yes|no|    

ソースコードからはオブジェクトとしてアクセスすることができます。
```php
// 1番のユーザーを読み込み
$user = User::find(1);

// 1番のユーザーの名前を取得しそれを表示する
echo $user->name;

echo $user->age;
```

## オブジェクトとして扱うことのメリット
もちろんオブジェクトとして扱うのではなく、連想配列として扱うこともできるのですが、
連想配列や配列は新たにメソッドやフィールドなどを宣言、実装することができません。
オブジェクトの場合メソッドやフィールドを新たに宣言、実装することができるので、  
データに対して処理内容やロジックを実装することができるのもメリットの一つです。  

> 配列、連想配列の場合
```php
$uArray = ['first_name' => 'hoge', 'last_name' => 'piyo', 'age' => 10 ];

// 何に対しての関数かわからない。
// もしかしたら引数に全く関係のないデータを入れられるかもしれない。
function getFullName($array)
{
    return $array['first_name'] . $array['last_name'];
}
```

> オブジェクトの場合
```php
class User
{
    public $first_name;
    public $last_name;
    public $age;

    // コンストラクタ
    public function __construct($f_name, $l_name, $age)
    {
        $this->first_name = $f_name;
        $this->last_name = $l_name;
        $this->age = $age;
    }

    public function getFullName()
    {
        return $this->first_name . $this->last_name;
    }
}

$user = new User('hoge', 'piyo', 12);
$user->getFullName();
```

## ->ってなんだ？
Javaで言うところの.(ドット)でインスタンスのメソッドやフィールドにアクセスするときに使用します。  

## ::ってなんだ？
Javaで言うところの.(ドット)ですがこちらはstaticなメソッドやフィールドにアクセスするときに使用します。


## まとめ
LaravelではスキーマをPHPのソースコードとして管理することができる。  
Laravelではデータベースをオブジェクトとして扱えるようにすることで、  
オブジェクト指向の恩恵を受けられるようにしている。