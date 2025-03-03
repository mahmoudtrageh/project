<?php

namespace Modules\Cms\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'title.*' => 'required|string|max:255',
            'content.*' => 'required|string',
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ];

        if ($this->isMethod('post') || $this->hasFile('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
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
