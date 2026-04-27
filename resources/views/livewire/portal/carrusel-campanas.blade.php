<div class="w-full relative min-h-[80vh] flex flex-col items-center justify-center p-4">

    @if($finished)
        <!-- FINISHED CAROUSEL: SHOW LOGIN -->
        <div class="w-full max-w-sm mx-auto animate-fade-in">
            @if($zona->venta_vouchers_activa)
                <!-- Phase 2 Stub -->
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 mb-6 text-center">
                    <p class="font-semibold text-sm mb-2">Planes de Internet (Fase 2)</p>
                    <p class="text-xs">Usa tu Pin si ya tienes cuenta, o compra un voucher abajo.</p>
                </div>
            @endif

            <livewire:portal.pin-login :zona="$zona" />
        </div>

    @else
        <!-- CAROUSEL SLIDE -->
        @php
            $campana = $campanas[$currentIndex];
            $hasSkip = !is_null($campana->skip_after_seconds);
            $skipSecs = $campana->skip_after_seconds ?? 0;
            $duration = $campana->duracion ?? 8;
        @endphp

        <!-- Use wire:key strictly on the root element of this condition to force Alpine re-init per slide -->
        <div wire:key="slide-{{ $campana->id }}" 
             class="w-full h-full flex flex-col items-center justify-center relative bg-black rounded-xl overflow-hidden aspect-[4/3] md:aspect-video"
             x-data="{
                type: '{{ $campana->tipo }}',
                duration: {{ $duration }},
                timeLeft: {{ $duration }},
                skipSecs: {{ $skipSecs }},
                hasSkip: {{ $hasSkip ? 'true' : 'false' }},
                skipDisabled: true,
                skipTextOrig: '{{ $campana->skip_texto }}',
                skipTextRender: '',
                videoMuted: true,
                timer: null,

                init() {
                    if (this.hasSkip) {
                        this.updateSkipText();
                    }
                    this.startTimer();
                },

                startTimer() {
                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        if (this.timeLeft > 0) this.timeLeft--;

                        if (this.hasSkip) {
                            if (this.skipSecs > 0) this.skipSecs--;
                            if (this.skipSecs <= 0) {
                                this.skipDisabled = false;
                                this.skipTextRender = 'Omitir →';
                            } else {
                                this.updateSkipText();
                            }
                        }

                        if (this.timeLeft <= 0 && this.type === 'imagen') {
                            this.autoAdvance();
                        }
                    }, 1000);
                },

                updateSkipText() {
                    this.skipTextRender = this.skipTextOrig.replace('{s}', this.skipSecs);
                },

                autoAdvance() {
                    clearInterval(this.timer);
                    setTimeout(() => $wire.nextSlide(), 100);
                },

                onVideoEnded() {
                    this.autoAdvance();
                },

                toggleMute(el) {
                    this.videoMuted = !this.videoMuted;
                    if(el) el.muted = this.videoMuted;
                }
             }">

            <!-- Visual Content -->
            @if($campana->tipo === 'imagen')
                <img src="{{ str_starts_with($campana->file_path, 'http') ? $campana->file_path : \Illuminate\Support\Facades\Storage::url($campana->file_path) }}" 
                     class="w-full h-full object-cover object-center absolute inset-0 z-0">
            @elseif($campana->tipo === 'video')
                <video src="{{ str_starts_with($campana->file_path, 'http') ? $campana->file_path : \Illuminate\Support\Facades\Storage::url($campana->file_path) }}" 
                       class="w-full h-full object-cover object-center absolute inset-0 z-0" 
                       x-ref="vid"
                       autoplay muted playsinline :muted="videoMuted" @ended="onVideoEnded()">
                </video>

                <!-- Unmute Button top-right -->
                <button @click="toggleMute($refs.vid)" class="absolute top-4 right-4 z-20 bg-black/50 text-white p-2 rounded-full backdrop-blur hover:bg-black/70 transition">
                    <!-- Icon switches based on muted state -->
                    <template x-if="videoMuted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z m9.172-5a3 3 0 010 6A3 3 0 0014 10.586m3.844-3.844A8 8 0 0122 12a8 8 0 01-2.922 6.078M18 10v4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                    </template>
                    <template x-if="!videoMuted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                    </template>
                </button>

                <!-- Skip Button bottom-right for video -->
                <template x-if="hasSkip">
                    <button :disabled="skipDisabled" 
                            @click="if(!skipDisabled) autoAdvance()"
                            class="absolute bottom-4 right-4 z-20 px-4 py-2 rounded-full text-sm font-semibold backdrop-blur transition-colors disabled:bg-black/40 disabled:text-gray-300 disabled:cursor-not-allowed bg-white/90 text-black hover:bg-white disabled:hover:bg-black/40">
                        <span x-text="skipTextRender"></span>
                    </button>
                </template>
            @endif

            <!-- Overlays & Controls for Image (or countdown overlay for video) -->
            <div class="absolute inset-0 z-10 flex flex-col justify-between pointer-events-none p-4">
                
                <!-- Top Row (Title / Countdown) -->
                <div class="flex justify-between items-start w-full">
                    <h2 class="text-white font-bold drop-shadow-md bg-black/30 px-3 py-1 rounded-md text-sm md:text-base pointer-events-auto">
                        {{ $campana->titulo }}
                    </h2>

                    @if($campana->countdown_visible)
                        @if($campana->countdown_style === 'barra')
                            <!-- progress bar indicator overlay top -->
                            <div class="w-24 h-2 bg-white/30 rounded-full overflow-hidden backdrop-blur pointer-events-auto shadow-sm">
                                <div class="h-full bg-white transition-all duration-1000 ease-linear" 
                                     :style="`width: ${(timeLeft / duration) * 100}%`"></div>
                            </div>
                        @else
                            <!-- circular indicator -->
                            <div class="w-10 h-10 rounded-full bg-black/40 backdrop-blur flex items-center justify-center pointer-events-auto shadow-sm">
                                <span class="text-white text-sm font-bold" x-text="timeLeft"></span>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Middle (Nav Arrows) -->
                <div class="w-full flex justify-between pointer-events-auto">
                    <button wire:click="prevSlide" class="bg-black/30 text-white rounded-full p-2 hover:bg-black/60 transition backdrop-blur {{ $currentIndex === 0 ? 'opacity-0 cursor-default' : '' }}" {{ $currentIndex === 0 ? 'disabled' : '' }}>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    <button wire:click="nextSlide" class="bg-black/30 text-white rounded-full p-2 hover:bg-black/60 transition backdrop-blur">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
                
                <!-- Bottom spacing (leave empty so nav arrows are centered) -->
                <div></div>

            </div>
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
