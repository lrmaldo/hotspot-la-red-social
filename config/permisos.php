<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Catálogo de permisos del panel
|--------------------------------------------------------------------------
|
| Fuente única de verdad de los permisos. Alimenta el seeder (crea los
| permisos en BD), la pantalla de Perfiles (checkboxes agrupados), el
| sidebar y el dashboard (verificaciones @can).
|
| Cada permiso es un interruptor on/off (acceso a una sección o a un
| widget del dashboard). Agregar uno nuevo aquí y correr el seeder basta
| para que aparezca en la UI de perfiles.
|
*/

return [

    'Dashboard' => [
        'dashboard.vouchers'  => 'Métricas de vouchers',
        'dashboard.ganancias' => 'Gráfica de ganancias mes a mes',
        'dashboard.trafico'   => 'Tráfico promedio por zona',
        'dashboard.resumen'   => 'Resumen de zonas y campañas',
    ],

    'Secciones' => [
        'seccion.zonas'         => 'Zonas',
        'seccion.campanas'      => 'Campañas',
        'seccion.planes'        => 'Planes',
        'seccion.vouchers'      => 'Vouchers',
        'seccion.configuracion' => 'Configuración',
        'seccion.stripe'        => 'Stripe',
        'seccion.usuarios'      => 'Usuarios y perfiles',
    ],

];
