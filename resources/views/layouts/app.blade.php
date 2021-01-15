<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
    </head>
    <body>
        <div>
            <h1>はろーLaravel App</h1>
            <ul>
                <li><a href="/">Welcome</a></li>
                <li><a href="/hello">Hello</a></li>
                <li><a href="{{ route('bmi') }}">BMI</a></li>
            </ul>
        </div>
        <div>
            @yield('content')
        </div>
    </body>
</html>