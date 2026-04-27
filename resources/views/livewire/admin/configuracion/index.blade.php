<div>
    <div class="mb-6 px-4 sm:px-6 md:px-8">
        <h2 class="text-xl font-bold text-gray-800">Configuración General</h2>
        <p class="mt-1 text-sm text-gray-500">
            Ajustes globales del sistema.
        </p>
    </div>

    <div class="bg-white shadow rounded-lg mx-4 sm:mx-6 md:mx-8 border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8">
            <form wire:submit="save" class="max-w-2xl space-y-6">
                
                <!-- Nombre de la App -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre de la Aplicación</label>
                    <input type="text" wire:model.blur="app_nombre" class="mt-1 block w-full rounded-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                    @error('app_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Email Administrador -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Correo del Administrador (Notificaciones / Soporte)</label>
                    <input type="email" wire:model.blur="admin_email" class="mt-1 block w-full rounded-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                    @error('admin_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Logo de la App -->
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded border border-gray-200">
                    <div class="flex-1 mr-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Principal (Admin Dashboard)</label>
                        <input type="file" wire:model="app_logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        <div wire:loading wire:target="app_logo" class="text-sm text-blue-600 mt-1 animate-pulse">Subiendo imagen...</div>
                        <p class="text-xs text-gray-500 mt-2">Formato PNG, JPG, WEBP. Máx. 2MB. Opcional.</p>
                        @error('app_logo') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="w-16 h-16 sm:w-24 sm:h-24 bg-white border rounded shadow-sm flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if ($app_logo)
                            <img src="{{ $app_logo->temporaryUrl() }}" class="object-contain h-full relative" alt="Preview nuevo logo">
                        @elseif($app_logo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($app_logo_path) }}" class="object-contain h-full relative" alt="Logo actual">
                        @else
                            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-6 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Guardar Configuración
                        <svg wire:loading wire:target="save" class="animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
<script>
    // Listen for the custom event emitted from the Livewire component when saving succeeds
    $wire.on('config-saved', () => {
        window.Swal.fire({
            title: '¡Guardado!',
            text: 'La configuración ha sido actualizada exitosamente.',
            icon: 'success',
            confirmButtonColor: '#3b82f6', // matches blue-500
            timer: 2000,
            timerProgressBar: true
        });
    });
</script>
@endscript
