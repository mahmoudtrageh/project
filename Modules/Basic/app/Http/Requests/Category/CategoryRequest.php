<?php

namespace Modules\Basic\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            // Basic multilingual fields
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'description.*' => ['nullable', 'string'],
            
            // Non-translated fields
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            
            // SEO fields
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            
            // Additional SEO fields with translation support
            'seo' => ['nullable', 'array'],
        ];
        
        // Add SEO rules for each locale
        foreach (config('app.available_locales') as $locale) {
            $rules['seo.' . $locale] = ['nullable', 'array'];
            $rules['seo.' . $locale . '.title'] = ['nullable', 'string', 'max:70'];
            $rules['seo.' . $locale . '.description'] = ['nullable', 'string', 'max:160'];
            $rules['seo.' . $locale . '.keywords'] = ['nullable', 'string', 'max:255'];
        }

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
        $messages = [
            'name.required' => 'The category name is required.',
            'name.*.required' => 'The category name is required for all languages.',
            'slug.unique' => 'This slug is already in use.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'parent_id.exists' => 'The selected parent category does not exist.',
            'image.image' => 'The uploaded file must be an image.',
            'meta_title.max' => 'The meta title should not exceed 70 characters.',
            'meta_description.max' => 'The meta description should not exceed 160 characters.',
        ];
        
        // Add SEO validation messages for each locale
        foreach (config('app.available_locales') as $locale) {
            $messages['seo.' . $locale . '.title.max'] = "The SEO title for {$locale} should not exceed 70 characters.";
            $messages['seo.' . $locale . '.description.max'] = "The SEO description for {$locale} should not exceed 160 characters.";
        }
        
        return $messages;
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
        
        // Generate a slug from the name in the default locale if not provided
        if (!$this->filled('slug') && $this->filled('name.' . app()->getFallbackLocale())) {
            $name = $this->input('name.' . app()->getFallbackLocale());
            $slug = \Illuminate\Support\Str::slug($name);
            $this->merge(['slug' => $slug]);
        }
    }
}
