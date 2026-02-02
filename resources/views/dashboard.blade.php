<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(Auth::user()->role === 'siswa')
                {{-- TAMPILAN KHUSUS SISWA --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-b-4 border-indigo-500">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-gray-600 mb-8">
                        Ingin menggunakan fasilitas sekolah? Kamu bisa melihat daftar ketersediaan barang dan mengajukan peminjaman melalui menu yang tersedia.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">Barang Saya Bawa</p>
                            <p class="text-3xl font-black text-blue-800">{{ $totalPinjamSaya }}</p>
                        </div>
                        <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-100">
                            <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest">Menunggu Antrean</p>
                            <p class="text-3xl font-black text-yellow-800">{{ $totalPendingSaya }}</p>
                        </div>
                        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest">Terlambat Kembali</p>
                            <p class="text-3xl font-black text-red-800">{{ $totalTerlambatSaya }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('barangs.index') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition shadow-md">
                            Mulai Pinjam Barang
                        </a>
                        <p class="w-full text-sm text-gray-400 italic mt-2">*Pastikan mengembalikan barang tepat waktu untuk menghindari sanksi.</p>
                    </div>
                </div>

            @else
                {{-- TAMPILAN KHUSUS PETUGAS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-blue-500">
                        <p class="text-xs font-bold text-gray-500 uppercase">Total Unit Barang</p>
                        <p class="text-2xl font-black text-blue-600">{{ $totalStokBarang }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-gray-800">
                        <p class="text-xs font-bold text-gray-500 uppercase">Siswa Terdaftar</p>
                        <p class="text-2xl font-black text-gray-800">{{ $totalSiswa }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-yellow-500">
                        <p class="text-xs font-bold text-gray-500 uppercase">Butuh Approval</p>
                        <p class="text-2xl font-black text-yellow-600">{{ $totalPending }}</p>
                        @if($totalPending > 0)
                            <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full animate-pulse">Cek segera</span>
                        @endif
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-green-500">
                        <p class="text-xs font-bold text-gray-500 uppercase">Sedang Dipinjam</p>
                        <p class="text-2xl font-black text-green-600">{{ $totalPinjam }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm border-t-4 border-red-500">
                        <p class="text-xs font-bold text-gray-500 uppercase">Terlambat</p>
                        <p class="text-2xl font-black text-red-600">{{ $totalTerlambat }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Tren Peminjaman Tahun {{ date('Y') }}</h3>
                        <div class="h-[250px]">
                            <canvas id="loanChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-widest text-sm">Menu Petugas</h3>
                        <div class="space-y-3">
                            <a href="{{ route('barangs.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition border border-transparent hover:border-blue-200 group">
                                <div class="p-2 bg-blue-500 text-white rounded-md group-hover:bg-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Manajemen Stok</span>
                            </a>
                            
                            <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-yellow-50 transition border border-transparent hover:border-yellow-200 group">
                                <div class="p-2 bg-yellow-500 text-white rounded-md group-hover:bg-yellow-600 relative">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <span class="ml-3 font-medium text-gray-700">Approval ({{ $totalPending }})</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @if(Auth::user()->role !== 'siswa')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('loanChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(data => data.month_name),
                    datasets: [{
                        label: 'Peminjaman',
                        data: chartData.map(data => data.total),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    @endif
</x-app-layout>