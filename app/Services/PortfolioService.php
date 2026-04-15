<?php

namespace App\Services;

use App\Repositories\PortfolioRepository;

class PortfolioService
{
    public function __construct(protected PortfolioRepository $repo) {}

    public function create($user, $data)
    {
        if ($user->role !== 'provider') {
            throw new \Exception("Only providers can add portfolio");
        }

        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('portfolios', 'public');
        }

        $data['user_id'] = $user->id;

        return $this->repo->create($data);
    }

    public function myPortfolios($user)
    {
        return $this->repo->getByUser($user->id);
    }

    public function update($user, $id, $data)
    {
        $portfolio = $this->repo->find($id);

        if ($portfolio->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }

        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('portfolios', 'public');
        }

        return $this->repo->update($portfolio, $data);
    }

    public function delete($user, $id)
    {
        $portfolio = $this->repo->find($id);

        if ($portfolio->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }

        $this->repo->delete($portfolio);
    }

    
    public function getProviderPortfolios($providerId)
    {
        return $this->repo->getByUser($providerId);
    }
}