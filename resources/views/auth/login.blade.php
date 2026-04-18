<x-guest-layout>

<style>

.input{
width:100%;
padding:11px 14px;
border-radius:10px;
border:1px solid rgba(255,255,255,.2);
background:rgba(255,255,255,.1);
color:#fff;
outline:none;
transition:.2s;
}

.input::placeholder{
color:#cbd5e1;
}

.input:focus{
border-color:#6366f1;
background:rgba(255,255,255,.15);
}

.label{
font-size:14px;
color:#e5e7eb;
margin-bottom:4px;
display:block;
}

.btn-login{
width:100%;
padding:11px;
border-radius:20px;
border:none;
background:#4f46e5;
color:white;
font-weight:600;
transition:.25s;
}

.btn-login:hover{
background:#4338ca;
}

.link{
font-size:13px;
color:#c7d2fe;
}

.link:hover{
text-decoration:underline;
}

.eye{
position:absolute;
right:10px;
top:38px;
cursor:pointer;
color:#cbd5e1;
}

.title{
text-shadow:0 4px 12px rgba(0,0,0,.6);
}

</style>

<div class="w-full">

<h2 class="text-2xl font-bold text-white mb-6 text-center title">
Login
</h2>

<form method="POST" action="{{ route('login') }}" class="space-y-5">
@csrf

<div>
<label class="label">Email</label>
<input type="email" name="email" class="input" placeholder="Masukkan email" required>
</div>

<div class="relative">
<label class="label">Password</label>
<input id="password" type="password" name="password" class="input pr-10" placeholder="Masukkan password" required>
<span onclick="togglePassword()" class="eye">👁</span>
</div>

@if (Route::has('password.request'))
<div class="text-right -mt-3">
<a href="{{ route('password.request') }}" class="link">Lupa password?</a>
</div>
@endif

<button type="submit" class="btn-login">
Login
</button>

<p class="text-sm text-center text-gray-200 mt-4">
Belum punya akun?
<a href="{{ route('register') }}" class="link">Daftar</a>
</p>

</form>

</div>

<script>
function togglePassword(){
const password=document.getElementById('password');
password.type=password.type==="password"?"text":"password";
}
</script>

</x-guest-layout>