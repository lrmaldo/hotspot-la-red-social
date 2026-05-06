<div class="px-6 py-6 border-t border-gray-100 mt-2">
    <h2 class="text-xl font-bold text-center text-gray-800 mb-4">Acceder a Internet</h2>

    <div class="auth-form" id="user-form">
        <form name="login" action="{{ $link_login_only ?? ('http://'.$zona->hotspot_host.'/login') }}" method="post" class="space-y-4" onSubmit="return doLogin()">
            <input type="hidden" name="dst" value="{{ $link_orig ?? 'http://google.com' }}" />
            <input type="hidden" name="popup" value="true" />

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Usuario / PIN</label>
                <div class="mt-1 relative rounded-md shadow-sm border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:border-primary transition-all">
                    <!-- Icon -->
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    
                    <input type="text" name="username" id="username"
                           class="w-full pl-10 pr-3 py-3 border-none focus:ring-0 text-lg tracking-widest outline-none bg-gray-50 placeholder-gray-300"
                           placeholder="Usuario o PIN" autofocus required>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña (opcional con PIN)</label>
                <div class="mt-1 relative rounded-md shadow-sm border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary focus-within:border-primary transition-all">
                    <!-- Icon -->
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    
                    <input type="password" name="password" id="password"
                           class="w-full pl-10 pr-3 py-3 border-none focus:ring-0 text-lg tracking-widest outline-none bg-gray-50 placeholder-gray-300"
                           placeholder="••••">
                </div>
            </div>

            <button type="submit"
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors cursor-pointer mt-2"
                    onclick="if(!document.getElementById('password').value) document.getElementById('password').value = document.getElementById('username').value;">
                Entrar
            </button>

            @if(!empty($error))
                <div class="text-center text-sm text-red-600 bg-red-50 p-2 rounded-md mt-2 font-medium">
                    {{ $error }}
                </div>
            @endif
        </form>
    </div>

    <p class="mt-4 text-center text-xs text-gray-500">
        Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
    </p>
</div>