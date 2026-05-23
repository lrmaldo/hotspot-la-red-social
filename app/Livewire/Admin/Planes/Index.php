<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Planes;

use App\Models\Plan;
use App\Models\Zona;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Gestión de Planes')]
class Index extends Component
{
    #[Url(as: 'zona')]
    public string $zonaFiltro = '';

    public bool $showModal = false;

    // Form fields
    public ?int $planId = null;
    public ?int $zona_id = null;
    public string $nombre = '';
    public ?string $descripcion = null;
    public int $duracion_minutos = 60;
    public string $precio = '0.00';
    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'zona_id'          => 'required|exists:zonas,id',
            'nombre'           => 'required|string|max:100',
            'descripcion'      => 'nullable|string|max:255',
            'duracion_minutos' => 'required|integer|min:1',
            'precio'           => 'required|numeric|min:0.01',
            'is_active'        => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        if ($this->zonaFiltro) {
            $this->zona_id = (int) $this->zonaFiltro;
        }
        $this->showModal = true;
    }

    public function edit(Plan $plan): void
    {
        $this->resetValidation();
        $this->planId = $plan->id;
        $this->zona_id = $plan->zona_id;
        $this->nombre = $plan->nombre;
        $this->descripcion = $plan->descripcion;
        $this->duracion_minutos = $plan->duracion_minutos;
        $this->precio = (string) $plan->precio;
        $this->is_active = $plan->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        Plan::updateOrCreate(
            ['id' => $this->planId],
            $validatedData,
        );

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Plan::findOrFail($id)->delete();
    }

    public function toggleActive(Plan $plan): void
    {
        $plan->update(['is_active' => ! $plan->is_active]);
    }

    public function resetForm(): void
    {
        $this->reset(['planId', 'zona_id', 'nombre', 'descripcion']);
        $this->duracion_minutos = 60;
        $this->precio = '0.00';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $zonas = Zona::orderBy('nombre')->get();

        $query = Plan::with('zona')
            ->withCount(['vouchers as vouchers_vendidos_count' => function ($q) {
                $q->where('estado', 'vendido');
            }])
            ->orderBy('zona_id')
            ->orderBy('precio');

        if ($this->zonaFiltro) {
            $query->where('zona_id', $this->zonaFiltro);
        }

        $planes = $query->get();

        return view('livewire.admin.planes.index', compact('zonas', 'planes'));
    }
}
