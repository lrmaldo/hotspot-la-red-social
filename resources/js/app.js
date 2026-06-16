// Livewire v3 ya incluye y arranca Alpine (config/livewire.php: inject_assets => true).
// NO importar ni arrancar Alpine aquí: cargarlo de nuevo crea dos instancias y
// rompe la reactividad (modales, toggles, @entangle).
// Para registrar plugins/datos de Alpine usa el evento 'alpine:init':
//   document.addEventListener('alpine:init', () => { /* Alpine.data(...) */ });
