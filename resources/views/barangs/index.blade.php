<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Barang') }}
            </h2>
            @if(Auth::user()->role === 'petugas')
                <button onclick="toggleModal('modal-tambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                    + Tambah Barang
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="px-4 py-3 border text-center">Kode Barang</th>
                                <th class="px-4 py-3 border">Nama Barang</th>
                                <th class="px-4 py-3 border text-center">Stok</th>
                                <th class="px-4 py-3 border">Deskripsi</th>
                                @if(Auth::user()->role === 'petugas')
                                    <th class="px-4 py-3 border text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($barangs as $barang)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border px-4 py-2 text-center font-mono text-blue-600">{{ $barang->kode_barang }}</td>
                                <td class="border px-4 py-2 font-medium">{{ $barang->nama_barang }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <span class="px-2 py-1 rounded {{ $barang->stok <= 5 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $barang->stok }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2 text-gray-500">{{ $barang->deskripsi ?? '-' }}</td>
                                @if(Auth::user()->role === 'petugas')
                                    <td class="border px-4 py-2 text-center">
                                        <div class="flex justify-center space-x-3">
                                            <button 
                                                onclick="openEditModal({{ $barang->id }}, '{{ $barang->nama_barang }}', {{ $barang->stok }}, '{{ $barang->deskripsi }}')"
                                                class="text-yellow-600 hover:text-yellow-900 font-semibold">
                                                Edit
                                            </button>
                                            
                                            <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500 italic border">Belum ada data barang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'petugas')
    <div id="modal-tambah" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
                <form action="{{ route('barangs.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Tambah Barang</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                                <input type="text" name="nama_barang" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok Awal</label>
                                <input type="number" name="stok" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md sm:ml-3">Simpan</button>
                        <button type="button" onclick="toggleModal('modal-tambah')" class="bg-white border px-4 py-2 rounded-md mt-3 sm:mt-0">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Edit Barang</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                                <input type="text" name="nama_barang" id="edit-nama" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok</label>
                                <input type="number" name="stok" id="edit-stok" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md sm:ml-3">Update</button>
                        <button type="button" onclick="toggleModal('modal-edit')" class="bg-white border px-4 py-2 rounded-md mt-3 sm:mt-0">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function openEditModal(id, nama, stok, deskripsi) {
            // Set action URL form secara dinamis
            const form = document.getElementById('form-edit');
            form.action = `/barangs/${id}`;

            // Isi nilai input modal
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-stok').value = stok;
            document.getElementById('edit-deskripsi').value = deskripsi;

            // Tampilkan modal
            toggleModal('modal-edit');
        }
    </script>
    @endif
</x-app-layout>