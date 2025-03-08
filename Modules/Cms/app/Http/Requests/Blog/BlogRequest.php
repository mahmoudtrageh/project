<?php

namespace Modules\Cms\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'title.*' => 'required|string|max:255',
            'content.*' => 'required|string',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'outsource_url' => 'nullable|string',

        ];

        if ($this->isMethod('post') || $this->hasFile('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        if ($this->isMethod('put')) {
            $rules['slug'] = [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('blogs')->ignore($this->blog)
            ];
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
