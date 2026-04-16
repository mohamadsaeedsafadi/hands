<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\QuestionService;
use App\Http\Responses\ApiResponse;
class CategoryController extends Controller
{
    protected $categoryService;
protected $questionService;
    public function __construct(CategoryService $categoryService ,QuestionService $questionService )
    {
        $this->categoryService = $categoryService;
        $this->questionService =$questionService;
    }

    public function index()
    {
        return ApiResponse::success(
    $this->categoryService->listCategories()
);
    }

    public function show($id)
    {
        return ApiResponse::success(
    $this->categoryService->getCategoryWithQuestions($id)
);
        
    }
    public function mainCategories()
{
   

    return ApiResponse::success(
    $this->categoryService->getMainCategories()
);
}
public function subCategories($id)
{

    return ApiResponse::success(
    $this->categoryService->getSubCategories($id)
);
}
public function categoryQuestions($id)
{
    $questions = $this->questionService->getCategoryQuestions($id);

    return ApiResponse::success(
    $questions
);
}
    
}