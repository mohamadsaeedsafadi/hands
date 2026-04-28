<?php

namespace App\Services;

use App\Repositories\PortfolioRepository;
use Illuminate\Support\Facades\Cache;

class PortfolioService
{
    protected $repo;

    public function __construct(PortfolioRepository $repo)
    {
        $this->repo = $repo;
    }

    private function cacheKey($userId)
    {
        return "portfolio_user_{$userId}";
    }

    public function getUserPortfolio($userId)
    {
        return Cache::remember($this->cacheKey($userId), 3600, function () use ($userId) {
            return $this->repo->getByUser($userId);
        });
    }

    public function createPortfolio($user, $data)
    {
        $portfolio = $this->repo->create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null
        ]);

        if (!empty($data['images'])) {
            foreach ($data['images'] as $img) {
                $portfolio->images()->create([
                    'image' => $img->store('portfolio', 'public')
                ]);
            }
        }

        Cache::forget($this->cacheKey($user->id));

        return $portfolio->load('images');
    }

    public function updatePortfolio($portfolio, $data)
    {
        $portfolio->update($data);

        if (!empty($data['images'])) {
            foreach ($data['images'] as $img) {
                $portfolio->images()->create([
                    'image' => $img->store('portfolio', 'public')
                ]);
            }
        }

        Cache::forget($this->cacheKey($portfolio->user_id));

        return $portfolio->load('images');
    }

    public function deletePortfolio($portfolio)
    {
        $userId = $portfolio->user_id;

        $this->repo->delete($portfolio);

        Cache::forget($this->cacheKey($userId));

        return true;
    }
}