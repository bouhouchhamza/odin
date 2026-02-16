<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShareRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Link $link */
        $link = $this->route('link');

        return $this->user()->can('updateSharePermission', $link);
    }

    public function rules(): array
    {
        return [
            'permission' => ['required', 'in:read,edit'],
        ];
    }

    public function messages(): array
    {
        return [
            'permission.in' => 'Permission must be read or edit.',
        ];
    }
}
