<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - F4UZIAHTAILOR</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    @include('partials.sidebar')

    <div class="main-wrapper">
        @include('partials.topbar')

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
