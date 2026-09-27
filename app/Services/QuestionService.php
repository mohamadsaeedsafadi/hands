<?php

namespace App\Services;

use App\Models\ServiceQuestion;
use App\Repositories\CategoryRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Support\Facades\Cache;

class QuestionService
{
    protected $questionRepo;
    protected $categoryRepo;

    public function __construct(
        QuestionRepository $questionRepo,
        CategoryRepository $categoryRepo
    ) {
        $this->questionRepo = $questionRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function create(array $data)
    {
        $question = $this->questionRepo->create($data);

        Cache::forget("category.{$data['category_id']}.questions");

        return $question;
    }

    public function getCategoryQuestions($categoryId)
    {
        $check = $this->categoryRepo->getMainCategoriesbyid($categoryId);

       
        if ($check) {
            throw new \Exception("Category must be a subcategory");
        }

         
            return $this->categoryRepo->findWithQuestions($categoryId);
            
    }

    public function getAll()
    {
        return $this->questionRepo->all();
    }

    public function update($id, array $data)
    {
        Cache::forget("category.{$data['category_id']}.questions");

        return $this->questionRepo->update($id, $data);
    }

    public function delete($id)
{
    $question = $this->questionRepo->find($id);

    if (!$question) {
        throw new \Exception("Question not found");
    }
$cat= ServiceQuestion::where('id',$id)->value('category_id');
    $cacheKey = "category.{$cat}.questions";

    Cache::forget($cacheKey);

    $deleted = $this->questionRepo->delete($id);


    return $deleted;
}
}