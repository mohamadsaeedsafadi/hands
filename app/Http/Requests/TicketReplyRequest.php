<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketReplyRequest extends FormRequest
{
   public function rules()
{
    return [
        'message' => 'nullable|string',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,png,pdf,docx|max:2048'
    ];
}
}