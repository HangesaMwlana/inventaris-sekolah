<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Barang') }}
            </h2>
            @if(Auth::user()->role === 'petugas')
                <button onclick="toggleModal('modal-tambah')" class="bg-blue-600 hover:bg-blue-700 text-black px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                    + Tambah Barang
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Fitur Cetak Laporan --}}
            @if(Auth::user()->role !== 'siswa')
            <div class="mb-6 bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                <h3 class="text-sm font-bold text-gray-600 uppercase mb-3">Cetak Laporan Inventaris</h3>
                <form action="{{ route('barangs.cetak') }}" method="GET" target="_blank" class="flex flex-wrap items-end gap-4">
                    <div class="w-full md:w-64">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Filter Status Stok</label>
                        <select name="filter_stok" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Semua Barang</option>
                            <option value="tersedia">Stok Tersedia (> 5)</option>
                            <option value="sedikit">Stok Menipis (1 - 5)</option>
                            <option value="habis">Stok Habis (0)</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-black px-6 py-2 rounded-md text-sm font-bold transition">
                        Preview PDF
                    </button>
                </form>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Cari & Filter --}}
                <div class="mb-4">
                    <form action="{{ route('barangs.index') }}" method="GET" class="flex flex-wrap gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/kode..." class="border-gray-300 rounded-md text-sm md:w-64">
                        <select name="kategori" onchange="this.form.submit()" class="border-gray-300 rounded-md text-sm">
                            <option value="">Semua Kategori</option>
                            <option value="elektronik" {{ request('kategori') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="atk" {{ request('kategori') == 'atk' ? 'selected' : '' }}>ATK</option>
                            <option value="alat_kebersihan" {{ request('kategori') == 'alat_kebersihan' ? 'selected' : '' }}>Alat Kebersihan</option>
                            <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">
                                <th class="px-4 py-3 border">Kode</th>
                                <th class="px-4 py-3 border">Nama Barang</th>
                                <th class="px-4 py-3 border">Kategori</th>
                                <th class="px-4 py-3 border">Deskripsi</th>
                                <th class="px-4 py-3 border text-center">Stok (Tersedia/Total)</th>
                                @if(Auth::user()->role === 'petugas')
                                <th class="px-4 py-3 border text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($barangs as $barang)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-mono text-blue-600 font-bold">{{ $barang->kode_barang }}</td>
                                <td class="p-3">{{ $barang->nama_barang }}</td>
                                <td class="p-3 capitalize">{{ str_replace('_', ' ', $barang->kategori) }}</td>
                                <td class="p-3 italic text-gray-500">{{ Str::limit($barang->deskripsi, 30) ?? '-' }}</td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $barang->stok_tersedia <= 5 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $barang->stok_tersedia }} / {{ $barang->stok_total }}
                                    </span>
                                </td>
                                @if(Auth::user()->role === 'petugas')
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick='openEditModal(@json($barang))' class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-3 py-1 rounded text-xs font-bold transition">
                                            Edit
                                        </button>
                                        <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-black px-3 py-1 rounded text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">Data Kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $barangs->links() }}</div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modal-tambah" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="toggleModal('modal-tambah')"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md z-50 p-6">
                <form action="{{ route('barangs.store') }}" method="POST">
                    @csrf
                    <h3 class="text-lg font-bold mb-4">Tambah Barang</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Nama Barang</label>
                            <input type="text" name="nama_barang" required class="w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kategori</label>
                            <select name="kategori" required class="w-full border-gray-300 rounded-md">
                                <option value="elektronik">Elektronik</option>
                                <option value="atk">ATK</option>
                                <option value="alat_kebersihan">Alat Kebersihan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Stok Awal</label>
                            <input type="number" name="stok_input" required min="0" class="w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Deskripsi</label>
                            <textarea name="deskripsi" class="w-full border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modal-tambah')" class="bg-gray-100 px-4 py-2 rounded-md">Batal</button>
                        <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modal-edit" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="toggleModal('modal-edit')"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md z-50 p-6">
                <form id="form-edit" method="POST">
                    @csrf @method('PUT')
                    <h3 class="text-lg font-bold mb-4">Edit Barang</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Nama Barang</label>
                            <input type="text" name="nama_barang" id="edit-nama" required class="w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kategori</label>
                            <select name="kategori" id="edit-kategori" required class="w-full border-gray-300 rounded-md">
                                <option value="elektronik">Elektronik</option>
                                <option value="atk">ATK</option>
                                <option value="alat_kebersihan">Alat Kebersihan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Stok Total</label>
                            <input type="number" name="stok_total" id="edit-stok" required min="0" class="w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Deskripsi</label>
                            <textarea name="deskripsi" id="edit-deskripsi" class="w-full border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modal-edit')" class="bg-gray-100 px-4 py-2 rounded-md">Batal</button>
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function openEditModal(barang) {
            const form = document.getElementById('form-edit');
            form.action = '/barangs/' + barang.id; 
            
            document.getElementById('edit-nama').value = barang.nama_barang;
            document.getElementById('edit-kategori').value = barang.kategori;
            document.getElementById('edit-stok').value = barang.stok_total;
            document.getElementById('edit-deskripsi').value = barang.deskripsi || '';
            
            toggleModal('modal-edit');
        }
    </script>
</x-app-layout>