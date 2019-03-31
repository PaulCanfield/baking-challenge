<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class MakeEpisodeResultsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return Gate::allows('manage', $this->route('episode')->season );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'baker_id'  => 'sometimes|exists:bakers,id',
            'result_id' => 'required|exists:results,id',
            'notes' => 'string|sometimes'
        ];
    }
}
