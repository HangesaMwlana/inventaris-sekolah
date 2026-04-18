<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Inventaris Sekolah') }}</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>

body{
font-family:'Poppins',sans-serif;

background:
linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)),
linear-gradient(-45deg,#667eea,#764ba2,#4facfe,#00f2fe);

background-size:400% 400%;
animation:bgmove 15s ease infinite;

overflow-x:hidden;
}

@keyframes bgmove{
0%{background-position:0% 50%}
50%{background-position:100% 50%}
100%{background-position:0% 50%}
}

/* CARD GLOBAL */
.glass-card{
background:rgba(0,0,0,.6);
backdrop-filter:blur(20px);
border:1px solid rgba(255,255,255,.2);
border-radius:25px;
padding:30px 25px;
box-shadow:0 20px 40px rgba(0,0,0,.4);
color:white;
}

/* TITLE */
.title{
color:white;
font-size:28px;
font-weight:700;
text-shadow:0 4px 12px rgba(0,0,0,.6);
}

</style>

</head>

<body class="min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-md">

<div class="mb-8 text-center">
<h1 class="title">Inventaris Sekolah</h1>
</div>

<div class="glass-card">
{{ $slot }}
</div>

</div>

</body>
</html>