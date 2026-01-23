<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"> Manajemen User </h2>
            <button onclick="toggleModal('modal-tambah-user')" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Tambah User
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">Cek kembali inputan Anda.</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider">
                            <th class="px-4 py-3 border">Nama</th>
                            <th class="px-4 py-3 border">Email</th>
                            <th class="px-4 py-3 border">Role</th>
                            <th class="px-4 py-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $user->name }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2 text-center">
                                <span class="px-2 py-1 rounded text-xs uppercase font-bold 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'petugas' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" class="text-yellow-600 font-bold">Edit</button>
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 font-bold">Hapus</button>
                                    </form>
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

    <div id="modal-tambah-user" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            <div class="bg-white rounded-lg p-6 w-full max-w-md z-50">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <h3 class="text-lg font-bold mb-4">Tambah User Baru</h3>
                    <div class="space-y-3">
                        <input type="text" name="name" placeholder="Nama Lengkap" required class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="email" name="email" placeholder="Email" required class="w-full border-gray-300 rounded-md shadow-sm">
                        <select name="role" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="siswa">Siswa</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                        <input type="password" name="password" placeholder="Password" required class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mt-5 flex justify-end space-x-2">
                        <button type="button" onclick="toggleModal('modal-tambah-user')" class="bg-gray-200 px-4 py-2 rounded-md">Batal</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-edit-user" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            <div class="bg-white rounded-lg p-6 w-full max-w-md z-50">
                <form id="form-edit-user" method="POST">
                    @csrf @method('PUT')
                    <h3 class="text-lg font-bold mb-4">Edit User</h3>
                    <div class="space-y-3">
                        <input type="text" name="name" id="edit-user-name" required class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="email" name="email" id="edit-user-email" required class="w-full border-gray-300 rounded-md shadow-sm">
                        <select name="role" id="edit-user-role" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="siswa">Siswa</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                        <p class="text-xs text-gray-500 italic">Kosongkan password jika tidak ingin mengganti.</p>
                        <input type="password" name="password" placeholder="Password Baru" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mt-5 flex justify-end space-x-2">
                        <button type="button" onclick="toggleModal('modal-edit-user')" class="bg-gray-200 px-4 py-2 rounded-md">Batal</button>
                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function openEditUserModal(id, name, email, role) {
            document.getElementById('form-edit-user').action = '/users/' + id;
            document.getElementById('edit-user-name').value = name;
            document.getElementById('edit-user-email').value = email;
            document.getElementById('edit-user-role').value = role;
            toggleModal('modal-edit-user');
        }
    </script>
</x-app-layout>