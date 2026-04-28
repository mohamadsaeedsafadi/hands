<?php
namespace App\Repositories;

use App\Models\Portfolio;

class PortfolioRepository
{
    public function getByUser($userId)
    {
        return Portfolio::with('images')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
    }

    public function create($data)
    {
        return Portfolio::create($data);
    }

    public function find($id)
    {
        return Portfolio::with('images')->findOrFail($id);
    }

    public function delete($portfolio)
    {
        return $portfolio->delete();
    }
}