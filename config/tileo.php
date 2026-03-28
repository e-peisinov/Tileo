<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Número de WhatsApp del negocio
    |--------------------------------------------------------------------------
    | Formato internacional sin +, sin espacios. Ej: 5492324123456
    | Argentina: 54 + código de área sin 0 + número sin 15
    */
    'whatsapp' => env('TILEO_WHATSAPP', ''),

    /*
    |--------------------------------------------------------------------------
    | Email del administrador para recibir notificaciones de pedidos
    |--------------------------------------------------------------------------
    */
    'email_admin' => env('ADMIN_EMAIL', 'admin@tileo.com'),

    /*
    |--------------------------------------------------------------------------
    | Umbral de stock bajo
    |--------------------------------------------------------------------------
    | Cantidad a partir de la cual se muestra la alerta de "stock bajo" en
    | el catálogo y en el panel de administración.
    */
    'stock_bajo_umbral' => 5,
];
