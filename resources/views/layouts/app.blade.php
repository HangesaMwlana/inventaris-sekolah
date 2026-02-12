<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventaris Sekolah</title>

    <!-- Font -->
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-inter bg-gray-100">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-600 text-white flex flex-col h-screen">
        <div class="p-6 text-xl font-bold border-b border-blue-500">
            Inventori Barang
        </div>

        <nav class="flex-1 p-6 space-y-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500">
                Dashboard
            </a>

            <a href="{{ route('barangs.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500">
                Data Barang
            </a>

            <a href="{{ route('peminjamans.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500">
                Peminjaman
            </a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500">
                    Manajemen User
                </a>
            @endif

            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500">
                Laporan
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-500">
                Tentang
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t border-blue-500">
            @csrf
            <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-500">
                Logout
            </button>
        </form>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-lg font-semibold">Dashboard</h1>

            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-full">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
