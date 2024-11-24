<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Employee Management'))</title>

    <!-- Bootstrap CSS (sử dụng CDN hoặc file cục bộ) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Hoặc sử dụng file cục bộ: <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Một số CSS cơ bản */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 25px;
        }

        .header {
            background-color: #343a40;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 3px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .btn {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
        }

    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="header">
        <h1>Employee Account</h1>
    </div>
    @if (Auth::check())
        @include('layouts.navigation')

    @endif
    <div>
        
        <div class="container">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
