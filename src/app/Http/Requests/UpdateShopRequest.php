<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
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
            'name' => 'required',
            'region' => 'required',
            'genre' => 'required',
            'description' => 'required|max:1000',
            'image_url' => 'file'|'mimes:jpeg,jpg,png'|'max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'region.required' => 'エリアを選択してください',
            'genre.required' => 'ジャンルを選択してください',
            'description.required' => '説明を入力してください',
            'description.max' => '説明は最大1000文字までです。',
            'image_url.file' => '有効なファイルをアップロードしてください',
            'image_url.mimes' => 'アップロード可能なファイル形式は、jpeg,jpg,png のみです',
            'image_url.max' => 'イメージのサイズは2MB以下にしてください。'
        ];
    }
}