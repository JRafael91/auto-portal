<?php

namespace Tests\Browser;

use App\Models\OtpCode;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Keyboard;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('input[type="email"]', "admin@autoportal.store");
            $browser->type('input[type="password"]', "Abc12345-");
            //$browser->press('button[type="submit"]');
            $browser->screenshot('_login_');
            $browser->pressAndWaitFor('button[type="submit"]');

            $otp = OtpCode::query()->where('email', "admin@autoportal.store")->first();

            $value = str_split($otp->code);

            $browser->click('input[x-ref="1"]')
                ->withKeyboard(fn (Keyboard $keyboard) =>
                $keyboard->press($value[0])
                    ->press($value[1])
                    ->press($value[2])
                    ->press($value[3])
                    ->press($value[4])
                    ->press($value[5]));

            $browser->pause(2000);

            $browser->press('button[type="submit"]');

            $browser->waitForLocation('/dashboard');

            $browser->screenshot('dashboard');

            $browser->quit();

        });
    }
}
