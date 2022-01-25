<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->assertSee('Email address')
                    ->type('email', 'admin@admin.com')
                    ->type('password', '1234567')
                    ->click('.btn-block')
                    // ->waitForReload()
                    ->assertSee('Dashboard 4');
        });
    }
}
