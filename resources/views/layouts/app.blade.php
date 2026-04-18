<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Inventaris Sekolah</title>

<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

<script src="https://unpkg.com/lucide@latest"></script>

@vite(['resources/css/app.css','resources/js/app.js'])

<style>

body{
font-family:'Poppins',sans-serif;
}

.sidebar-item-active{
background:rgba(255,255,255,0.15);
}

.nav-item{
transition:all .2s ease;
}

.nav-item:hover{
background:rgba(255,255,255,0.12);
transform:translateX(3px);
}

/* sidebar mobile */
.sidebar{
transition:transform .3s ease;
}

</style>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">

<div class="flex h-screen overflow-hidden">

<!-- OVERLAY MOBILE -->
<div id="sidebarOverlay"
class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"
onclick="toggleSidebar()">
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
class="sidebar fixed md:static z-40 w-60 bg-gradient-to-b from-purple-700 via-blue-700 to-indigo-800 text-white flex flex-col h-screen shadow-xl
-translate-x-full md:translate-x-0">

<!-- LOGO -->
<div class="p-6 text-center border-b border-white/20">

<div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl mx-auto mb-3 flex items-center justify-center shadow">
<i data-lucide="package" class="w-6 h-6 text-white"></i>
</div>

<h1 class="text-lg font-bold">
Inventori
</h1>

<p class="text-xs opacity-80">
Sistem Barang
</p>

</div>


<!-- NAV -->
<nav class="flex-1 p-4 space-y-2 overflow-y-auto">

<a href="{{ route('dashboard') }}"
class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl
{{ request()->routeIs('dashboard') ? 'sidebar-item-active' : '' }}">

<div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center">
<i data-lucide="layout-dashboard" class="w-5 h-5 text-white"></i>
</div>

<span class="text-sm font-medium">
Dashboard
</span>

</a>


<a href="{{ route('barangs.index') }}"
class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl
{{ request()->routeIs('barangs.*') ? 'sidebar-item-active' : '' }}">

<div class="w-9 h-9 bg-emerald-500 rounded-lg flex items-center justify-center">
<i data-lucide="boxes" class="w-5 h-5 text-white"></i>
</div>

<span class="text-sm font-medium">
Data Barang
</span>

</a>


<a href="{{ route('peminjamans.index') }}"
class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl
{{ request()->routeIs('peminjamans.*') ? 'sidebar-item-active' : '' }}">

<div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center">
<i data-lucide="clipboard-list" class="w-5 h-5 text-white"></i>
</div>

<span class="text-sm font-medium">
Peminjaman
</span>

</a>


@if(auth()->user()->role === 'admin')

<a href="{{ route('users.index') }}"
class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl
{{ request()->routeIs('users.*') ? 'sidebar-item-active' : '' }}">

<div class="w-9 h-9 bg-purple-500 rounded-lg flex items-center justify-center">
<i data-lucide="users" class="w-5 h-5 text-white"></i>
</div>

<span class="text-sm font-medium">
Manajemen User
</span>

</a>

@endif

</nav>


<div class="p-4 border-t border-white/20 text-center text-xs opacity-70">
 © 2026
</div>

</aside>



<!-- MAIN -->
<div class="flex-1 flex flex-col h-screen">

<!-- TOPBAR -->
<header class="bg-white/90 backdrop-blur-lg h-16 flex items-center justify-between px-4 md:px-6 shadow-sm border-b">

<div class="flex items-center gap-3">

<button onclick="toggleSidebar()"
class="p-2 rounded-lg bg-indigo-600 text-white md:hidden">
<i data-lucide="menu" class="w-5 h-5"></i>
</button>

<h1 class="text-sm md:text-lg font-semibold text-gray-800">
{{ $header ?? 'Dashboard' }}
</h1>

</div>


<!-- USER -->
<div class="relative">

<button onclick="toggleUserMenu()"
class="flex items-center gap-3 px-3 py-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition">

<div class="w-8 h-8 md:w-9 md:h-9 bg-indigo-500 rounded-lg flex items-center justify-center">
<i data-lucide="user" class="w-4 h-4 text-white"></i>
</div>

<div class="hidden sm:block text-left leading-tight">

<span class="block text-sm font-medium text-gray-800">
{{ auth()->user()->name }}
</span>

<span class="text-[10px] bg-indigo-600 text-white px-2 py-0.5 rounded">
{{ ucfirst(auth()->user()->role) }}
</span>

</div>

<i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>

</button>



<div id="userMenu"
class="hidden absolute right-0 mt-3 w-56 bg-white border rounded-xl shadow-lg py-2">

<div class="px-4 py-3 border-b">

<p class="font-medium text-gray-800">
{{ auth()->user()->name }}
</p>

<p class="text-xs text-gray-500">
{{ auth()->user()->email }}
</p>

</div>

<button onclick="openLogoutModal()"
class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 flex items-center gap-2">

<i data-lucide="log-out" class="w-4 h-4"></i>
Keluar

</button>

</div>

</div>

</header>



<!-- CONTENT -->
<main class="flex-1 overflow-y-auto p-4 md:p-6">
{{ $slot }}
</main>

</div>

</div>



<!-- MODAL -->
<div id="logoutModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">

<div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 text-center">

<div class="w-14 h-14 bg-red-500 rounded-xl mx-auto mb-4 flex items-center justify-center">
<i data-lucide="log-out" class="w-6 h-6 text-white"></i>
</div>

<h2 class="text-lg font-semibold text-gray-800 mb-2">
Konfirmasi Logout
</h2>

<p class="text-gray-600 text-sm mb-6">
Yakin ingin keluar dari sistem?
</p>

<div class="flex justify-center gap-3">

<button onclick="closeLogoutModal()"
class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-100">
Batal
</button>

<form method="POST" action="{{ route('logout') }}">
@csrf

<button type="submit"
class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
Logout
</button>

</form>

</div>

</div>

</div>



<script>

lucide.createIcons()

function toggleSidebar(){

const sidebar=document.getElementById("sidebar")
const overlay=document.getElementById("sidebarOverlay")

sidebar.classList.toggle("-translate-x-full")
overlay.classList.toggle("hidden")

}


function toggleUserMenu(){
document.getElementById("userMenu").classList.toggle("hidden")
}

function openLogoutModal(){
document.getElementById("logoutModal").classList.remove("hidden")
}

function closeLogoutModal(){
document.getElementById("logoutModal").classList.add("hidden")
}

document.addEventListener('click',function(e){

const menu=document.getElementById("userMenu")
const btn=e.target.closest('[onclick="toggleUserMenu()"]')

if(!btn && !menu.contains(e.target)){
menu.classList.add("hidden")
}

})

</script>

</body>
</html>