<?php
/**
 * Created by PhpStorm.
 * User: countzero
 * Date: 14/04/2018
 * Time: 21:25.
 */

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;

abstract class CashFlowAppUserLoggedInTestCase extends CashFlowAppTestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /** @var User */
    public $currentUser;

    protected function setUp()
    {
        parent::setUp();

        try {
            $this->browse(function (Browser $browser) {
                $this->currentUser = $this->createUser();
                $browser->resize(1600, 1200);
                $browser->loginAs($this->currentUser);
            });
        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
