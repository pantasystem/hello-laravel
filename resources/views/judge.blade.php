<!DOCTYPE html>
<html>
    <head>
        <title>Hello Blade!!</title>
    </head>
    <body>
        <h1>基数偶数判定だ！！</h1>
        @if($number % 2 == 0)
            <p>偶数だ！！</p>
        @else 
            <p>奇数だ！！</p>
        @endif
    </body>
</html>