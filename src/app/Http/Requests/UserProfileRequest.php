<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
        'name' => 'required|string|max:255',
        'post_code' => 'required|string|max:10',
        'address' => 'required|string|max:255',
        'building' => 'nullable|string|max:255',
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
        'name.required' => '名前を入力してください',
        'name.string' => '名前は文字列で入力してください',
        'name.max' => '名前は最大255文字までです',
        'post_code.required' => '郵便番号を入力してください',
        'post_code.string' => '郵便番号は文字列で入力してください',
        'post_code.max' => '郵便番号は最大10文字までです',
        'address.required' => '住所を入力してください',
        'address.string' => '住所は文字列で入力してください',
        'address.max' => '住所は最大255文字までです',
        'building.string' => '建物名は文字列で入力してください',
        'building.max' => '建物名は最大255文字までです',
        'profile_image.required' => 'プロフィール画像をアップロードしてください',
        'profile_image.image' => '有効な画像ファイルを選択してください',
        'profile_image.mimes' => '画像はjpeg, png, jpg, gif形式のみ許可されています',
        'profile_image.max' => '画像ファイルは最大2MBまでです',
    ];
    }
}
