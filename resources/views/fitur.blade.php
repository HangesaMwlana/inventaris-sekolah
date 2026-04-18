<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Sistem Inventaris Sekolah</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
min-height:100vh;
overflow-x:hidden;

background:
linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)),
linear-gradient(-45deg,#667eea,#764ba2,#4facfe,#00f2fe);

background-size:400% 400%;
animation:bgmove 15s ease infinite;
}

@keyframes bgmove{
0%{background-position:0% 50%}
50%{background-position:100% 50%}
100%{background-position:0% 50%}
}

/* blobs */
.blob{
position:fixed;
border-radius:50%;
filter:blur(100px);
opacity:.5;
z-index:0;
}

.blob1{
width:350px;
height:350px;
background:#6366f1;
top:-120px;
left:-120px;
}

.blob2{
width:400px;
height:400px;
background:#ec4899;
bottom:-160px;
right:-160px;
}

/* NAVBAR */
.navbar{
position:fixed;
top:20px;
left:50%;
transform:translateX(-50%);

display:flex;
align-items:center;
justify-content:space-between;

padding:10px 25px;

border-radius:40px;

background:rgba(0,0,0,.4);
backdrop-filter:blur(20px);
border:1px solid rgba(255,255,255,.2);

z-index:50;
width:90%;
max-width:900px;
}

.logo{
display:flex;
align-items:center;
gap:10px;
color:white;
font-weight:600;
}

.logo-img{
width:36px;
height:36px;
object-fit:contain;
}

/* BUTTON */
.btn{
padding:7px 18px;
border-radius:20px;

font-size:14px;
font-weight:600;

border:1px solid rgba(255,255,255,.3);
background:rgba(255,255,255,.1);

color:white;
text-decoration:none;

transition:.25s;

display:flex;
align-items:center;
gap:6px;
}

.btn:hover{
background:white;
color:#4f46e5;
transform:translateY(-2px);
}

/* container */
.container{
max-width:900px;
margin:auto;
padding:140px 20px 80px;
position:relative;
z-index:5;
text-align:center;
}

/* HERO */
.hero h1{
font-size:clamp(2rem,4vw,3rem);
color:white;
font-weight:800;
margin-bottom:10px;
text-shadow:0 4px 12px rgba(0,0,0,.6);
}

.hero p{
color:#e5e7eb;
max-width:600px;
margin:auto;
}

/* GRID */
.grid{
margin-top:40px;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
}

/* BOX */
.box{
padding:35px 25px;
border-radius:25px;

background:rgba(0,0,0,.55);
border:1px solid rgba(255,255,255,.2);

color:white;
font-weight:600;
text-decoration:none;

transition:.3s;

display:flex;
flex-direction:column;
align-items:center;
gap:10px;
}

.box i{
font-size:26px;
}

.box:hover{
transform:translateY(-6px);
background:rgba(0,0,0,.7);
}

/* footer */
.footer{
text-align:center;
padding:30px;
color:#e5e7eb;
font-size:14px;
}

/* responsive */
@media(max-width:768px){
.container{
padding-top:120px;
}
}

</style>

</head>

<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="navbar">

<div class="logo">
<img src="{{ asset('images/logo.png') }}" class="logo-img">
<span>Inventaris</span>
</div>

<a href="{{ route('home') }}" class="btn">
<i class="fas fa-arrow-left"></i>
Kembali
</a>

</div>

<div class="container">

<div class="hero">

<h1>Sistem Inventaris Sekolah</h1>

<p>
Aplikasi untuk mengelola barang inventaris sekolah secara digital 
agar pencatatan lebih rapi dan mudah dipantau.
</p>

</div>

<div class="grid">

<a class="box" href="#">
<i class="fas fa-info-circle"></i>
Informasi Sistem
</a>

<a class="box" href="#">
<i class="fas fa-box"></i>
Data Barang
</a>

<a class="box" href="#">
<i class="fas fa-hand-holding"></i>
Peminjaman Barang
</a>

<a class="box" href="#">
<i class="fas fa-clock"></i>
Riwayat Transaksi
</a>

</div>

</div>

<div class="footer">
© 2026 Sistem Inventaris Sekolah
</div>

</body>
</html>