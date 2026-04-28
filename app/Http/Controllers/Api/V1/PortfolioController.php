<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PortfolioService;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    protected $service;

    public function __construct(PortfolioService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'images' => 'required|array',
            'images.*' => 'image|max:2048'
        ]);

        return response()->json(
            $this->service->createPortfolio(Auth::user(), $data)
        );
    }

    public function my()
    {
        return response()->json(
            $this->service->getUserPortfolio(Auth::user()->id)
        );
    }

    public function provider($id)
    {
        return response()->json(
            $this->service->getUserPortfolio($id)
        );
    }

    public function update($id, Request $request)
    {
        $portfolio = Portfolio::findOrFail($id);

        if ($portfolio->user_id !== Auth::user()->id) {
            throw new \Exception("Unauthorized");
        }

        $data = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048'
        ]);

        return response()->json(
            $this->service->updatePortfolio($portfolio, $data)
        );
    }

    public function delete($id)
    {
        $portfolio = Portfolio::findOrFail($id);

        if ($portfolio->user_id !== Auth::user()->id) {
            throw new \Exception("Unauthorized");
        }

        return response()->json(
            $this->service->deletePortfolio($portfolio)
        );
    }
}