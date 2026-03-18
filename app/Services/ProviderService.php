<?php
namespace App\Services;

use App\Repositories\ProviderRepository;

class ProviderService {
    protected $repo;

    public function __construct(ProviderRepository $repo) {
        $this->repo = $repo;
    }

    public function setupProviderProfile($userId, $details) {
       
        return $this->repo->updateOrCreateProfile($userId, [
            'title' => $details['title'],
            'experience_years' => $details['experience_years'],
        ]);
    }

    public function uploadWorkToPortfolio($userId, $file, $info) {
        
        $path = $file->store('portfolios', 'public');
        
        return $this->repo->addPortfolioItem($userId, [
            'title' => $info['title'],
            'description' => $info['description'],
            'image_path' => $path
        ]);
    }
}