<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderProfileController extends Controller {
    protected $service;

    public function __construct(ProviderService $service) {
        $this->service = $service;
    }

  
    public function update(Request $request) {
        $user = Auth::user();
        $profile = $this->service->setupProviderProfile($user->id, $request->all());
        
        return response()->json(['message' => 'Profile updated successfully', 'data' => $profile]);
    }

   
    public function storePortfolio(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $item = $this->service->uploadWorkToPortfolio(
            Auth::user()->id, 
            $request->file('image'), 
            $request->only(['title', 'description'])
        );

        return response()->json(['message' => 'Work added to portfolio', 'data' => $item]);
    }
}