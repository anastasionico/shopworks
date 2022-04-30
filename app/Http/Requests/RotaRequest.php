<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RotaRequest extends FormRequest
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
        return [
            'rota' => 'required|integer|exists:App\Models\Rota,id'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['rota'] = $this->route('rota')->id;
        return $data;
    }

}
