<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class UpdateTicketStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required|in:open,in_progress,waiting_user,resolved,closed',
            'version' => 'required|integer'
        ];
    }
}