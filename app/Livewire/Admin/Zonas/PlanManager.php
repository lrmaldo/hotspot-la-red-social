<?php

namespace App\Livewire\Admin\Zonas;

use App\Models\Plan;
use App\Models\Zona;
use Livewire\Component;

class PlanManager extends Component
{
    public Zona $zona;
    public $planes;
    public bool $showPlanModal = false;

    // Form fields
    public ?int $planId = null;
    public string $nombre = '';
    public string $descripcion = '';
    public float $precio = 0.00;
    public int $duracion_minutos = 60;
    public bool $is_active = true;

    protected $rules = [
        'nombre' => 'required|string|max:100',
        'descripcion' => 'nullable|string|max:255',
        'precio' => 'required|numeric|min:0',
        'duracion_minutos' => 'required|integer|min:1',
        'is_active' => 'boolean',
    ];

    public function mount(Zona $zona)
    {
        $this->zona = $zona;
        $this->loadPlanes();
    }

    public function loadPlanes()
    {
        $this->planes = $this->zona->planes()->orderBy('precio')->get();
    }

    public function openPlanModal()
    {
        $this->resetForm();
        $this->showPlanModal = true;
    }

    public function createPlan()
    {
        $this->resetForm();
        $this->showPlanModal = true;
    }

    public function editPlan(Plan $plan)
    {
        $this->planId = $plan->id;
        $this->nombre = $plan->nombre;
        $this->descripcion = $plan->descripcion;
        $this->precio = $plan->precio;
        $this->duracion_minutos = $plan->duracion_minutos;
        $this->is_active = $plan->is_active;
        $this->showPlanModal = true;
    }

    public function savePlan()
    {
        $this->validate();

        Plan::updateOrCreate(
            ['id' => $this->planId],
            [
                'zona_id' => $this->zona->id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'duracion_minutos' => $this->duracion_minutos,
                'is_active' => $this->is_active,
            ]
        );

        $this->showPlanModal = false;
        $this->loadPlanes();
        $this->dispatch('planSaved');
    }

    public function deletePlan(Plan $plan)
    {
        $plan->delete();
        $this->loadPlanes();
        $this->dispatch('planDeleted');
    }

    public function resetForm()
    {
        $this->planId = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->precio = 0.00;
        $this->duracion_minutos = 60;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.zonas.plan-manager');
    }
}
