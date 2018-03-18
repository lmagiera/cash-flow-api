<?php

use App\Http\Resources\TransactionCollection;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});

Route::middleware('auth:api')->get('/transaction', function (Request $request) {

    $user = Auth::user();

    $transactions = Transaction::find(['user_id' => $user->id]);

    return new TransactionCollection($transactions);

});
