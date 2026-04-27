<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Campanas;

use App\Models\Campana;
use App\Models\Zona;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Gestión de Campañas')]
class Index extends Component
{
    use WithFileUploads;

    #[Url(as: 'zona')]
    public $filterZona = '';

    public bool $showModal = false;

    // Form fields
    public ?int $campanaId = null;
    public ?int $zona_id = null;
    public ?string $titulo = null;
    public string $tipo = 'imagen';
    public $file; // Uploaded file
    public ?string $file_path = null; // Existing file path
    public int $duracion = 8;
    public ?int $skip_after_seconds = null;
    public string $skip_texto = 'Omitir en {s}s';
    public bool $countdown_visible = true;
    public string $countdown_style = 'barra';
    public int $prioridad = 0;
    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'zona_id' => 'required|exists:zonas,id',
            'titulo' => 'nullable|string|max:150',
            'tipo' => 'required|in:imagen,video',
            'file' => $this->campanaId ? 'nullable|file|mimes:jpg,jpeg,png,webp,mp4|max:51200' : 'required|file|mimes:jpg,jpeg,png,webp,mp4|max:51200',
            'duracion' => 'required|integer|min:1|max:255',
            'skip_after_seconds' => 'nullable|integer|min:1|max:255',
            'skip_texto' => 'required|string|max:50',
            'countdown_visible' => 'boolean',
            'countdown_style' => 'required|in:barra,circular',
            'prioridad' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function updatedFile()
    {
        if ($this->file) {
            $mime = $this->file->getMimeType();
            if (str_starts_with($mime ?? '', 'video')) {
                $this->tipo = 'video';
            } else {
                $this->tipo = 'imagen';
            }
        }
    }

    public function create()
    {
        $this->resetForm();
        if ($this->filterZona) {
            $this->zona_id = (int) $this->filterZona;
        }
        $this->showModal = true;
    }

    public function edit(Campana $campana)
    {
        $this->resetValidation();
        $this->campanaId = $campana->id;
        $this->zona_id = $campana->zona_id;
        $this->titulo = $campana->titulo;
        $this->tipo = $campana->tipo;
        $this->file_path = $campana->file_path;
        $this->file = null;
        $this->duracion = $campana->duracion;
        $this->skip_after_seconds = $campana->skip_after_seconds;
        $this->skip_texto = $campana->skip_texto;
        $this->countdown_visible = $campana->countdown_visible;
        $this->countdown_style = $campana->countdown_style;
        $this->prioridad = $campana->prioridad;
        $this->is_active = $campana->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        // Treat empty string as null for nullable int
        if ($this->skip_after_seconds === '') {
            $this->skip_after_seconds = null;
        }

        $validatedData = $this->validate();

        if ($this->file) {
            if ($this->campanaId && $this->file_path && !str_starts_with($this->file_path, 'http')) {
                Storage::disk('public')->delete($this->file_path);
            }
            $validatedData['file_path'] = $this->file->store('campanas/' . $this->zona_id, 'public');
        } else {
            $validatedData['file_path'] = $this->file_path;
        }

        unset($validatedData['file']);

        Campana::updateOrCreate(
            ['id' => $this->campanaId],
            $validatedData
        );

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id)
    {
        $campana = Campana::findOrFail($id);
        if ($campana->file_path && !str_starts_with($campana->file_path, 'http')) {
            Storage::disk('public')->delete($campana->file_path);
        }
        $campana->delete();
    }

    public function toggleActive(Campana $campana)
    {
        $campana->update(['is_active' => !$campana->is_active]);
    }

    public function moveUp(Campana $campana)
    {
        // Decrease priority number to move up visually
        if ($campana->prioridad > 0) {
            $campana->update(['prioridad' => $campana->prioridad - 1]);
        }
    }

    public function moveDown(Campana $campana)
    {
        // Increase priority number
        $campana->update(['prioridad' => $campana->prioridad + 1]);
    }

    public function resetForm()
    {
        $this->reset([
            'campanaId', 'zona_id', 'titulo', 'file', 'file_path', 'skip_after_seconds'
        ]);
        $this->tipo = 'imagen';
        $this->duracion = 8;
        $this->skip_texto = 'Omitir en {s}s';
        $this->countdown_visible = true;
        $this->countdown_style = 'barra';
        $this->prioridad = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $zonas = Zona::orderBy('nombre')->get();
        
        $query = Campana::with('zona')->orderBy('zona_id')->orderBy('prioridad', 'asc');
        
        if ($this->filterZona) {
            $query->where('zona_id', $this->filterZona);
        }
        
        $campanas = $query->get();

        return view('livewire.admin.campanas.index', compact('zonas', 'campanas'));
    }
}
