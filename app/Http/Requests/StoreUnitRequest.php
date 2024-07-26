<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dc' => 'required|regex:/^[A-Za-z0-9-\ ]+$/',
            'o;lang-cs' => 'required|string|max:255',
            'o;lang-en' => 'nullable|string|max:255',
            'oabbrev;lang-cs' => 'required|string|max:255',
            'oabbrev;lang-en' => 'nullable|string|max:255',
            'ou;lang-cs' => 'required|string|max:255',
            'ou;lang-en' => 'nullable|string|max:255',
            'ouabbrev;lang-cs' => 'required|string|max:255',
            'ouabbrev;lang-en' => 'nullable|string|max:255',
            'oparentpointer' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'l' => 'required|string|max:255',
            'postalcode' => 'required|numeric',
            'c' => 'required|alpha|size:2',
            'labeleduri' => 'required|url|max:255',
        ];
    }
}
