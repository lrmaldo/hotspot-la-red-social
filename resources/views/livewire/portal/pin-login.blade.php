<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
    <div class="h-32 bg-primary flex items-center justify-center relative">
        @if($zona->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($zona->logo_path) }}" alt="{{ $zona->nombre }}" class="h-16 object-contain z-10 drop-shadow-md">
        @else
            <!-- Placeholder icon -->
            <svg class="w-16 h-16 text-white drop-shadow-md z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
            </svg>
        @endif
        
        <!-- Decoration curve -->
        <div class="absolute -bottom-6 w-full">
            <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="#ffffff" fill-opacity="1" d="M0,96L120,122.7C240,149,480,203,720,202.7C960,203,1200,149,1320,122.7L1440,96L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
        </div>
    </div>

    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Acceder a Internet</h2>

        <form wire:submit="login" class="space-y-5">
            <div>
                <label for="pin" class="block text-sm font-medium text-gray-700">Introduce tu PIN de acceso</label>
                <div class="mt-1 relative rounded-md shadow-sm border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:border-primary transition-all">
                    <!-- Icon Left -->
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    
                    <input type="text" wire:model="pin" id="pin"
                           class="w-full pl-10 pr-3 py-3 border-none focus:ring-0 text-lg font-bold tracking-widest outline-none bg-gray-50 placeholder-gray-300"
                           placeholder="••••" autofocus required>
                </div>
                
                @error('pin')
                    <p class="mt-2 text-sm text-red-600 animate-pulse flex items-center font-medium">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors cursor-pointer">
                Conectar a Internet
                <!-- Loading spinner -->
                <svg wire:loading wire:target="login" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <!-- Terms & Conditions note -->
        <p class="mt-6 text-center text-xs text-gray-500">
            Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
        </p>
    </div>
</div>
