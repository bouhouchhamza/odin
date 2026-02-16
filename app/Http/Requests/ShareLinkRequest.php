<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShareLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Link $link */
        $link = $this->route('link');

        return $this->user()->can('share', $link);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::notIn([$this->user()->id]),
            ],
            'permission' => ['required', 'in:read,edit'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.not_in' => 'You cannot share a link with yourself.',
            'permission.in' => 'Permission must be read or edit.',
        ];
    }
}
