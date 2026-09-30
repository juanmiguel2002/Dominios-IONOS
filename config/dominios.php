<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notificaciones de dominios
    |--------------------------------------------------------------------------
    |
    | Destinatarios de los avisos de renovación y confirmación. El correo del
    | titular del dominio se añade dinámicamente como CC cuando existe.
    |
    */

    'notificaciones' => [
        'to' => env('DOMINIOS_NOTIF_TO', 'web@ivarscomagenciadepublicidad.com'),
        'bcc' => env('DOMINIOS_NOTIF_BCC', 'joseivars@ivarscom.com'),
    ],

];
