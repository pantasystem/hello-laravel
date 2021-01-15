<!DOCTYPE html>
<html>
    <head>
        <title>Hello Blade!!</title>
    </head>
    <body>
        <h1>bladeに入門！！</h1>
        <p>{{ $message }}</p>

        <!--エスケープされない-->
        <p>{!! $message !!}</p>
    </body>
</html>