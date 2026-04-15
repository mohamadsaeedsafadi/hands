<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required|in:complaint,suggestion',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,low,medium,high',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,png,pdf,docx|max:2048', 
        ];
    }
}