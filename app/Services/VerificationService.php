<?php
namespace App\Services;

use App\Repositories\VerificationRepository;
class VerificationService
{
    public function __construct(protected VerificationRepository $repo) {}

  
    public function requestVerification($user, $data)
    {
        if ($user->role !== 'provider') {
            throw new \Exception("Only providers can request verification");
        }

       
        if ($user->categories()->count() == 0) {
            throw new \Exception("You must select categories first");
        }

       
        if ($this->repo->findPendingByUser($user->id)) {
            throw new \Exception("You already have a pending request");
        }

        
        $data['id_document'] = $data['id_document']->store('verification', 'public');
        $data['work_document'] = $data['work_document']->store('verification', 'public');

        $data['user_id'] = $user->id;

        return $this->repo->create($data);
    }

    
    public function approve($id)
    {
        $request = $this->repo->find($id);

        $this->repo->update($request, [
            'status' => 'approved'
        ]);

        
        $request->user->update([
            'provider_verified_at' => now()
        ]);

        return $request;
    }

    
    public function reject($id, $note)
    {
        $request = $this->repo->find($id);

        return $this->repo->update($request, [
            'status' => 'rejected',
            'admin_note' => $note
        ]);
    }

    public function pendingRequests()
    {
        return $this->repo->getAllPending();
    }
}