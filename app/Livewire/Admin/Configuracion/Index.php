<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Configuracion;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Configuración')]
class Index extends Component
{
    use WithFileUploads;

    public string $app_nombre = '';
    public string $admin_email = '';
    public $app_logo; // Uploaded file
    public ?string $app_logo_path = null;

    public function mount()
    {
        $this->app_nombre = (string) Setting::getValue('app_nombre', 'La Red Social');
        $this->admin_email = (string) Setting::getValue('admin_email', 'admin@laredsocial.com');
        $this->app_logo_path = Setting::getValue('app_logo');
    }

    public function rules(): array
    {
        return [
            'app_nombre' => 'required|string|max:100',
            'admin_email' => 'required|email|max:150',
            'app_logo' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function save()
    {
        $this->validate();

        Setting::updateOrCreate(['key' => 'app_nombre'], ['value' => $this->app_nombre]);
        Setting::updateOrCreate(['key' => 'admin_email'], ['value' => $this->admin_email]);

        if ($this->app_logo) {
            // Eliminar logo anterior si existe
            if ($this->app_logo_path) {
                Storage::disk('public')->delete($this->app_logo_path);
            }
            $path = $this->app_logo->store('config', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
            $this->app_logo_path = $path;
            $this->app_logo = null;
        }

        // Emitimos un evento al navegador para mostrar el SweetAlert de éxito
        $this->dispatch('config-saved');
    }

    public function render()
    {
        return view('livewire.admin.configuracion.index');
    }
}
