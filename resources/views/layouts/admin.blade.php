<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #1a1a1a;
            color: white;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }

        .nav-link {
            color: #a0aec0;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: white;
            background: #2d2d2d;
        }

        .nav-link.active {
            color: white;
            background: #2d2d2d;
        }

        .header {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .notification-bell {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="sidebar">
        <div class="p-5">
            <h2 class="text-xl font-bold">Admin Panel</h2>
        </div>
        <nav>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Quản lý người dùng
            </a>
            <a href="{{ route('admin.posts.index') }}"
                class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Quản lý bài viết
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="text-xl font-semibold">@yield('title')</h1>
            <div class="flex items-center gap-4">
                <div class="notification-bell">
                    <i class="fas fa-bell text-gray-600 text-xl"></i>
                    <span class="notification-badge">3</span>
                </div>
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Thông báo -->
    @if (session('success'))
        <x-notification type="success" :message="session('success')" />
    @endif

    @if (session('error'))
        <x-notification type="error" :message="session('error')" />
    @endif

    @if (session('warning'))
        <x-notification type="warning" :message="session('warning')" />
    @endif

    @if (session('info'))
        <x-notification type="info" :message="session('info')" />
    @endif

    @stack('scripts')
</body>

</html>
