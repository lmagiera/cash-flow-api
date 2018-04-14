<?php
/**
 * Created by PhpStorm.
 * User: countzero
 * Date: 14/04/2018
 * Time: 19:51
 */

namespace Tests;
use App\Transaction;
use App\User;

/**
 * Class CashFlowAppTestCase
 *
 * Base Class for all Cash Flow App Dusk Test Cases
 *
 * @package Tests\Browser
 */
abstract class CashFlowAppTestCase extends DuskTestCase
{

    /**
     * Creates new user for application,
     * and saves it in database
     *
     * @param array $attributes User attributes
     * @return User
     */
    public function createUser(array $attributes = []) {
        return factory(User::class)->create($attributes);
    }


    /**
     * Create new instance of User class, without saving it in database
     *
     * @param array $attributes
     * @return User
     */
    public function makeUser(array $attributes = []) {
        return factory(User::class)->make($attributes);
    }

    /**
     * Creates new transaction in database for given User
     *
     * @param User $user
     * @param array $attributes
     * @return Transaction
     */
    public function createTransaction(User $user, array $attributes = []) {

        if ( $user->id == null) {
            throw new \InvalidArgumentException("User not saved in DB? User::id is null");
        }

        $attributes = array_merge($attributes, ['user_id' => $user->id]);

        return factory(Transaction::class)->create($attributes);

    }

    /**
     * Creates new instance of Transaction class with given attributes
     *
     * @param array $attributes
     * @return mixed
     */
    public function makeTransaction(array $attributes = []) {

        return factory(Transaction::class)->make($attributes);

    }


}