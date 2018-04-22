<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetTransactionsRequest;
use App\Http\Requests\PostTransactionRequest;
use App\Http\Requests\PutTransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TransactionController extends Controller
{

    /**
     * TransactionController constructor.
     */
    public function __construct()
    {

        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TransactionCollection
     */
    public function index(Request $request)
    {
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostTransactionRequest $request
     * @return TransactionResource
     */
    public function store(PostTransactionRequest $request)
    {
        $transactionData =
            $request->json()->get('transaction');

        $transaction = new Transaction($transactionData);
        $transaction->save();

        if ($transactionData['repeating_interval'] > 0) {
            $transaction->saveRepeating();
        }


        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction $transaction
     * @return TransactionResource
     */
    public function show(Transaction $transaction)
    {

        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PutTransactionRequest $request
     * @param  \App\Transaction $transaction
     * @return TransactionResource
     */
    public function update(PutTransactionRequest $request, Transaction $transaction)
    {
        $oldPlannedOn = $transaction->planned_on;

        $oldRepitingInterval = $transaction->repeating_interval;

        $transactionData = collect($request->json()->get('transaction'));
        $fillables = $transactionData->except(['update_all', 'user_id', 'id', 'repeating_id'])->toArray();

        $transaction->fill($fillables);
        $transaction->save();

        if ( $transactionData['update_all'] == true) {

            $repeatingId = $transaction->repeating_id;
            $repeatingInterval = $transactionData['repeating_interval'];

            if ($repeatingInterval != $oldRepitingInterval && $repeatingInterval != 0) {

                Transaction::repeating($repeatingId, $oldPlannedOn)->withoutKey($transaction->id)->delete();

                $transaction->saveRepeating();

            } else if ($repeatingInterval == 0)  {

                Transaction::repeating($repeatingId, $oldPlannedOn)->withoutKey($transaction->id)->delete();

            } else {

                // means we have repeating interval
                // if dates are different we should re-set all the stuff, if not, just update.
                if ($oldPlannedOn == $transactionData['planned_on']) {

                    $execptDate = collect($fillables)->except('planned_on')->toArray();
                    Transaction::repeating($repeatingId, $oldPlannedOn)->update($execptDate);


                } else {


                    Transaction::repeating($repeatingId, $oldPlannedOn)->withoutKey($transaction->id)->delete();
                    $transaction->saveRepeating();


                }
            }
        }

        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \App\Transaction $transaction
     * @return TransactionResource
     * @throws \Exception
     */
    public function destroy(Request $request, Transaction $transaction)
    {


        if ( $request->has('all') ) {
            $transaction->repeating($transaction->repeating_id, $transaction->planned_on)->delete();
            return new TransactionResource($transaction);
        }

        if ( !$transaction->exists ) {
            throw new NotFoundHttpException("Transaction not found");
        }

        $transaction->delete();

        return new TransactionResource($transaction);

    }
}
