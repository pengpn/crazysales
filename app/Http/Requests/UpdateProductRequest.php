<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'title.min' => '标题不能少于6个字符',
            'title.max' => '标题不能多于255个字符',
            'category.required' => '分类不能为空',
            'cost.required' => '成本不能为空',
            'cost.numeric' => '成本必须为数字',
            'price.required' => '售价不能为空',
            'price.numeric' => '售价必须为数字',
            'brandID.required' => '品牌不能为空',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:6|max:255',
            'category' => 'required',
            'brandID' => 'required',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }
}