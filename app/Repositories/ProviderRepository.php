<?php
namespace App\Repositories;

use App\Models\ProviderProfile;
use App\Models\Portfolio;

class ProviderRepository {
    // تحديث أو إنشاء بيانات مقدم الخدمة
    public function updateOrCreateProfile($userId, array $data) {
        return ProviderProfile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    // إضافة عمل جديد لمعرض الأعمال
    public function addPortfolioItem($userId, array $data) {
        return Portfolio::create([
            'provider_id' => $userId, // مرتبط بـ users table
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image_path']
        ]);
    }
}