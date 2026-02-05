<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, openEditModal: false, editData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERT NOTIFIKASI --}}
            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex justify-between items-center animate-pulse">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="this.parentElement.remove()" class="text-white font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="this.parentElement.remove()" class="text-white font-bold">&times;</button>
                </div>
            @endif

            {{-- FORM CETAK PDF --}}
            @if(Auth::user()->role !== 'siswa')
            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <h3 class="font-bold mb-3 text-gray-700 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak Laporan PDF
                </h3>
                <form action="{{ route('peminjamans.cetak') }}" method="GET" target="_blank" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="date" name="tgl_mulai" class="border-gray-300 rounded text-xs">
                    <input type="date" name="tgl_selesai" class="border-gray-300 rounded text-xs">
                    <select name="status" class="border-gray-300 rounded text-xs">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="dikembalikan">Dikembalikan</option>
                    </select>
                    <select name="user_id" class="border-gray-300 rounded text-xs">
                        <option value="">Semua Siswa</option>
                        @foreach($siswas as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                    </select>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold hover:bg-red-700 transition">Download PDF</button>
                </form>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <form action="{{ route('peminjamans.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam/barang..." class="border-gray-300 rounded-md text-sm w-64">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-black transition">Cari</button>
                    </form>
                    @if(Auth::user()->role !== 'admin')
                        <button @click="openModal = true" class="bg-indigo-600 text-black px-4 py-2 rounded text-sm font-bold shadow-md hover:bg-indigo-700 transition">+ Tambah Data</button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase tracking-wider">
                                <th class="p-3 text-left">Peminjam</th>
                                <th class="p-3 text-left">Barang</th>
                                <th class="p-3 text-center">Jumlah</th>
                                <th class="p-3 text-left">Deskripsi</th>
                                <th class="p-3 text-center">Tgl Pinjam</th>
                                <th class="p-3 text-center bg-blue-50 text-blue-600">Tgl Kembali</th>
                                <th class="p-3 text-center">Jatuh Tempo</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @forelse($peminjamans as $p)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3 font-semibold">{{ $p->user->name }}</td>
                                <td class="p-3 text-indigo-700 font-medium">{{ $p->barang->nama_barang }}</td>
                                <td class="p-3 text-center">{{ $p->jumlah }}</td>
                                <td class="p-3 text-gray-500 italic">{{ Str::limit($p->deskripsi, 20) }}</td>
                                <td class="p-3 text-center">{{ $p->tanggal_pinjam }}</td>
                                <td class="p-3 text-center font-bold text-blue-600 bg-blue-50/30">
                                    {{ $p->tanggal_kembali ?? '-' }}
                                </td>
                                <td class="p-3 text-center font-bold {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->isPast() && $p->status != 'dikembalikan' ? 'text-red-600' : '' }}">
                                    {{ $p->tanggal_jatuh_tempo }}
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-[9px] font-bold border
                                        {{ $p->status == 'pending' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : '' }}
                                        {{ $p->status == 'disetujui' ? 'bg-green-100 text-green-700 border-green-300' : '' }}
                                        {{ $p->status == 'dikembalikan' ? 'bg-blue-100 text-blue-700 border-blue-300' : '' }}">
                                        {{ strtoupper($p->status) }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-1">
                                        {{-- AKSI KHUSUS SISWA --}}
                                        @if(Auth::user()->role === 'siswa')
                                            {{-- Siswa hanya bisa membatalkan pengajuan MILIKNYA SENDIRI dan status masih PENDING --}}
                                            @if($p->user_id === Auth::id() && $p->status == 'pending')
                                                <form action="{{ route('peminjamans.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="bg-red-500 text-black px-2 py-1 rounded text-[10px] font-bold hover:bg-red-600 transition">BATAL</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 italic text-[10px]">No Action</span>
                                            @endif
                                        @endif

                                        {{-- AKSI KHUSUS PETUGAS --}}
                                        @if(Auth::user()->role === 'petugas')
                                            @if($p->status == 'pending')
                                                <form action="{{ route('peminjamans.approve', $p->id) }}" method="POST">@csrf
                                                    <button class="bg-green-600 text-black px-2 py-1 rounded text-[10px] font-bold">SETUJUI</button>
                                                </form>
                                            @elseif($p->status == 'disetujui')
                                                <form action="{{ route('peminjamans.return', $p->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian barang?')">
                                                    @csrf
                                                    <button class="bg-blue-600 text-black px-2 py-1 rounded text-[10px] font-bold">KEMBALI</button>
                                                </form>
                                            @endif

                                            @if($p->status !== 'dikembalikan')
                                                <button @click="openEditModal = true; editData = { id: '{{ $p->id }}', jumlah: '{{ $p->jumlah }}', deskripsi: '{{ addslashes($p->deskripsi) }}', tempo: '{{ $p->tanggal_jatuh_tempo }}' }" 
                                                    class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-[10px] font-bold">EDIT</button>
                                            @else
                                                <form action="{{ route('peminjamans.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="bg-gray-400 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-red-500 transition">HAPUS</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="9" class="p-4 text-center text-gray-400">Data tidak ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $peminjamans->links() }}</div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50" @click="openModal = false"></div>
            <div class="bg-white rounded-xl p-8 z-50 w-full max-w-md shadow-2xl transition-all">
                <form action="{{ route('peminjamans.store') }}" method="POST">
                    @csrf
                    <h3 class="text-xl font-black mb-6 text-indigo-800 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        INPUT PEMINJAMAN
                    </h3>
                    <div class="space-y-4">
                        @if(Auth::user()->role === 'petugas')
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Peminjam</label>
                            <select name="user_id" required class="w-full border-gray-200 rounded-lg text-sm mt-1">
                                <option value="">Pilih Siswa...</option>
                                @foreach($siswas as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                            </select>
                        </div>
                        @endif
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Barang</label>
                            <select name="barang_id" required class="w-full border-gray-200 rounded-lg text-sm mt-1">
                                <option value="">Pilih Barang...</option>
                                @foreach($barangs as $b) <option value="{{ $b->id }}">{{ $b->nama_barang }} (Tersedia: {{ $b->stok_tersedia }})</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Jumlah</label>
                                <input type="number" name="jumlah" min="1" value="1" required class="w-full border-gray-200 rounded-lg text-sm mt-1">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Tgl Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="p_pinjam" value="{{ date('Y-m-d') }}" required class="w-full border-gray-200 rounded-lg text-sm mt-1">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Jatuh Tempo (Maks 1 Minggu)</label>
                            <input type="date" name="tanggal_jatuh_tempo" id="p_tempo" required class="w-full border-gray-200 rounded-lg text-sm mt-1 bg-indigo-50 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Deskripsi / Keperluan</label>
                            <textarea name="deskripsi" rows="2" placeholder="Contoh: Praktik di bengkel" required class="w-full border-gray-200 rounded-lg text-sm mt-1"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-lg font-bold">BATAL</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold shadow-lg">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50" @click="openEditModal = false"></div>
            <div class="bg-white rounded-xl p-8 z-50 w-full max-w-md shadow-2xl">
                <form :action="`/peminjamans/${editData.id}`" method="POST">
                    @csrf @method('PUT')
                    <h3 class="text-xl font-black mb-6 text-yellow-600 uppercase">Edit Data</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Jumlah</label>
                            <input type="number" name="jumlah" x-model="editData.jumlah" required class="w-full border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Tgl Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" x-model="editData.tempo" required class="w-full border-gray-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editData.deskripsi" rows="2" required class="w-full border-gray-200 rounded-lg text-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-lg font-bold">BATAL</button>
                        <button type="submit" class="flex-1 bg-yellow-500 text-black py-3 rounded-lg font-bold hover:bg-yellow-600">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inPinjam = document.getElementById('p_pinjam');
            const inTempo = document.getElementById('p_tempo');

            function syncTempo() {
                if (inPinjam && inPinjam.value) {
                    let dPinjam = new Date(inPinjam.value);
                    inTempo.min = inPinjam.value;
                    
                    let dMax = new Date(dPinjam);
                    dMax.setDate(dMax.getDate() + 7);
                    inTempo.max = dMax.toISOString().split('T')[0];
                    inTempo.value = inTempo.max; 
                }
            }
            if(inPinjam) inPinjam.addEventListener('change', syncTempo);
            syncTempo();
        });
    </script>
</x-app-layout>