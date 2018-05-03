<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testRegistrationLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Register')
                    ->assertPathIs('/register');
          
        });
      
    }
  
  public function testRegisterForm()
  {

        $this->browse(function ($browser) {
            $browser->visit('/register')
                    ->press('Register')
                    ->assertPathIs('//register')
              
                    ->type('name', 'Test')
                    ->press('Register')
                    ->assertPathIs('//register')
              
                    ->type('email', 'bogus@email.com')
                    ->press('Register')
                    ->assertPathIs('//register')
              
                    ->type('password', 'Greenmonkey717')
                    ->press('Register')
                    ->assertPathIs('//register')
              
                    ->type('password_confirmation', 'Greenmonkey717')
                    ->press('Register')
                    ->assertPathIs('/home');
        }); 
    
  }
}
