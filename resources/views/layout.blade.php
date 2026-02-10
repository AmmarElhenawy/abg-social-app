<!DOCTYPE html>
<html>

<head>
    <title>Mini Social App</title>
</head>

<body>

    @auth
        <p>
            Welcome, {{ auth()->user()->name }} |
        <form action="/logout" method="POST" style="display:inline">
            @csrf
            <button>Logout</button>
        </form>
        </p>
    @endauth

    <hr>

    @yield('content')

</body>

</html>