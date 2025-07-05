<!DOCTYPE html>
<html lang="en" class="h-full w-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - SPK Beasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


    <style>
        html, body {
            border-radius: 0 !important;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="bg-[#C7CEDB] h-screen w-screen overflow-hidden">

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 w-64 h-full bg-blue-700">
        @include('superadmin.layouts.sidebar')
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex flex-col h-full">

        <!-- Navbar -->
        <div class="h-16 bg-white flex items-center justify-end px-6 shadow-md">
            @include('superadmin.layouts.navbar')
        </div>

        <!-- Header Title -->
        <div class="bg-[#C7CEDB] px-6 py-2 flex items-center space-x-2 sticky top-16 z-10">
            <i data-lucide="home" class="w-7 h-7 text-black-700"></i>
            <h1 class="text-2xl font-bold text-black-700">@yield('title')</h1>
        </div>

        <!-- Fixed White Content -->
        <div class="bg-white m-4 shadow-md overflow-auto flex flex-col ">

            <!-- Scrollable Inner Content -->
            <div class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </div>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
@stack('scripts')

</body>
</html>
