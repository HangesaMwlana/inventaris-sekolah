<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ ucfirst(Auth::user()->role) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(Auth::user()->role !== 'siswa')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Total Stok Barang</div>
                    <div class="text-3xl font-bold">{{ $total_barang }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Barang Sedang Dipinjam</div>
                    <div class="text-3xl font-bold">{{ $total_pinjam }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-indigo-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Total Siswa Terdaftar</div>
                    <div class="text-3xl font-bold">{{ $total_siswa }}</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-bold mb-4">Grafik Peminjaman Barang {{ date('Y') }}</h3>
                <canvas id="loanChart" height="100"></canvas>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-indigo-600">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="mt-2 text-gray-600">Silakan gunakan menu <b>Peminjaman</b> untuk melakukan request alat sekolah.</p>
                <div class="mt-6 p-4 bg-indigo-50 rounded-md border border-indigo-100">
                    <p class="text-sm text-indigo-700"><b>Tips:</b> Pastikan barang yang Anda pinjam dikembalikan tepat waktu agar stok tetap terjaga untuk teman-teman yang lain.</p>
                </div>
            </div>
            @endif

        </div>
    </div>

@if(Auth::user()->role !== 'siswa')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pindahkan data Blade ke variabel JS agar VS Code tidak bingung
            const chartLabels = {!! json_encode($chart_data->pluck('month')) !!};
            const chartDataValues = {!! json_encode($chart_data->pluck('count')) !!};

            const ctx = document.getElementById('loanChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: chartDataValues,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        });
    </script>
@endif
</x-app-layout>