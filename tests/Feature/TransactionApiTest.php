<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{

    use RefreshDatabase;

    public function testTransactionWithGuard() {

        $intRandomNoOfTransactions = rand(10,20);

        // create user first
        $user = factory(User::class, 1)
            ->create()
            ->each(function (User $u) use ($intRandomNoOfTransactions) {

                $transactions = factory(Transaction::class, $intRandomNoOfTransactions)->create(['user_id' => $u->id]);
                $u->transactions()->saveMany($transactions);

        })->first();

        $response = $this->actingAs($user, 'api')
            ->withSession([])
            ->json('GET', '/api/transaction');


        $response->assertStatus(200);

        $response->assertJsonCount($intRandomNoOfTransactions, 'data');



    }
}
