<?php

namespace Modules\Newsletter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    public function rules(): array
    {
        // Prepare social links validation rules
        $socialLinksRules = [];
        if ($this->has('social_links')) {
            $platforms = $this->input('social_links.platform', []);
            $urls = $this->input('social_links.url', []);
            
            // Transform the input into social_links array for model
            if (!empty($platforms) && !empty($urls) && count($platforms) === count($urls)) {
                $socialLinks = [];
                foreach ($platforms as $key => $platform) {
                    if (!empty($platform) && !empty($urls[$key])) {
                        $socialLinks[$platform] = $urls[$key];
                    }
                }
                $this->merge(['social_links' => $socialLinks]);
            }
        }

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'custom_css' => 'nullable|string',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'scheduled_at' => 'nullable|date|after:now',
            'recipients' => 'nullable|array',
            'recipients.*' => 'exists:subscribers,id',
            'action' => 'nullable|string|in:save,send',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The newsletter title is required.',
            'content.required' => 'The newsletter content is required.',
            'social_links.*.url' => 'The social media links must be valid URLs.',
            'scheduled_at.after' => 'The scheduled date must be in the future.',
            'recipients.*.exists' => 'One or more selected recipients do not exist.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
