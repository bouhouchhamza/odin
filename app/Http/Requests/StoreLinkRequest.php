<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Link::class);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'normalized_url' => $this->normalizeUrl((string) $this->input('url')),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'normalized_url' => [
                'required',
                'string',
                'max:2048',
                Rule::unique('links', 'normalized_url')
                    ->where(fn ($query) => $query
                        ->where('user_id', $this->user()->id)
                        ->whereNull('deleted_at')),
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'normalized_url.unique' => 'You already saved this URL.',
            'category_id.exists' => 'The selected category is invalid.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ];
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);

        if ($parts === false || !isset($parts['host'])) {
            return rtrim(strtolower($url), '/');
        }

        $scheme = strtolower($parts['scheme'] ?? 'https');
        $host = strtolower($parts['host']);
        $path = isset($parts['path']) ? rtrim($parts['path'], '/') : '';
        $query = isset($parts['query']) ? '?'.$parts['query'] : '';

        return $scheme.'://'.$host.$path.$query;
    }
}
