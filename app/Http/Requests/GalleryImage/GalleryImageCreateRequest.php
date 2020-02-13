<?php

namespace App\Http\Requests\GalleryImage;

use Illuminate\Foundation\Http\FormRequest;

class GalleryImageCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'images' => 'required|array',
            'images.*.name' => 'required|string|min:3|max:80',
            'images.*.image' => 'required|image|max:8192',
        ];
    }
}
