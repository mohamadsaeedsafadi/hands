<?php
namespace App\Repositories;

use App\Models\Portfolio;

class PortfolioRepository
{
    public function create($data)
    {
        return Portfolio::create($data);
    }

    public function getByUser($userId)
    {
        return Portfolio::where('user_id', $userId)->latest()->get();
    }

    public function find($id)
    {
        return Portfolio::findOrFail($id);
    }

    public function update($portfolio, $data)
    {
        $portfolio->update($data);
        return $portfolio;
    }

    public function delete($portfolio)
    {
        $portfolio->delete();
    }
}