<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddAdminRequest extends FormRequest
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
        $adminId = $this->input('admin_id');
        $isUpdate = !empty($adminId);

        return [
            'name' => $isUpdate ? 'nullable' : 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $isUpdate ? Rule::unique('admins', 'email')->ignore($adminId) : 'unique:admins,email'
            ],
            'password' => $isUpdate ? 'nullable|string' : 'required|string',
            'role' => 'required',
            'shop' => 'required_if:role,store_manager',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前は必須項目です。',
            'email.required' => 'メールアドレスは必須項目です。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'email.unique' => '指定のメールアドレスは既に使用されています。',
            'password.required' => 'パスワードは必須項目です。',
            'role.required' => '役割は必須項目です。',
            'shop.required_if' => '店舗代表者の場合、店舗は必須項目です。',
        ];
    }
}
