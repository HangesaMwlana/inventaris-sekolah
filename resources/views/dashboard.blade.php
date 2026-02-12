<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 h-full">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ========================= --}}
            {{-- DASHBOARD SISWA --}}
            {{-- ========================= --}}
            @if(Auth::user()->role === 'siswa')

                <div class="bg-white p-8 rounded-xl shadow">

                    <h3 class="text-2xl font-bold mb-6">
                        Selamat Datang, {{ Auth::user()->name }} 👋
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div class="bg-blue-50 p-6 rounded-xl">
                            <p class="text-sm text-blue-600 font-semibold">Sedang Dipinjam</p>
                            <p class="text-3xl font-bold text-blue-800">
                                {{ $totalPinjamSaya }}
                            </p>
                        </div>

                        <div class="bg-yellow-50 p-6 rounded-xl">
                            <p class="text-sm text-yellow-600 font-semibold">Menunggu Approval</p>
                            <p class="text-3xl font-bold text-yellow-800">
                                {{ $totalPendingSaya }}
                            </p>
                        </div>

                        <div class="bg-red-50 p-6 rounded-xl">
                            <p class="text-sm text-red-600 font-semibold">Terlambat</p>
                            <p class="text-3xl font-bold text-red-800">
                                {{ $totalTerlambatSaya }}
                            </p>
                        </div>

                    </div>

                </div>

            {{-- ========================= --}}
            {{-- DASHBOARD PETUGAS / ADMIN --}}
            {{-- ========================= --}}
            @else

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">

                    <div class="bg-white p-5 rounded-xl shadow">
                        <p class="text-sm text-gray-500">Total Stok Barang</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $totalStokBarang }}
                        </p>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow">
                        <p class="text-sm text-gray-500">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $totalSiswa }}
                        </p>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow">
                        <p class="text-sm text-gray-500">Sedang Dipinjam</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $totalPinjam }}
                        </p>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow">
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $totalPending }}
                        </p>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow">
                        <p class="text-sm text-gray-500">Terlambat</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ $totalTerlambat }}
                        </p>
                    </div>

                </div>

                {{-- Grafik --}}
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="font-semibold mb-4">
                        Grafik Peminjaman Tahun {{ date('Y') }}
                    </h3>

                    <div class="h-72">
                        <canvas id="loanChart"></canvas>
                    </div>
                </div>


                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const chartData = @json($chartData);

                    const ctx = document.getElementById('loanChart').getContext('2d');

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
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                </script>

            @endif

        </div>
    </div>
</x-app-layout>