<?php

namespace App\Services;

use Resend\Laravel\Facades\Resend;

class EmailService {


    public function send(string $email): void
    {
        Resend::emails()->send([
            'from' => env('MAIL_FROM_ADDRESS'),
            'to' => $email,
            'subject' => 'Registro de usuario, activar cuenta',
            'html' => '<h1>Test Email</h1>',
        ]);
    }
}