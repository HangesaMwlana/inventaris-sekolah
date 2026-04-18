<x-app-layout>

<x-slot name="header">
<div class="flex justify-between items-center">
<h2 class="text-2xl font-bold text-indigo-700">
📦 Daftar Barang Inventaris
</h2>
</div>
</x-slot>


<div class="py-10 bg-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto px-4 space-y-6">

{{-- ALERT --}}
@if(session('success'))
<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">
{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm">
{{ session('error') }}
</div>
@endif


{{-- CETAK LAPORAN --}}
@if(Auth::user()->role !== 'siswa')

<div class="bg-white rounded-xl shadow p-6">

<h3 class="text-sm font-semibold text-indigo-600 uppercase mb-4">
Cetak Laporan Inventaris
</h3>

<form action="{{ route('barangs.cetak') }}" method="GET" target="_blank"
class="flex flex-wrap gap-4 items-end">

<div class="w-full md:w-64">

<label class="text-xs text-gray-500">
Filter Status Stok
</label>

<select name="filter_stok"
class="w-full rounded-lg border-gray-300 text-sm">

<option value="">Semua Barang</option>
<option value="tersedia">Stok Tersedia (>5)</option>
<option value="sedikit">Stok Menipis (1-5)</option>
<option value="habis">Stok Habis</option>

</select>

</div>

<button
type="submit"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">
Download PDF
</button>

</form>

</div>

@endif



{{-- CARD DATA --}}
<div class="bg-white rounded-xl shadow">

<div class="p-6 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">

<form action="{{ route('barangs.index') }}" method="GET"
class="flex flex-wrap gap-3">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="Cari barang..."
class="rounded-lg border-gray-300 text-sm w-60">

<select
name="kategori"
onchange="this.form.submit()"
class="rounded-lg border-gray-300 text-sm">

<option value="">Semua Kategori</option>

<option value="elektronik"
{{ request('kategori')=='elektronik'?'selected':'' }}>
Elektronik
</option>

<option value="atk"
{{ request('kategori')=='atk'?'selected':'' }}>
ATK
</option>

<option value="alat_kebersihan"
{{ request('kategori')=='alat_kebersihan'?'selected':'' }}>
Alat Kebersihan
</option>

<option value="lainnya"
{{ request('kategori')=='lainnya'?'selected':'' }}>
Lainnya
</option>

</select>

<button
type="submit"
class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-lg text-sm">
Cari
</button>

</form>


@if(Auth::user()->role === 'petugas')
<button
onclick="openModal('modal-tambah')"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
+ Tambah Barang
</button>
@endif

</div>


{{-- TABLE --}}
<div class="overflow-x-auto">

<table class="min-w-full text-sm">

<thead class="bg-indigo-50 text-indigo-700 uppercase text-xs">

<tr>

<th class="px-6 py-3 text-left">Kode</th>
<th class="px-6 py-3 text-left">Nama</th>
<th class="px-6 py-3 text-left">Kategori</th>
<th class="px-6 py-3 text-left">Deskripsi</th>
<th class="px-6 py-3 text-center">Stok</th>

@if(Auth::user()->role==='petugas')
<th class="px-6 py-3 text-center">Aksi</th>
@endif

</tr>

</thead>


<tbody class="divide-y">

@forelse($barangs as $barang)

<tr class="hover:bg-gray-50">

<td class="px-6 py-4 font-mono text-indigo-600 font-semibold">
{{ $barang->kode_barang }}
</td>

<td class="px-6 py-4 font-medium">
{{ $barang->nama_barang }}
</td>

<td class="px-6 py-4 capitalize">
{{ str_replace('_',' ',$barang->kategori) }}
</td>

<td class="px-6 py-4 text-gray-500">
{{ Str::limit($barang->deskripsi,40) ?? '-' }}
</td>

<td class="px-6 py-4 text-center">

@if($barang->stok_tersedia==0)

<span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs font-semibold">
Habis
</span>

@elseif($barang->stok_tersedia<=5)

<span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
{{ $barang->stok_tersedia }} / {{ $barang->stok_total }}
</span>

@else

<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
{{ $barang->stok_tersedia }} / {{ $barang->stok_total }}
</span>

@endif

</td>


@if(Auth::user()->role==='petugas')

<td class="px-6 py-4 text-center">

<div class="flex justify-center gap-2">

<button
onclick='openEditModal(@json($barang))'
class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-3 py-1 rounded-lg text-xs font-semibold">
Edit
</button>

<button
onclick="openDeleteModal('{{ $barang->id }}','{{ $barang->nama_barang }}')"
class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-semibold">
Hapus
</button>

</div>

</td>

@endif

</tr>

@empty

<tr>
<td colspan="6" class="text-center py-10 text-gray-500">
Data barang belum ada
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


<div class="p-6 border-t">
{{ $barangs->links() }}
</div>

</div>
</div>
</div>




{{-- MODAL TAMBAH --}}
<div id="modal-tambah"
class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

<div class="absolute inset-0" onclick="closeModal('modal-tambah')"></div>

<div onclick="event.stopPropagation()"
class="relative bg-white rounded-xl shadow-lg w-full max-w-md p-6">

<form action="{{ route('barangs.store') }}" method="POST">

@csrf

<h3 class="text-lg font-semibold mb-4">
Tambah Barang
</h3>

<div class="space-y-4">

<input
type="text"
name="nama_barang"
required
placeholder="Nama Barang"
class="w-full rounded-lg border-gray-300">

<select name="kategori"
required
class="w-full rounded-lg border-gray-300">

<option value="elektronik">Elektronik</option>
<option value="atk">ATK</option>
<option value="alat_kebersihan">Alat Kebersihan</option>
<option value="lainnya">Lainnya</option>

</select>

<input
type="number"
name="stok_input"
required
min="0"
placeholder="Stok Awal"
class="w-full rounded-lg border-gray-300">

<textarea
name="deskripsi"
placeholder="Deskripsi"
class="w-full rounded-lg border-gray-300"></textarea>

</div>

<div class="mt-6 flex justify-end gap-3">

<button
type="button"
onclick="closeModal('modal-tambah')"
class="px-4 py-2 rounded-lg bg-gray-100">
Batal
</button>

<button
type="submit"
class="px-4 py-2 rounded-lg bg-indigo-600 text-white">
Simpan
</button>

</div>

</form>

</div>

</div>

{{-- MODAL EDIT --}}
<div id="modal-edit"
class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

<div class="absolute inset-0" onclick="closeModal('modal-edit')"></div>

<div onclick="event.stopPropagation()"
class="relative bg-white rounded-xl shadow-lg w-full max-w-md p-6">

<form id="form-edit" method="POST">

@csrf
@method('PUT')

<h3 class="text-lg font-semibold mb-4">
Edit Barang
</h3>

<div class="space-y-4">

<div>
<label class="text-sm text-gray-600">Nama Barang</label>
<input
type="text"
name="nama_barang"
id="edit-nama"
required
class="w-full rounded-lg border-gray-300">
</div>

<div>
<label class="text-sm text-gray-600">Kategori</label>
<select
name="kategori"
id="edit-kategori"
required
class="w-full rounded-lg border-gray-300">

<option value="elektronik">Elektronik</option>
<option value="atk">ATK</option>
<option value="alat_kebersihan">Alat Kebersihan</option>
<option value="lainnya">Lainnya</option>

</select>
</div>

<div>
<label class="text-sm text-gray-600">Stok Total</label>
<input
type="number"
name="stok_total"
id="edit-stok"
required
min="0"
class="w-full rounded-lg border-gray-300">
</div>

<div>
<label class="text-sm text-gray-600">Deskripsi</label>
<textarea
name="deskripsi"
id="edit-deskripsi"
class="w-full rounded-lg border-gray-300"></textarea>
</div>

</div>

<div class="mt-6 flex justify-end gap-3">

<button
type="button"
onclick="closeModal('modal-edit')"
class="px-4 py-2 rounded-lg bg-gray-100">
Batal
</button>

<button
type="submit"
class="px-4 py-2 rounded-lg bg-yellow-500 text-white">
Update
</button>

</div>

</form>

</div>

</div>


{{-- MODAL HAPUS --}}
<div id="modal-hapus"
class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

<div class="absolute inset-0" onclick="closeModal('modal-hapus')"></div>

<div onclick="event.stopPropagation()"
class="relative bg-white rounded-xl shadow-lg w-full max-w-sm p-6 text-center">

<div class="w-14 h-14 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
⚠️
</div>

<h3 class="text-lg font-semibold mb-2">
Hapus Barang
</h3>

<p id="hapus-text" class="text-sm text-gray-600 mb-6"></p>

<form id="form-hapus" method="POST">

@csrf
@method('DELETE')

<div class="flex justify-center gap-3">

<button
type="button"
onclick="closeModal('modal-hapus')"
class="px-4 py-2 rounded-lg bg-gray-100">
Batal
</button>

<button
type="submit"
class="px-4 py-2 rounded-lg bg-red-600 text-white">
Hapus
</button>

</div>

</form>

</div>

</div>




<script>

function openModal(id){
document.getElementById(id).classList.remove('hidden')
}

function closeModal(id){
document.getElementById(id).classList.add('hidden')
}

function openEditModal(barang){

const form=document.getElementById('form-edit')

form.action='/barangs/'+barang.id

document.getElementById('edit-nama').value=barang.nama_barang
document.getElementById('edit-kategori').value=barang.kategori
document.getElementById('edit-stok').value=barang.stok_total
document.getElementById('edit-deskripsi').value=barang.deskripsi || ''

openModal('modal-edit')

}

function openDeleteModal(id,nama){

const form=document.getElementById('form-hapus')

form.action='/barangs/'+id

document.getElementById('hapus-text').innerText =
'Barang "'+nama+'" akan dihapus secara permanen.'

openModal('modal-hapus')

}

</script>

</x-app-layout>