<?php
namespace App\Repositories;

use App\Models\VerificationRequest;

class VerificationRepository
{
    public function create($data)
    {
        return VerificationRequest::create($data);
    }

    public function findPendingByUser($userId)
    {
        return VerificationRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->first();
    }

    public function find($id)
    {
        return VerificationRequest::findOrFail($id);
    }

    public function getAllPending()
    {
        return VerificationRequest::where('status', 'pending')->paginate(10);
    }

    public function update($request, $data)
    {
        $request->update($data);
        return $request;
    }
}