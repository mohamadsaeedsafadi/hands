<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $service) {}

    public function me(Request $request)
    {
        return response()->json(
            $this->service->getMyProfile($request->user())
        );
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'image' => 'nullable|image',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'skills' => 'nullable|array'
        ]);

        $profile = $this->service->updateProfile(
            $request->user(),
            $data
        );

        return response()->json($profile);
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