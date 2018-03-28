<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class GetTransactionsRequest extends Request
{

    protected $method = 'GET';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'date_format:"Y-m-d"|before_or_equal:to',
            'to' => 'date_format:"Y-m-d"|after_or_equal:from'
        ];
    }
}
