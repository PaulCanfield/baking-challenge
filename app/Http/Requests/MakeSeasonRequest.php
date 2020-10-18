<?php

namespace App\Http\Requests;

use App\Models\Season;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class MakeSeasonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'POST':
                return auth()->user()->can('create', Season::class);
                break;
            case 'PATCH':
                return Gate::allows('update', $this->route('season') );
                break;
            default:
                return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year'     => 'sometimes|required|numeric|min:1900|max:'.((int) date('Y') + 1),
            'title'    => 'sometimes|required',
            'note'     => 'nullable'
        ];
    }
}
