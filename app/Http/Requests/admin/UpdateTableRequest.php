<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('id') ?? $this->route('tabel'); // Support route parameter id atau tabel

        return [
            'table_number' => 'required|string|max:100|unique:tables,table_number,' . $id,
            'is_active' => 'nullable|boolean',
        ];
    }
}
