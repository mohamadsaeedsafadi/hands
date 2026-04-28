<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function me()
    {
        return response()->json(
            $this->service->getProfile(Auth::user()->id)
        );
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'image' => 'nullable|image|max:2048',
            'city' => 'nullable|string',
            'location' => 'nullable|string',
            'bio' => 'nullable|string'
        ]);

        return response()->json(
            $this->service->updateProfile(Auth::user(), $data)
        );
    }
    public function updateLocation(Request $request)
{
    $data = $request->validate([
        'lat' => 'required|numeric',
        'lng' => 'required|numeric'
    ]);

    $user = $request->user();

    $user->update($data);

    return response()->json(['message' => 'Location updated']);
}
}