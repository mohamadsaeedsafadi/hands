<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use Illuminate\Http\Request;
class PortfolioController extends Controller
{
    public function __construct(protected PortfolioService $service) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image'
        ]);

        return response()->json(
            $this->service->create($request->user(), $data)
        );
    }

    public function my(Request $request)
    {
        return response()->json(
            $this->service->myPortfolios($request->user())
        );
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        return response()->json(
            $this->service->update($request->user(), $id, $data)
        );
    }

    public function delete(Request $request, $id)
    {
        $this->service->delete($request->user(), $id);

        return response()->json(['message' => 'Deleted']);
    }

    public function provider($providerId)
    {
        return response()->json(
            $this->service->getProviderPortfolios($providerId)
        );
    }
}