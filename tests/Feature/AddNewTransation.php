<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class AddNewTransation extends TestCase
{


    public function testLoginUser() {

    }

    public function testCanAddTransation() {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->withSession([])
            ->get("/home")
            ;

        $response->assertSee("Laravel");


    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
