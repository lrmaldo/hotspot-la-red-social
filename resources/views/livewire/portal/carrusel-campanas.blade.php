<div>
    <!-- Optional Custom Banner/Header if required -->
    {{-- <div class="bg-primary text-white text-center py-2 font-semibold shadow-sm text-sm">
        {{ $zona->nombre }} - Publicidad
    </div> --}}

    @if(count($campanas) > 0)
        <!-- AUTOMATIC CAROUSEL (ALPINE.JS) -->
        <div x-data="{
                activeSlide: 0,
                slides: {{ count($campanas) }},
                timer: null,
                init() {
                    if (this.slides > 1) {
                        this.startTimer();
                    }
                },
                startTimer() {
                    this.timer = setInterval(() => this.next(), 6000);
                },
                resetTimer() {
                    clearInterval(this.timer);
                    this.startTimer();
                },
                next() {
                    this.activeSlide = (this.activeSlide + 1) % this.slides;
                },
                prev() {
                    this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides;
                }
            }" 
            class="relative w-full aspect-video bg-black overflow-hidden object-contain shadow-inner">
            
            @foreach($campanas as $index => $campana)
                @php
                    $path = str_starts_with($campana->file_path, 'http') ? $campana->file_path : \Illuminate\Support\Facades\Storage::url($campana->file_path);
                @endphp
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition ease-in-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in-out duration-700 absolute inset-0"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="w-full h-full">
                    
                    @if($campana->tipo === 'video')
                        <!-- Videos autoplay perfectly because they are handled internally by browser when displayed -->
                        <video src="{{ $path }}" autoplay muted loop class="w-full h-full object-cover" alt="{{ $campana->titulo }}"></video>
                    @else
                        <img src="{{ $path }}" class="w-full h-full object-cover" alt="{{ $campana->titulo }}">
                    @endif
                    
                    <!-- Title Overlay -->
                    <div class="absolute top-0 left-0 w-full p-2 bg-gradient-to-b from-black/60 to-transparent text-white text-sm font-semibold pointer-events-none text-center drop-shadow-md">
                        {{ $campana->titulo }}
                    </div>
                </div>
            @endforeach

            <!-- Indicators bottom -->
            @if(count($campanas) > 1)
            <div class="absolute bottom-3 left-0 w-full flex justify-center space-x-2 z-10">
                @foreach($campanas as $index => $campana)
                    <button @click="activeSlide = {{ $index }}; resetTimer();" 
                            :class="{'bg-primary w-4': activeSlide === {{ $index }}, 'bg-white/60 w-2': activeSlide !== {{ $index }}}" 
                            class="h-2 rounded-full transition-all duration-300 shadow"></button>
                @endforeach
            </div>
            @endif

            <!-- Navigation Arrows (Optional, un-comment if needed later) -->
            {{-- 
            <button @click="prev(); resetTimer();" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black/30 text-white rounded-full p-1 border border-white/20 hover:bg-black/60 transition shadow backdrop-blur-sm z-10"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
            <button @click="next(); resetTimer();" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black/30 text-white rounded-full p-1 border border-white/20 hover:bg-black/60 transition shadow backdrop-blur-sm z-10"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            --}}
        </div>
    @endif

    <!-- FORMULARIO DE LOGGEO (SIEMPRE VISIBLE ABAJO) -->
    <livewire:portal.pin-login 
        :zona="$zona"
        :mac="$mac"
        :ip="$ip"
        :username="$username"
        :link-login="$link_login"
        :link-orig="$link_orig"
        :error="$error"
        :chap-id="$chap_id"
        :chap-challenge="$chap_challenge"
        :link-login-only="$link_login_only"
        :link-orig-esc="$link_orig_esc"
        :mac-esc="$mac_esc"
    />

    @if($zona->venta_vouchers_activa)
        <!-- Phase 2 Stub (if Vouchers mode active on zone) -->
        <div class="bg-yellow-50 border-t border-yellow-200 text-yellow-800 rounded-b-2xl p-4 text-center">
            <p class="font-semibold text-sm">Planes de Internet Adicionales</p>
            <p class="text-xs">Este módulo de compra y venta estará disponible próximamente.</p>
        </div>
    @endif

    <!-- Facebook Floating Button (Visible across entire portal if set) -->
    @if($zona->facebook_url)
    <a href="{{ $zona->facebook_url }}" target="_blank" class="fixed bottom-6 right-6 bg-[#1877F2] text-white p-4 rounded-full shadow-xl hover:bg-[#166fe5] transition-transform hover:scale-105 flex items-center justify-center z-50">
        <svg class="w-6 h-6 fill-current mr-2" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V7.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
        <span class="font-bold text-sm">Visítanos en Facebook &rarr;</span>
    </a>
    @endif
</div>