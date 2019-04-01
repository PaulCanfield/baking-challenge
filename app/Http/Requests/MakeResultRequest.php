<?php

namespace App\Http\Requests;

use App\Result;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class MakeResultRequest extends FormRequest
{
    public $errorBag = 'result';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return Gate::allows('create', Result::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'result' => 'required|string',
            'eliminated' => 'sometimes|boolean'
        ];
    }
}
