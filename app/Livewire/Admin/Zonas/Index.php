<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Zonas;

use App\Models\Zona;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Gestión de Zonas')]
class Index extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    
    // Form fields
    public ?int $zonaId = null;
    public string $nombre = '';
    public string $id_personalizado = '';
    public ?string $descripcion = null;
    public string $hotspot_host = '';
    public string $tipo_autenticacion = 'pin';
    public bool $venta_vouchers_activa = false;
    public bool $trial_enabled = false;
    public int $trial_duration_seconds = 5;
    public $logo; // New upload
    public ?string $logo_path = null; // Existing path
    public string $color_primario = '#1a56db';
    public string $color_secundario = '#ffffff';
    public ?string $facebook_url = null;
    public ?string $mikrotik_user = null;
    public ?string $mikrotik_password = null;
    public ?int $mikrotik_port = 8728;
    public ?string $mikrotik_hotspot_profile = 'default';
    public bool $is_active = true;

    public function rules(): array
    {
        $zonaActual = $this->zonaId ? Zona::find($this->zonaId) : null;
        $mikrotikPasswordExiste = $zonaActual?->mikrotik_password !== null;
        $debePedirPassword = $this->venta_vouchers_activa && (! $this->zonaId || ! $mikrotikPasswordExiste);

        return [
            'nombre' => 'required|string|max:150',
            'id_personalizado' => 'required|string|max:100|unique:zonas,id_personalizado,' . $this->zonaId,
            'descripcion' => 'nullable|string',
            'hotspot_host' => 'required|string|max:100',
            'tipo_autenticacion' => 'required|in:pin,sin_autenticacion',
            'venta_vouchers_activa' => 'boolean',
            'trial_enabled' => 'boolean',
            'trial_duration_seconds' => 'required|integer|min:0',
            'logo' => 'nullable|image|max:2048', // 2MB Max
            'color_primario' => 'required|string|size:7',
            'color_secundario' => 'required|string|size:7',
            'facebook_url' => 'nullable|url',
            'mikrotik_user' => $this->venta_vouchers_activa ? 'required|string|max:100' : 'nullable|string|max:100',
            'mikrotik_password' => $debePedirPassword ? 'required|string|max:255' : 'nullable|string|max:255',
            'mikrotik_port' => 'nullable|integer|min:1|max:65535',
            'mikrotik_hotspot_profile' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function updatedNombre()
    {
        if (!$this->zonaId && empty($this->id_personalizado)) {
            $this->id_personalizado = Str::slug($this->nombre);
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Zona $zona)
    {
        $this->resetValidation();
        $this->zonaId = $zona->id;
        $this->nombre = $zona->nombre;
        $this->id_personalizado = $zona->id_personalizado;
        $this->descripcion = $zona->descripcion;
        $this->hotspot_host = $zona->hotspot_host;
        $this->tipo_autenticacion = $zona->tipo_autenticacion;
        $this->venta_vouchers_activa = $zona->venta_vouchers_activa;
        $this->trial_enabled = $zona->trial_enabled;
        $this->trial_duration_seconds = $zona->trial_duration_seconds;
        $this->mikrotik_user = $zona->mikrotik_user;
        $this->mikrotik_password = null;
        $this->logo_path = $zona->logo_path;
        $this->logo = null;
        $this->color_primario = $zona->color_primario;
        $this->color_secundario = $zona->color_secundario;
        $this->facebook_url = $zona->facebook_url;
        $this->mikrotik_port = $zona->mikrotik_port ?: 8728;
        $this->mikrotik_hotspot_profile = $zona->mikrotik_hotspot_profile ?: 'default';
        $this->is_active = $zona->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->logo) {
            // Delete old logo if exists
            if ($this->zonaId && $this->logo_path) {
                Storage::disk('public')->delete($this->logo_path);
            }
            $validatedData['logo_path'] = $this->logo->store('zonas', 'public');
        } else {
            $validatedData['logo_path'] = $this->logo_path;
        }

        unset($validatedData['logo']);

        if (empty($validatedData['mikrotik_password'])) {
            unset($validatedData['mikrotik_password']);
        }

        Zona::updateOrCreate(
            ['id' => $this->zonaId],
            $validatedData
        );

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id)
    {
        $zona = Zona::findOrFail($id);
        if ($zona->logo_path) {
            Storage::disk('public')->delete($zona->logo_path);
        }
        $zona->delete();
    }

    public function toggleActive(Zona $zona)
    {
        $zona->update(['is_active' => !$zona->is_active]);
    }

    public function probarConexion(int $zonaId): void
    {
        $zona = Zona::findOrFail($zonaId);
        $ok = (new MikrotikService($zona))->verificarConexion();

        session()->flash(
            $ok ? 'success' : 'error',
            $ok ? 'Conexión exitosa al MikroTik' : 'No se pudo conectar al MikroTik',
        );
    }

    public function resetForm()
    {
        $this->reset([
            'zonaId', 'nombre', 'id_personalizado', 'descripcion',
            'hotspot_host', 'mikrotik_user', 'mikrotik_password',
            'mikrotik_port', 'mikrotik_hotspot_profile',
            'logo', 'logo_path', 'facebook_url',
        ]);
        $this->tipo_autenticacion = 'pin';
        $this->venta_vouchers_activa = false;
        $this->trial_enabled = false;
        $this->trial_duration_seconds = 5;
        $this->color_primario = '#1a56db';
        $this->color_secundario = '#ffffff';
        $this->mikrotik_port = 8728;
        $this->mikrotik_hotspot_profile = 'default';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $zonas = Zona::withCount('campanas')
                    ->orderBy('id', 'desc')
                    ->get();
                    
        return view('livewire.admin.zonas.index', compact('zonas'));
    }
}
