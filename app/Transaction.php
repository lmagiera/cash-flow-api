<?php

namespace App;

use App\Scopes\CurrentUserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //



    protected $fillable = [
        'description',
        'amount',
        'planned_on',
        'repeating_id',
        'repeating_interval'
    ];





    public function user() {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CurrentUserScope());
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return void
     */
    public function scopeBetween($query, $dateFrom, $dateTo) {

        $query->whereBetween('planned_on', [$dateFrom, $dateTo]);

    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrdered($query) {

        $query->orderBy('planned_on', 'ASC');

    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $keyId
     */
    public function scopeById($query, $keyId) {

        $query->whereKey($keyId);

    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $keyId
     */
    public function scopeWithoutKey($query, $keyId) {

        $query->whereKeyNot($keyId);

    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $repeating_id
     * @param $planned_on
     */
    public function scopeRepeating($query, $repeating_id, $planned_on) {

        $query->where('repeating_id', '=', $repeating_id);
        $query->where('planned_on', '>=', $planned_on);


    }

    public function saveRepeating($transactionData) {

        if (!$this->exists) {

            return;
        }

        $fillables = collect($transactionData)->except(['update_all', 'user_id', 'id', 'repeating_id'])->toArray();

        $firstDate = (new Carbon($this->planned_on))->addMonth($transactionData['repeating_interval'])->format('Y-m-d');

        for ($c = 1; $c < 50; $c++) {

            $rTransaction = new Transaction($fillables);
            $rTransaction->planned_on = $firstDate;
            $rTransaction->user_id = $this->user_id;
            $rTransaction->repeating_id = $this->repeating_id;

            $rTransaction->save();

            $firstDate = (new Carbon($rTransaction->planned_on))->addMonth($transactionData['repeating_interval'])->format('Y-m-d');
        }

    }


}
