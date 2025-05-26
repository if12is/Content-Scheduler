<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Content Scheduler') }} - Authentication</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="h-full">
    <div class="min-h-full">
        <!-- Page Content -->
        <main>
            @if (session('success'))
            <div id="success-message" data-message="{{ session('success') }}"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const message = document.getElementById('success-message').dataset.message;
                    Swal.fire({
                        title: 'Success!',
                        text: message,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            </script>
            @endif

            @if (session('error'))
            <div id="error-message" data-message="{{ session('error') }}"></div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const message = document.getElementById('error-message').dataset.message;
                    Swal.fire({
                        title: 'Error!',
                        text: message,
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            </script>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Additional Scripts -->
    @yield('scripts')
</body>

</html>