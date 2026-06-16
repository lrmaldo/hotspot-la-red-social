{{--
    Safelist de clases que el panel arma dentro de expresiones Blade
    {{ ... ? '...' : '...' }} (toggles, navegación activa). El extractor de
    Tailwind no detecta esas clases, así que las declaramos aquí como literales
    para forzar su generación. Esta vista NO se renderiza en ningún lado.
--}}
@php return; @endphp
<div class="hidden
    bg-blue-600 bg-gray-50 bg-gray-100 bg-gray-300
    bg-green-50 bg-green-500 bg-green-600
    border-gray-200 border-green-200
    font-semibold
    text-blue-600 text-blue-700 text-gray-500 text-gray-600 text-green-700
    transform translate-x-0 translate-x-4 translate-x-5"></div>
