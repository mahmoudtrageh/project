<?php

namespace Modules\Cms\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug',
            'description' => 'required|string',
            'client_name' => 'nullable|string|max:255',
            'completion_date' => 'required|date',
            'url' => 'nullable|url|max:255',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
        ];

        // Add image validation only for new projects or when image is being updated
        if ($this->isMethod('post') || $this->hasFile('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg';
        }

        if ($this->isMethod('put')) {
            $rules['slug'] = [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('projects')->ignore($this->project)
            ];
        }

        return $rules;
    }
}