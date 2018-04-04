<?php

namespace App\Http\Requests;

class PutTransactionRequest extends PostTransactionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['transaction.id'] = 'required|numeric';
        $rules['transaction.update_all'] = 'required|boolean';

        return $rules;
    }
}
