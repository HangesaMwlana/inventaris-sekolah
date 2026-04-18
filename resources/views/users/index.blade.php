<x-app-layout>

<x-slot name="header">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
</svg>
</div>

<div>
<h1 class="text-xl font-bold text-gray-800">Manajemen User</h1>
<p class="text-sm text-gray-500">Kelola pengguna sistem</p>
</div>
</div>
</x-slot>


<div class="py-8 bg-gray-50 min-h-screen">

<div class="max-w-7xl mx-auto px-4 space-y-6">


@if(session('success'))
<div class="bg-green-500 text-white p-4 rounded-xl shadow">
{{ session('success') }}
</div>
@endif


<div class="bg-white shadow rounded-2xl p-6">


<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

<div>
<h2 class="text-lg font-semibold text-gray-800">Daftar Pengguna</h2>
<p class="text-sm text-gray-500">{{ $users->count() }} user</p>
</div>

<button
onclick="toggleModal('modalAdd')"
class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
Tambah User
</button>

</div>


<div class="overflow-x-auto">

<table class="min-w-full text-sm">

<thead class="bg-gray-100">

<tr>
<th class="px-4 py-3 text-left">Nama</th>
<th class="px-4 py-3 text-left">Email</th>
<th class="px-4 py-3 text-left">Role</th>
<th class="px-4 py-3 text-center">Aksi</th>
</tr>

</thead>


<tbody class="divide-y">

@foreach($users as $user)

<tr class="hover:bg-gray-50">

<td class="px-4 py-3 font-medium text-gray-800">
{{ $user->name }}
</td>

<td class="px-4 py-3 text-gray-600">
{{ $user->email }}
</td>

<td class="px-4 py-3">

@if($user->role == 'admin')
<span class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full">
Admin
</span>

@elseif($user->role == 'petugas')
<span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
Petugas
</span>

@else
<span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">
Siswa
</span>
@endif

</td>

<td class="px-4 py-3">

<div class="flex gap-2 justify-center">

<button
onclick="openEdit('{{ $user->id }}','{{ $user->name }}','{{ $user->email }}','{{ $user->role }}')"
class="bg-yellow-400 text-white px-3 py-1 rounded-lg text-sm hover:bg-yellow-500">
Edit
</button>

@if($user->id !== auth()->id())

<button
onclick="openDelete('{{ $user->id }}','{{ $user->name }}')"
class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-600">
Hapus
</button>

@endif

</div>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>

</div>


{{-- MODAL TAMBAH --}}
<div id="modalAdd" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">

<div class="bg-white rounded-xl p-6 w-full max-w-md">

<h3 class="text-lg font-semibold mb-4">
Tambah User
</h3>

<form action="{{ route('users.store') }}" method="POST" class="space-y-4">

@csrf

<input name="name" placeholder="Nama" class="w-full border rounded-lg px-3 py-2">

<input name="email" placeholder="Email" class="w-full border rounded-lg px-3 py-2">

<select name="role" class="w-full border rounded-lg px-3 py-2">
<option value="siswa">Siswa</option>
<option value="petugas">Petugas</option>
<option value="admin">Admin</option>
</select>

<input type="password" name="password" placeholder="Password" class="w-full border rounded-lg px-3 py-2">

<input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border rounded-lg px-3 py-2">


<div class="flex justify-end gap-2 pt-3">

<button type="button" onclick="toggleModal('modalAdd')" class="px-4 py-2 bg-gray-300 rounded-lg">
Batal
</button>

<button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
Simpan
</button>

</div>

</form>

</div>

</div>



{{-- MODAL EDIT --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">

<div class="bg-white rounded-xl p-6 w-full max-w-md">

<h3 class="text-lg font-semibold mb-4">
Edit User
</h3>

<form id="editForm" method="POST" class="space-y-4">

@csrf
@method('PUT')

<input id="editName" name="name" class="w-full border rounded-lg px-3 py-2">

<input id="editEmail" name="email" class="w-full border rounded-lg px-3 py-2">

<select id="editRole" name="role" class="w-full border rounded-lg px-3 py-2">
<option value="siswa">Siswa</option>
<option value="petugas">Petugas</option>
<option value="admin">Admin</option>
</select>

{{-- 🔥 FIX UTAMA DISINI --}}
<input type="password" name="password" placeholder="Password baru (opsional)" class="w-full border rounded-lg px-3 py-2">

<input type="password" name="password_confirmation" placeholder="Konfirmasi password" class="w-full border rounded-lg px-3 py-2">

<div class="flex justify-end gap-2 pt-3">

<button type="button" onclick="toggleModal('modalEdit')" class="px-4 py-2 bg-gray-300 rounded-lg">
Batal
</button>

<button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
Update
</button>

</div>

</form>

</div>

</div>



{{-- MODAL DELETE --}}
<div id="modalDelete" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">

<div class="bg-white rounded-xl p-6 w-full max-w-sm text-center">

<h3 class="text-lg font-semibold mb-3">
Hapus User
</h3>

<p id="deleteText" class="text-gray-600 mb-5"></p>

<form id="deleteForm" method="POST">

@csrf
@method('DELETE')

<div class="flex justify-center gap-3">

<button type="button" onclick="toggleModal('modalDelete')" class="px-4 py-2 bg-gray-300 rounded-lg">
Batal
</button>

<button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">
Hapus
</button>

</div>

</form>

</div>

</div>



<script>

function toggleModal(id){
document.getElementById(id).classList.toggle("hidden")
}

function openEdit(id,name,email,role){
document.getElementById("editName").value=name
document.getElementById("editEmail").value=email
document.getElementById("editRole").value=role
document.getElementById("editForm").action="/users/"+id
toggleModal("modalEdit")
}

function openDelete(id,name){
document.getElementById("deleteText").innerText="Yakin ingin menghapus "+name+" ?"
document.getElementById("deleteForm").action="/users/"+id
toggleModal("modalDelete")
}

</script>

</x-app-layout>