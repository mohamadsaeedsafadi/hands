<?php
namespace App\Repositories;

use App\Models\ProviderProfile;
use App\Models\Portfolio;

class ProviderRepository {
  
    public function updateOrCreateProfile($userId, array $data) {
        return ProviderProfile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

  
    public function addPortfolioItem($userId, array $data) {
        return Portfolio::create([
            'provider_id' => $userId, 
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image_path']
        ]);
    }
}