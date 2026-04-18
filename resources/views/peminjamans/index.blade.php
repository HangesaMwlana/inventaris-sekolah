<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12" 
    x-data="{ 
        openModal: false, 
        openEditModal: false, 
        editData: {},
        confirmModal: false,
        confirmMessage: '',
        confirmAction: '',
        confirmMethod: 'POST'
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERT NOTIFIKASI --}}
            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex justify-between items-center relative z-10">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="this.parentElement.remove()" class="text-white font-bold text-2xl">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex justify-between items-center relative z-10">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="this.parentElement.remove()" class="text-white font-bold text-2xl">&times;</button>
                </div>
            @endif

            {{-- FORM CETAK PDF --}}
            @if(Auth::user()->role !== 'siswa')
            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <h3 class="font-bold mb-3 text-gray-700 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak Laporan Berkala (PDF)
                </h3>
                <form action="{{ route('peminjamans.cetak') }}" method="GET" target="_blank" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="date" name="tgl_mulai" class="border-gray-300 rounded text-xs focus:ring-red-500">
                    <input type="date" name="tgl_selesai" class="border-gray-300 rounded text-xs focus:ring-red-500">
                    <select name="status" class="border-gray-300 rounded text-xs">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="dikembalikan">Dikembalikan</option>
                    </select>
                    <select name="user_id" class="border-gray-300 rounded text-xs">
                        <option value="">Semua Siswa</option>
                        @foreach($siswas as $s) 
                            <option value="{{ $s->id }}">{{ $s->name }}</option> 
                        @endforeach
                    </select>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold hover:bg-red-700 transition shadow-sm">Download PDF</button>
                </form>
                <p class="mt-2 text-[10px] text-gray-400 italic">*Disarankan cetak PDF sebelum melakukan "Clear All History".</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- SEARCH & ADD --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <form action="{{ route('peminjamans.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau barang..." class="border-gray-300 rounded-md text-sm w-full md:w-64 focus:ring-indigo-500">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-black transition">Cari</button>
                    </form>
                    @if(Auth::user()->role !== 'admin')
                        <button @click="openModal = true" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold shadow-md hover:bg-indigo-700 transition">+ Tambah Data</button>
                    @endif
                </div>

                {{-- TABEL 1: PEMINJAMAN AKTIF --}}
                <div class="mb-10">
                    <h3 class="text-sm font-black text-orange-600 mb-3 flex items-center uppercase tracking-wider">
                        <span class="mr-2 text-lg">📂</span> Peminjaman Berjalan / Menunggu
                    </h3>
                    <div class="overflow-x-auto border rounded-xl shadow-sm">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase tracking-wider">
                                    <th class="p-3 text-left">Peminjam</th>
                                    <th class="p-3 text-left">Barang</th>
                                    <th class="p-3 text-center">Jumlah</th>
                                    <th class="p-3 text-center">Jatuh Tempo</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @forelse($peminjamans->where('status', '!=', 'dikembalikan') as $p)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="p-3 font-semibold">{{ $p->user->name }}</td>
                                    <td class="p-3 text-indigo-700 font-medium">{{ $p->barang->nama_barang }}</td>
                                    <td class="p-3 text-center font-bold">{{ $p->jumlah }}</td>
                                    <td class="p-3 text-center font-bold {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->isPast() ? 'text-red-600 animate-pulse' : '' }}">
                                        {{ $p->tanggal_jatuh_tempo }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-1 rounded-full text-[9px] font-bold border
                                            {{ $p->status == 'pending' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : 'bg-green-100 text-green-700 border-green-300' }}">
                                            {{ strtoupper($p->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex justify-center gap-1">
                                            @if(Auth::user()->role === 'petugas')
                                                @if($p->status == 'pending')
                                                    <form action="{{ route('peminjamans.approve', $p->id) }}" method="POST">@csrf
                                                        <button class="bg-green-600 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-green-700 shadow-sm">SETUJUI</button>
                                                    </form>
                                                @elseif($p->status == 'disetujui')
                                                    <button type="button" @click="confirmModal = true; confirmMessage = 'Konfirmasi pengembalian barang?'; confirmAction = '{{ route('peminjamans.return', $p->id) }}'; confirmMethod = 'POST';" class="bg-blue-600 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-blue-700 shadow-sm">KEMBALIKAN</button>
                                                @endif
                                                <button @click="openEditModal = true; editData = { id: '{{ $p->id }}', jumlah: '{{ $p->jumlah }}', deskripsi: '{{ addslashes($p->deskripsi) }}', tempo: '{{ $p->tanggal_jatuh_tempo }}' }" class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-[10px] font-bold shadow-sm">EDIT</button>
                                            @endif
                                            @if(Auth::user()->role === 'siswa' && $p->status == 'pending')
                                                <button type="button" @click="confirmModal = true; confirmMessage = 'Hapus pengajuan ini?'; confirmAction = '{{ route('peminjamans.destroy', $p->id) }}'; confirmMethod = 'DELETE';" class="bg-gray-400 text-white px-2 py-1 rounded text-[10px] font-bold hover:bg-red-600 transition shadow-sm">BATAL</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="6" class="p-4 text-center text-gray-400">Tidak ada peminjaman aktif saat ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 2: RIWAYAT (OTOMATIS SEMBUNYI SETELAH 2 BULAN) --}}
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-sm font-black text-blue-600 flex items-center uppercase tracking-wider">
                            <span class="mr-2 text-lg">✅</span> Riwayat Selesai (Maks. 2 Bulan)
                        </h3>
                        
                        {{-- Tombol Hapus Semua Riwayat --}}
                        @if(Auth::user()->role === 'petugas' && $peminjamans->where('status', 'dikembalikan')->count() > 0)
                            <button @click="confirmModal = true; confirmMessage = 'Hapus SEMUA data riwayat pengembalian secara permanen?'; confirmAction = '{{ route('peminjamans.destroy', 'all') }}'; confirmMethod = 'DELETE';" 
                                class="text-[10px] bg-red-100 text-red-600 px-3 py-1 rounded-full font-bold hover:bg-red-600 hover:text-white transition shadow-sm">
                                🗑️ CLEAR ALL HISTORY
                            </button>
                        @endif
                    </div>
                    <div class="overflow-x-auto border rounded-xl bg-gray-50/30">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-500 text-[10px] uppercase tracking-wider">
                                    <th class="p-3 text-left">Peminjam</th>
                                    <th class="p-3 text-left">Barang</th>
                                    <th class="p-3 text-center">Tgl Kembali</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @forelse($peminjamans->where('status', 'dikembalikan') as $p)
                                <tr class="border-b hover:bg-white transition">
                                    <td class="p-3 text-gray-500">{{ $p->user->name }}</td>
                                    <td class="p-3 text-gray-400 italic">{{ $p->barang->nama_barang }}</td>
                                    <td class="p-3 text-center font-bold text-blue-600">{{ $p->tanggal_kembali }}</td>
                                    <td class="p-3 text-center">
                                        @if(Auth::user()->role === 'petugas')
                                            <button @click="confirmModal = true; confirmMessage = 'Hapus riwayat ini?'; confirmAction = '{{ route('peminjamans.destroy', $p->id) }}'; confirmMethod = 'DELETE';" 
                                                class="text-gray-400 hover:text-red-600 transition font-bold">Hapus</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="4" class="p-4 text-center text-gray-400 italic">Belum ada riwayat (atau data sudah otomatis diarsipkan).</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">{{ $peminjamans->links() }}</div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="openModal = false"></div>
            <div class="bg-white rounded-xl p-8 z-[110] w-full max-w-md shadow-2xl relative">
                <form action="{{ route('peminjamans.store') }}" method="POST">
                    @csrf
                    <h3 class="text-xl font-black mb-6 text-indigo-800 uppercase flex items-center">
                        <span class="mr-2">📝</span> Form Peminjaman
                    </h3>
                    <div class="space-y-4">
                        @if(Auth::user()->role === 'petugas')
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Peminjam (Siswa)</label>
                            <select name="user_id" required class="w-full border-gray-200 rounded-lg text-sm mt-1 focus:ring-indigo-500">
                                <option value="">Cari Siswa...</option>
                                @foreach($siswas as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                            </select>
                        </div>
                        @endif
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Pilih Barang</label>
                            <select name="barang_id" required class="w-full border-gray-200 rounded-lg text-sm mt-1 focus:ring-indigo-500">
                                <option value="">Pilih...</option>
                                @foreach($barangs as $b) <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok_tersedia }})</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Jumlah</label>
                                <input type="number" name="jumlah" min="1" value="1" required class="w-full border-gray-200 rounded-lg text-sm mt-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Tgl Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="in_pinjam" value="{{ date('Y-m-d') }}" required class="w-full border-gray-200 rounded-lg text-sm mt-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Tgl Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" id="in_tempo" required class="w-full border-indigo-200 bg-indigo-50 rounded-lg text-sm mt-1 font-bold focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Keperluan</label>
                            <textarea name="deskripsi" rows="2" placeholder="Contoh: Praktik Laboratorium" required class="w-full border-gray-200 rounded-lg text-sm mt-1 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-lg font-bold hover:bg-gray-200 transition">BATAL</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold shadow-lg hover:bg-indigo-700 transition">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="openEditModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="openEditModal = false"></div>
            <div class="bg-white rounded-xl p-8 z-[110] w-full max-w-md shadow-2xl relative">
                <form :action="`/peminjamans/${editData.id}`" method="POST">
                    @csrf @method('PUT')
                    <h3 class="text-xl font-black mb-6 text-yellow-600 uppercase">Edit Peminjaman</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Jumlah Pinjam</label>
                            <input type="number" name="jumlah" x-model="editData.jumlah" required class="w-full border-gray-200 rounded-lg text-sm focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Batas Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" x-model="editData.tempo" required class="w-full border-gray-200 rounded-lg text-sm focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Deskripsi</label>
                            <textarea name="deskripsi" x-model="editData.deskripsi" rows="2" required class="w-full border-gray-200 rounded-lg text-sm focus:ring-yellow-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openEditModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-lg font-bold hover:bg-gray-200 transition">BATAL</button>
                        <button type="submit" class="flex-1 bg-yellow-500 text-black py-3 rounded-lg font-bold hover:bg-yellow-600 transition shadow-md">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL KONFIRMASI (GENERAL) - DIPERBAIKI Z-INDEX NYA --}}
        <div x-show="confirmModal" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center px-4">
            {{-- Overlay Gelap --}}
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" @click="confirmModal = false"></div>
            
            {{-- Kotak Modal --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8 z-[1000] w-full max-w-sm text-center relative transform transition-all">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Apakah Anda Yakin?</h3>
                <p class="text-sm text-gray-500 mb-8" x-text="confirmMessage"></p>
                <div class="flex gap-3">
                    <button @click="confirmModal = false" class="flex-1 bg-gray-100 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-200 transition">BATAL</button>
                    <form :action="confirmAction" method="POST" class="flex-1">
                        @csrf
                        <template x-if="confirmMethod === 'DELETE'">
                            <input type="hidden" name="_method" value="DELETE">
                        </template>
                        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 shadow-lg transition">YA, LANJUTKAN</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT OTOMATIS TANGGAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inPinjam = document.getElementById('in_pinjam');
            const inTempo = document.getElementById('in_tempo');

            function updateTempo() {
                if (inPinjam && inPinjam.value) {
                    let date = new Date(inPinjam.value);
                    inTempo.min = inPinjam.value; // Jatuh tempo minimal hari yang sama
                    
                    // Set default jatuh tempo +3 hari dari tanggal pinjam
                    date.setDate(date.getDate() + 3);
                    inTempo.value = date.toISOString().split('T')[0];
                }
            }
            if(inPinjam) inPinjam.addEventListener('change', updateTempo);
            updateTempo();
        });
    </script>
</x-app-layout>