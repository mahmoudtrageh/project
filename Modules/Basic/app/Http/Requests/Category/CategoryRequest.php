<?php

namespace Modules\Basic\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name.*' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
            ],
            'description.*' => ['nullable', 'string'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // Add unique rule for slug, but exclude the current category if updating
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['slug'][] = Rule::unique('categories')->ignore($this->category);
            
            // Prevent setting the category as its own parent
            $rules['parent_id'][] = function ($attribute, $value, $fail) {
                if ($value == $this->category) {
                    $fail('A category cannot be its own parent.');
                }
            };
        } else {
            $rules['slug'][] = 'unique:categories';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'slug.unique' => 'This slug is already in use.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'parent_id.exists' => 'The selected parent category does not exist.',
            'image.image' => 'The preview image must be an image.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Set a default value for is_active if not provided
        if ($this->isMethod('post') && !$this->has('is_active')) {
            $this->merge(['is_active' => true]);
        }

        // Set a default value for order if not provided
        if ($this->isMethod('post') && !$this->has('order')) {
            $this->merge(['order' => 0]);
        }
    }
}
