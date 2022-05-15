<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Support\Str;
use App\Http\Requests\Api\ApiRequest;

class StoreCategoryRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:191|unique:categories',
            'slug' => 'required|min:3|max:191|unique:categories',
            'parent_id' => 'exists:categories,id|nullable'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'parent_id' => $this->parent_id == 0 ? null : $this->parent_id,
        ]);
    }
}