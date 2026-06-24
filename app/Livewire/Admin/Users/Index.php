<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Usuarios')]
class Index extends Component
{
    public bool $showModal = false;
    public bool $showDeleteConfirm = false;

    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $perfil = ''; // nombre del rol asignado ('' = sin perfil)
    public ?int $deleteId = null;

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'perfil'   => 'nullable|string|exists:roles,name',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        $this->resetValidation();
        $this->userId   = $user->id;
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->perfil = (string) ($user->roles->first()?->name ?? '');
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        $payload = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $payload);

        $user->syncRoles(! empty($data['perfil']) ? [$data['perfil']] : []);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $this->userId ? 'Usuario actualizado.' : 'Usuario creado.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete(): void
    {
        $user = User::find($this->deleteId);

        if ($user && !$user->is_protected) {
            $user->delete();
            session()->flash('success', 'Usuario eliminado.');
        }

        $this->showDeleteConfirm = false;
        $this->deleteId = null;
    }

    public function resetForm(): void
    {
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'perfil']);
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::visible()
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->get();

        $perfiles = Role::orderBy('name')->pluck('name');

        return view('livewire.admin.users.index', compact('users', 'perfiles'));
    }
}
