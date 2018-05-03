<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoginLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Login')
                    ->assertPathIs('/login');
          
        });
      
    }
  
  public function testLoginLogout()
  {

        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->type('email', 'sarah@pixelandhammer.com')
                    ->type('password', 'Greenmonkey717')
                    ->press('Login')
                    ->assertPathIs('/home')
                    ->clickLink('Sarah Kyler')
                    ->clickLink('Logout')
                    ->assertPathIs('/')
                    ->visit('/home')
                    ->assertPathIs('/login');
        }); 
    
  }
}
