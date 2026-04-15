<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class ReportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'reported_user_id' => 'required|exists:users,id',
            'type' => 'required|in:spam,fraud,abuse,other',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048'
        ];
    }
}