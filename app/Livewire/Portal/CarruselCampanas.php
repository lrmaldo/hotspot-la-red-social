<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Zona;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
class CarruselCampanas extends Component
{
    public Zona $zona;
    public $campanas = [];
    public int $currentIndex = 0;
    public bool $finished = false;

    public function mount(Zona $zona)
    {
        if (!$zona->is_active) {
            abort(404);
        }

        $this->zona = $zona;
        $this->campanas = $this->zona->campanas()
            ->where('is_active', true)
            ->orderBy('prioridad', 'asc')
            ->get();

        if ($this->campanas->isEmpty()) {
            $this->finished = true;
        }
    }

    #[Title('Bienvenido al Portal')]
    public function render()
    {
        // Provide the variable to the layout
        view()->share('zona', $this->zona);

        return view('livewire.portal.carrusel-campanas');
    }

    public function nextSlide()
    {
        if ($this->currentIndex < count($this->campanas) - 1) {
            $this->currentIndex++;
        } else {
            $this->finished = true;
        }
    }

    public function prevSlide()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }
}
