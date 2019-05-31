<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class MakeFinalResultRequest extends FormRequest
{
    public $errorBag = 'finalResults';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('castFinalResult', $this->route('season'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'baker_id'  => [ 'required', Rule::exists('bakers', 'id') ],
            'winner' => 'sometimes|boolean'
        ];
    }
}
