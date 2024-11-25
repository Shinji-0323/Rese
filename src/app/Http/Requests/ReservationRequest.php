<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:22:00',
            'number' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '予約する日付を選択してください。',
            'date.date' => '有効な日付を入力してください。',
            'date.after_or_equal' => '予約日には今日以降の日付を指定してください。',
            'time.required' => '予約する時間を選択してください。',
            'time.date_format' => '正しい時間形式 (例: 09:00) を入力してください。',
            'time.after_or_equal' => '予約時間は9:00以降を指定してください。',
            'time.before_or_equal' => '予約時間は22:00以前を指定してください。',
            'number.required' => '予約する人数を選択してください。',
            'number.integer' => '人数には有効な数値を入力してください。',
            'number.min' => '予約人数は1人以上を指定してください。',
            'number.max' => '予約人数は5人以下を指定してください。',
        ];
    }
}
