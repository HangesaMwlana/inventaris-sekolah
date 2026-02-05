<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

            {{-- CONTENT --}}
            <div class="p-6">

                {{-- GREETING --}}
                <h2 class="text-xl font-semibold mb-1">
                    Selamat Datang, {{ Auth::user()->role === 'admin' ? 'Admin' : Auth::user()->name }}
                </h2>
                <p class="text-gray-500 mb-6">Sistem Inventaris Barang</p>

                {{-- CARD STATISTIK (ADMIN & PETUGAS) --}}
                @if(Auth::user()->role !== 'siswa')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                            📦
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Stok Barang</p>
                            <p class="text-xl font-bold">{{ $total_barang }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
                        <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                            🔁
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Peminjaman</p>
                            <p class="text-xl font-bold">{{ $total_pinjam }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
                        <div class="bg-orange-100 text-orange-600 p-3 rounded-lg">
                            👥
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Siswa Terdaftar</p>
                            <p class="text-xl font-bold">{{ $total_siswa }}</p>
                        </div>
                    </div>
                </div>

                {{-- CHART --}}
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="font-semibold mb-4">Grafik Peminjaman Barang {{ date('Y') }}</h3>
                    <canvas id="loanChart" height="100"></canvas>
                </div>
                @else

                {{-- SISWA --}}
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-xl font-semibold text-blue-600">
                        Selamat Datang, {{ Auth::user()->name }} 👋
                    </h3>
                    <p class="mt-2 text-gray-600">
                        Silakan gunakan menu <b>Peminjaman</b> untuk melakukan request alat sekolah.
                    </p>
                </div>
                @endif

            </div>
        </main>
    </div>

    
</x-app-layout>