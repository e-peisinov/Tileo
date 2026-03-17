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
];
