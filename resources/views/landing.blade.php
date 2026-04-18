<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Informasi Sistem Inventaris</title>

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
z-index:0;
opacity:.5;
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

/* navbar */
.navbar{
position:fixed;
top:20px;
right:20px;

display:flex;
align-items:center;
gap:12px;

padding:10px 18px;
border-radius:40px;

background:rgba(0,0,0,.4);
backdrop-filter:blur(20px);
border:1px solid rgba(255,255,255,.2);

z-index:50;
}

.navbar a{
padding:8px 18px;
border-radius:20px;

font-weight:600;
font-size:14px;

color:#fff;

border:1px solid rgba(255,255,255,.3);
background:rgba(255,255,255,.1);

transition:.25s;
text-decoration:none;
}

.navbar a:hover{
background:#fff;
color:#4f46e5;
transform:translateY(-2px);
}

/* container */
.container{
max-width:1100px;
margin:auto;
padding:140px 20px 80px;
position:relative;
z-index:5;
}

/* main card */
.card{
background:rgba(0,0,0,.55);
backdrop-filter:blur(25px);
border:1px solid rgba(255,255,255,.2);

border-radius:30px;
padding:60px 50px;

box-shadow:0 30px 60px rgba(0,0,0,.4);

text-align:center;
color:white;
}

/* title */
.hero-title{
font-size:clamp(2rem,4vw,3rem);
font-weight:800;
color:white;
margin-bottom:10px;
text-shadow:0 4px 12px rgba(0,0,0,.6);
}

.hero-sub{
color:#e5e7eb;
font-size:1.1rem;
max-width:600px;
margin:auto;
margin-bottom:40px;
}

/* features */
.features{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-top:30px;
}

.feature{
padding:28px 20px;
border-radius:22px;

background:rgba(255,255,255,.12);
border:1px solid rgba(255,255,255,.2);

color:white;
text-align:center;

transition:.3s;
}

.feature i{
font-size:28px;
margin-bottom:12px;
display:block;
}

.feature:hover{
transform:translateY(-6px);
background:rgba(255,255,255,.25);
}

/* about */
.about{
margin-top:40px;
padding:40px;

border-radius:25px;

background:rgba(255,255,255,.12);
border:1px solid rgba(255,255,255,.2);

color:#e5e7eb;
line-height:1.7;
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

.card{
padding:40px 25px;
}

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
<a href="/">
<i class="fas fa-arrow-left"></i>
Kembali
</a>
</div>

<div class="container">

<div class="card">

<h1 class="hero-title">
Informasi Sistem Inventaris
</h1>

<p class="hero-sub">
Sistem ini membantu sekolah mencatat, memantau, dan mengelola 
barang inventaris seperti komputer, proyektor, dan peralatan lainnya.
</p>

<div class="features">

<div class="feature">
<i class="fas fa-box"></i>
<strong>Manajemen Data Barang</strong>
<br>
Menambahkan, mengubah, dan menghapus data barang inventaris sekolah.
</div>

<div class="feature">
<i class="fas fa-hand-holding"></i>
<strong>Pencatatan Peminjaman</strong>
<br>
Mencatat siapa yang meminjam barang dan kapan barang harus dikembalikan.
</div>

<div class="feature">
<i class="fas fa-chart-bar"></i>
<strong>Monitoring Stok</strong>
<br>
Melihat jumlah barang yang tersedia dan yang sedang dipinjam.
</div>

<div class="feature">
<i class="fas fa-clock"></i>
<strong>Riwayat Aktivitas</strong>
<br>
Melihat histori transaksi peminjaman barang secara lengkap.
</div>

</div>

<div class="about">

<h3>Tentang Aplikasi</h3>

<p>
Aplikasi inventaris sekolah dibuat untuk membantu petugas dalam mengelola
data barang dengan lebih terstruktur. Semua data tersimpan secara digital
sehingga memudahkan proses pencarian, peminjaman, dan pelaporan.
</p>

</div>

</div>

</div>

<div class="footer">
© 2026 Sistem Inventaris SMKN 4 Padalarang
</div>

</body>
</html>