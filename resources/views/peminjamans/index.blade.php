<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(Auth::user()->role !== 'admin')
                    <button @click="openModal = true" class="mb-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow">
                        {{ Auth::user()->role === 'siswa' ? '+ Request Peminjaman' : '+ Input Peminjaman Manual' }}
                    </button>
                @endif

                <table class="min-w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Peminjam</th>
                            <th class="border p-2">Barang</th>
                            <th class="border p-2">Jumlah</th>
                            <th class="border p-2">Tgl Pinjam</th>
                            <th class="border p-2">Status</th>
                            @if(Auth::user()->role === 'petugas')
                                <th class="border p-2">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjamans as $p)
                        <tr class="text-center">
                            <td class="border p-2">{{ $p->user->name }}</td>
                            <td class="border p-2">{{ $p->barang->nama_barang }}</td>
                            <td class="border p-2">{{ $p->jumlah }}</td>
                            <td class="border p-2">{{ $p->tanggal_pinjam }}</td>
                            <td class="border p-2">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $p->status == 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                    {{ $p->status == 'disetujui' ? 'bg-green-200 text-green-800' : '' }}
                                    {{ $p->status == 'dikembalikan' ? 'bg-blue-200 text-blue-800' : '' }}
                                    {{ $p->status == 'ditolak' ? 'bg-red-200 text-red-800' : '' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            @if(Auth::user()->role === 'petugas')
                            <td class="border p-2">
                                @if($p->status == 'pending')
                                    <form action="{{ route('peminjamans.approve', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="bg-green-500 text-white px-3 py-1 rounded text-xs">Approve</button>
                                    </form>
                                @elseif($p->status == 'disetujui')
                                    <form action="{{ route('peminjamans.return', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs">Kembalikan</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto" x-cloak>
            <div class="bg-white p-8 rounded-lg w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Form Peminjaman</h3>
                <form action="{{ route('peminjamans.store') }}" method="POST">
                    @csrf
                    
                    @if(Auth::user()->role === 'petugas')
                    <div class="mb-4">
                        <label class="block mb-1">Pilih Siswa (Input Manual)</label>
                        <select name="user_id" class="w-full border-gray-300 rounded shadow-sm">
                            @foreach($siswas as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="block mb-1">Pilih Barang</label>
                        <select name="barang_id" class="w-full border-gray-300 rounded shadow-sm">
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Jumlah</label>
                        <input type="number" name="jumlah" min="1" value="1" class="w-full border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openModal = false" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>