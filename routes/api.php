<?php

use App\Http\Requests\GetTransactionsRequest;
use App\Http\Requests\PostTransactionRequest;
use App\Http\Requests\PutTransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

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

Route::middleware(['auth:api'])->get('/transaction', function (Request $request) {

    $getRequest = new GetTransactionsRequest();


    $validator = Validator::make($request->query(), $getRequest->rules());

    $dateStart = $request->query('from');
    $dateEnd = $request->query('to');

    if ( !$validator->validate() ) {
        $dateStart = \Carbon\Carbon::now()->startOfMonth()->subMonth(6);
        $dateEnd = \Carbon\Carbon::now()->endOfMonth()->addMonth(6);
    }


    $transactions = Transaction::between($dateStart, $dateEnd)->ordered()->get();

    return new TransactionCollection($transactions);

});

Route::middleware(['auth:api'])->get('/transaction/{id}', function(Request $request, $id) {

    $transaction = Transaction::where(['id' => $id])->firstOrFail();

    return new TransactionResource($transaction);

});

Route::middleware(['auth:api'])->put('/transaction/{id}', function(PutTransactionRequest $request, $id) {


    $transaction = Transaction::where(['id' => $id])->firstOrFail();

    $oldRepitingInterval = $transaction->repeating_interval;

    $transactionData = collect($request->json()->get('transaction'));
    $fillables = $transactionData->except(['update_all', 'user_id', 'id', 'repeating_id'])->toArray();

    $transaction->fill($fillables);
    $transaction->save();

    if ( $transactionData['update_all'] == true) {

        $repeatingId = $transaction->repeating_id;

        if ($transactionData['repeating_interval'] != $oldRepitingInterval) {

            Transaction::repeating($repeatingId)->withoutKey($transaction->id)->delete();

            $firstDate = (new Carbon($transaction->planned_on))->addMonth($transaction->repeating_interval)->format('Y-m-d');

            for ($c = 1; $c < 50; $c++) {

                $rTransaction = new Transaction($fillables);
                $rTransaction->planned_on = $firstDate;
                $rTransaction->user_id = $transaction->user_id;
                $rTransaction->repeating_id = $transaction->repeating_id;

                $rTransaction->save();
            }



        } else {
            Transaction::repeating($repeatingId)->update($fillables);
        }



    }

    return new TransactionResource($transaction);


});

Route::middleware(['auth:api'])->post('/transaction', function(PostTransactionRequest $request) {

    $transactionData =
        $request->json()->get('transaction');

    $transaction = new Transaction($transactionData);
    $transaction->user_id = Auth::id();
    $transaction->repeating_id = $transactionData['repeating_interval'] != 0 ? Uuid::uuid4()->toString() : null;


    $transaction->save();

    if ( $transactionData['repeating_interval'] != 0 ) {

        $firstDate = (new Carbon($transaction->planned_on))->addMonth($transactionData['repeating_interval'])->format('Y-m-d');

        for ($c = 1; $c < 50; $c++) {

            $rTransaction = new Transaction($transactionData);
            $rTransaction->planned_on = $firstDate;
            $rTransaction->user_id = $transaction->user_id;
            $rTransaction->repeating_id = $transaction->repeating_id;

            $rTransaction->save();
        }
    }

    return new TransactionResource($transaction);


});