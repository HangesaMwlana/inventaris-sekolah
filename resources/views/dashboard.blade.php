<x-app-layout>

<x-slot name="header">
<div class="flex items-center gap-3">
    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center shadow">
        <i data-lucide="activity" class="w-4 h-4 sm:w-5 sm:h-5 text-white"></i>
    </div>

    <div>
        <h2 class="text-lg sm:text-xl font-bold text-gray-800">
            {{ __('Dashboard') }}
        </h2>
        <p class="text-[11px] sm:text-xs text-gray-500">Ringkasan aktivitas sistem</p>
    </div>
</div>
</x-slot>


<div class="py-6 sm:py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">


{{-- ================= SISWA ================= --}}
@if(auth()->user()->role === 'siswa')


{{-- HERO --}}
<div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 text-white 
p-5 sm:p-6 rounded-2xl shadow-lg">

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6">

<div>
<h3 class="text-xl sm:text-2xl font-bold">
Selamat Datang
</h3>

<p class="text-sm opacity-90">
{{ auth()->user()->name }} 👋
</p>

<p class="text-xs opacity-70 mt-1">
Kelola peminjaman barang dengan mudah
</p>
</div>

<div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 rounded-xl flex items-center justify-center">
<i data-lucide="user-check" class="w-7 h-7 sm:w-8 sm:h-8"></i>
</div>

</div>
</div>



{{-- STATS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">


{{-- CARD --}}
<div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-4 sm:p-5 rounded-2xl shadow transition">

<div class="flex items-center justify-between mb-3">

<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center">
<i data-lucide="shopping-bag" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>

<span class="text-base sm:text-lg font-bold">
{{ $totalPinjamSaya ?? 0 }}
</span>

</div>

<h4 class="font-semibold text-sm">Sedang Dipinjam</h4>
<p class="text-xs opacity-80">Barang yang kamu pinjam</p>

</div>



<div class="bg-gradient-to-br from-yellow-400 to-orange-500 text-white p-4 sm:p-5 rounded-2xl shadow transition">

<div class="flex items-center justify-between mb-3">

<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center">
<i data-lucide="clock" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>

<span class="text-base sm:text-lg font-bold">
{{ $totalPendingSaya ?? 0 }}
</span>

</div>

<h4 class="font-semibold text-sm">Menunggu</h4>
<p class="text-xs opacity-80">Menunggu persetujuan</p>

</div>



<div class="bg-gradient-to-br from-red-500 to-pink-600 text-white p-4 sm:p-5 rounded-2xl shadow transition">

<div class="flex items-center justify-between mb-3">

<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center">
<i data-lucide="alert-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>

<span class="text-base sm:text-lg font-bold">
{{ $totalTerlambatSaya ?? 0 }}
</span>

</div>

<h4 class="font-semibold text-sm">Terlambat</h4>
<p class="text-xs opacity-80">Harus dikembalikan</p>

</div>

</div>


@else



{{-- ================= ADMIN / PETUGAS ================= --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">


<div class="bg-gradient-to-br from-indigo-500 to-blue-600 text-white p-4 rounded-2xl shadow text-center">
<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2">
<i data-lucide="package" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>
<div class="text-lg sm:text-xl font-bold">{{ $totalStokBarang ?? 0 }}</div>
<p class="text-xs opacity-80">Total Stok</p>
</div>


<div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-4 rounded-2xl shadow text-center">
<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2">
<i data-lucide="users" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>
<div class="text-lg sm:text-xl font-bold">{{ $totalSiswa ?? 0 }}</div>
<p class="text-xs opacity-80">Total Siswa</p>
</div>


<div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-4 rounded-2xl shadow text-center">
<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2">
<i data-lucide="shopping-cart" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>
<div class="text-lg sm:text-xl font-bold">{{ $totalPinjam ?? 0 }}</div>
<p class="text-xs opacity-80">Dipinjam</p>
</div>


<div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white p-4 rounded-2xl shadow text-center">
<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2">
<i data-lucide="clock" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>
<div class="text-lg sm:text-xl font-bold">{{ $totalPending ?? 0 }}</div>
<p class="text-xs opacity-80">Pending</p>
</div>


<div class="bg-gradient-to-br from-red-500 to-rose-600 text-white p-4 rounded-2xl shadow text-center">
<div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-2">
<i data-lucide="alert-octagon" class="w-4 h-4 sm:w-5 sm:h-5"></i>
</div>
<div class="text-lg sm:text-xl font-bold">{{ $totalTerlambat ?? 0 }}</div>
<p class="text-xs opacity-80">Terlambat</p>
</div>

</div>



{{-- CHART --}}
<div class="bg-white p-5 sm:p-6 rounded-2xl shadow">

<div class="flex items-center justify-between mb-4">

<div>
<h3 class="text-base sm:text-lg font-bold text-gray-800">
📊 Grafik Peminjaman {{ date('Y') }}
</h3>
<p class="text-xs text-gray-500">
Jumlah Peminjaman 
</p>
</div>

<div class="w-8 h-8 sm:w-9 sm:h-9 bg-purple-500 rounded-lg flex items-center justify-center">
<i data-lucide="bar-chart-3" class="w-4 h-4 sm:w-5 sm:h-5 text-white"></i>
</div>

</div>


<div class="h-64 sm:h-72 md:h-80">
<canvas id="loanChart"></canvas>
</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const chartData = @json($chartData ?? []);

if(document.getElementById('loanChart') && chartData.length > 0){

const ctx = document.getElementById('loanChart');

new Chart(ctx,{
type:'line',

data:{
labels: chartData.map(i=>i.month_name),

datasets:[{
label:'Total Peminjaman',
data: chartData.map(i=>i.total),
borderColor:'#6366f1',
backgroundColor:'rgba(99,102,241,0.2)',
fill:true,
tension:0.4,
pointRadius:4
}]
},

options:{
responsive:true,
maintainAspectRatio:false,
plugins:{legend:{display:false}},
scales:{
y:{beginAtZero:true},
x:{grid:{display:false}}
}
}

});

}

</script>


@endif


</div>
</div>

</x-app-layout>