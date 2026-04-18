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

.btn-register{
padding:10px 20px;
border-radius:20px;
border:none;
background:#4f46e5;
color:white;
font-weight:600;
}

.btn-register:hover{
background:#4338ca;
}

.link{
font-size:13px;
color:#c7d2fe;
}

.eye{
position:absolute;
right:10px;
top:38px;
cursor:pointer;
color:#cbd5e1;
}

.error{
font-size:12px;
color:#fca5a5;
}

</style>

<div class="w-full">

<h2 class="text-2xl font-bold text-white mb-6 text-center">
Register
</h2>

<form method="POST" action="{{ route('register') }}" class="space-y-5">
@csrf

<div>
<label class="label">Name</label>
<input type="text" name="name" class="input" required>
</div>

<div>
<label class="label">Email</label>
<input type="email" name="email" class="input" required>
</div>

<div class="relative">
<label class="label">Password</label>
<input id="password" type="password" name="password" class="input pr-10" required>
<span onclick="togglePassword('password')" class="eye">👁</span>
</div>

<div class="relative">
<label class="label">Confirm Password</label>
<input id="password_confirmation" type="password" name="password_confirmation" class="input pr-10" required>
<span onclick="togglePassword('password_confirmation')" class="eye">👁</span>
</div>

<div class="flex justify-between items-center pt-2">
<a href="{{ route('login') }}" class="link">Sudah punya akun?</a>
<button type="submit" class="btn-register">Register</button>
</div>

</form>

</div>

<script>
function togglePassword(id){
const field=document.getElementById(id);
field.type=field.type==="password"?"text":"password";
}
</script>

</x-guest-layout>