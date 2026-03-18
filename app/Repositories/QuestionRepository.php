<?php
namespace App\Repositories;

use App\Models\ServiceQuestion;

class QuestionRepository
{
    public function create(array $data)
    {
        return ServiceQuestion::create($data);
    }
    public function getCategoryQuestions($categoryId)
{
    return ServiceQuestion::where('category_id',$categoryId)
        ->select('id','question','type','required')
        ->get();
}
}