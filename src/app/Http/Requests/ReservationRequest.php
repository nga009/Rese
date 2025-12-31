<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_id' => ['required', 'exists:shops,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'number' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required' => '店舗を選択してください。',
            'shop_id.exists' => '選択された店舗が存在しません。',
            'date.required' => '予約日を入力してください。',
            'date.date' => '正しい日付を入力してください。',
            'date.after_or_equal' => '予約日は本日以降の日付を選択してください。',
            'time.required' => '予約時間を入力してください。',
            'time.date_format' => '正しい時間形式で入力してください。',
            'number.required' => '人数を選択してください。',
            'number.integer' => '人数は整数で入力してください。',
            'number.min' => '人数は1人以上で選択してください。',
            'number.max' => '人数は10人以下で選択してください。',
        ];
    }
}
