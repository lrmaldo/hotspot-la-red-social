<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Roles;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Perfiles')]
class Index extends Component
{
    /** Roles del sistema: siempre tienen todo, no se editan ni eliminan. */
    private const ROLES_SISTEMA = ['admin', 'super_admin'];

    public bool $showModal = false;
    public bool $showDeleteConfirm = false;

    public ?int $roleId = null;
    public string $nombre = '';
    /** @var array<int, string> nombres de permisos seleccionados */
    public array $permisosSeleccionados = [];
    public ?int $deleteId = null;

    public function rules(): array
    {
        return [
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('roles', 'name')->ignore($this->roleId),
            ],
            'permisosSeleccionados'   => ['array'],
            'permisosSeleccionados.*' => ['string'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $role = Role::with('permissions')->findOrFail($id);

        if (in_array($role->name, self::ROLES_SISTEMA, true)) {
            return; // Los roles del sistema no se editan.
        }

        $this->resetValidation();
        $this->roleId = $role->id;
        $this->nombre = $role->name;
        $this->permisosSeleccionados = $role->permissions->pluck('name')->all();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        // Solo permitir permisos que existan en el catálogo.
        $validos = $this->catalogoNombres();
        $permisos = array_values(array_intersect($data['permisosSeleccionados'], $validos));

        $role = $this->roleId ? Role::findOrFail($this->roleId) : new Role();

        // Nunca renombrar un rol del sistema.
        if ($role->exists && in_array($role->name, self::ROLES_SISTEMA, true)) {
            return;
        }

        $role->name = $data['nombre'];
        $role->guard_name = 'web';
        $role->save();

        $role->syncPermissions($permisos);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $this->roleId ? 'Perfil actualizado.' : 'Perfil creado.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete(): void
    {
        $role = Role::find($this->deleteId);

        if ($role && ! in_array($role->name, self::ROLES_SISTEMA, true)) {
            $role->delete();
            session()->flash('success', 'Perfil eliminado.');
        }

        $this->showDeleteConfirm = false;
        $this->deleteId = null;
    }

    public function resetForm(): void
    {
        $this->reset(['roleId', 'nombre', 'permisosSeleccionados']);
        $this->resetValidation();
    }

    /**
     * @return array<int, string>
     */
    private function catalogoNombres(): array
    {
        return collect(config('permisos', []))
            ->flatMap(fn (array $permisos) => array_keys($permisos))
            ->all();
    }

    public function render()
    {
        $roles = Role::withCount(['permissions', 'users'])
            ->orderBy('name')
            ->get();

        return view('livewire.admin.roles.index', [
            'roles'        => $roles,
            'catalogo'     => config('permisos', []),
            'rolesSistema' => self::ROLES_SISTEMA,
        ]);
    }
}
