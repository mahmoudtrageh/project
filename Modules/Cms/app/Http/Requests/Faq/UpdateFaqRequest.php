<?php

namespace Modules\Cms\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'question.*' => 'sometimes|required|string|max:255',
            'answer.*' => 'sometimes|required|string',
            'is_published' => 'sometimes|boolean',
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
