<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">Kelola Pengguna</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 font-bold p-3 rounded mb-4">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 text-red-800 font-bold p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-300 mb-6">
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full text-black border-gray-400 rounded-md shadow-sm focus:border-black focus:ring-black">
                        @error('name') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-1">Email</label>
                        <input type="email" wire:model="email" class="w-full text-black border-gray-400 rounded-md shadow-sm focus:border-black focus:ring-black">
                        @error('email') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-1">Role</label>
                        <select wire:model="role" class="w-full text-black border-gray-400 rounded-md shadow-sm focus:border-black focus:ring-black">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="peminjam">Peminjam</option>
                        </select>
                        @error('role') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-1">Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full text-black border-gray-400 rounded-md shadow-sm focus:border-black focus:ring-black">
                        @error('password') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <button type="submit" class="bg-black text-white font-bold py-2 px-4 rounded hover:bg-gray-800 border border-black">
                    {{ $isEdit ? 'Update Pengguna' : 'Simpan Pengguna' }}
                </button>
                @if($isEdit)
                    <button type="button" wire:click="resetFields" class="bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600 border border-gray-600 ml-2">Batal</button>
                @endif
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-300 overflow-x-auto">
            <table class="w-full text-black">
                <thead>
                    <tr class="border-b border-gray-400 text-left bg-gray-100">
                        <th class="px-4 py-3 font-bold text-gray-900">Nama</th>
                        <th class="px-4 py-3 font-bold text-gray-900">Email</th>
                        <th class="px-4 py-3 font-bold text-gray-900">Role</th>
                        <th class="px-4 py-3 font-bold text-gray-900 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3 uppercase font-bold text-xs">{{ $user->role }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="edit({{ $user->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-3 py-1 rounded text-sm">Edit</button>
                            <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin hapus akun ini?" class="bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1 rounded text-sm ml-2">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>