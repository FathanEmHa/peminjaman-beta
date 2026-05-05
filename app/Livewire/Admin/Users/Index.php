<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    public $name, $email, $password, $role;
    public $userId;
    public $isEdit = false;

    public function render()
    {
        $users = User::latest()->get();
        return view('livewire.admin.users.index', compact('users'))
            ->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,petugas,peminjam'
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role
        ]);

        $this->resetFields();
        session()->flash('message', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        // Password sengaja dikosongkan, diisi cuma kalau mau diganti
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:admin,petugas,peminjam'
        ]);

        $user = User::findOrFail($this->userId);
        
        $dataToUpdate = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role
        ];

        // Update password hanya jika diisi
        if (!empty($this->password)) {
            $dataToUpdate['password'] = Hash::make($this->password);
        }

        $user->update($dataToUpdate);

        $this->resetFields();
        session()->flash('message', 'Data pengguna berhasil diupdate.');
    }

    public function delete($id)
    {
        // Cegah admin menghapus dirinya sendiri
        if ($id == auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId', 'isEdit']);
        $this->resetErrorBag();
    }
}