<?php

return [
    'otp_code' => 'Código OTP',

    'mail' => [
        'subject' => 'Código OTP',
        'greeting' => 'Hola!',
        'line1' => 'Tu Código OTP es: :code',
        'line2' => 'El código será válido por :seconds segundos.',
        'line3' => 'Si no ha solicitado un código, ignore este correo electrónico.',
        'salutation' => 'Saludos cordiales, :app_name',
    ],

    'view' => [
        'time_left' => 'segundos restantes',
        'resend_code' => 'Reenviar código',
        'verify' => 'Verificar',
        'go_back' => 'Volver',
    ],

    'notifications' => [
        'title' => 'Código OTP enviado',
        'body' => 'El código de verificación ha sido enviado a su dirección de correo electrónico. Será válido en :seconds segundos.'
    ],

    'validation' => [
        'invalid_code' => 'El código que ha introducido no es válido.',
        'expired_code' => 'El código que ha introducido ha expirado.',
    ],
];
