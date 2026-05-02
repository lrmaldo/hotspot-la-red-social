<div class="px-6 py-6 border-t border-gray-100 mt-2">
    <h2 class="text-xl font-bold text-center text-gray-800 mb-4">Acceder a Internet</h2>

       @if($this->zona->trial_enabled)
        <div class="mt-4" x-data="{ 
            countdown: @entangle('countdown'),
            init() {
                let timer = setInterval(() => {
                    if (this.countdown > 0) {
                        this.countdown--;
                    } else {
                        clearInterval(timer);
                    }
                }, 1000);
            }
        }">
            <button type="button"
                    wire:click="loginTrial"
                    x-bind:disabled="countdown > 0"
                    x-bind:class="countdown > 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600 cursor-pointer'"
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white transition-colors">
                <span x-show="countdown > 0">Espere <span x-text="countdown"></span>s para conectar gratis</span>
                <span x-show="countdown <= 0">Conectarse a Internet Gratis</span>
                
                <svg wire:loading wire:target="loginTrial" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    @endif

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="pin" class="block text-sm font-medium text-gray-700">Introduce tu PIN de acceso</label>
            <div class="mt-1 relative rounded-md shadow-sm border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:border-primary transition-all">
                <!-- Icon -->
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                
                <input type="text" wire:model="pin" id="pin"
                       class="w-full pl-10 pr-3 py-3 border-none focus:ring-0 text-lg font-bold tracking-widest outline-none bg-gray-50 placeholder-gray-300"
                       placeholder="••••" autofocus required>
            </div>
            
            @error('pin')
                <p class="mt-1 text-sm text-red-600 animate-pulse flex items-center font-medium">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit"
                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors cursor-pointer mt-2">
            Canjear PIN
            <svg wire:loading wire:target="login" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </button>
    </form>

 

    <p class="mt-4 text-center text-xs text-gray-500">
        Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
    </p>
</div>