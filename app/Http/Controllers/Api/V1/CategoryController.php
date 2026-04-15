<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\QuestionService;

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
        return response()->json(
            $this->categoryService->listCategories()
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->categoryService->getCategoryWithQuestions($id)
        );
    }
    public function mainCategories()
{
    $categories = $this->categoryService->getMainCategories();

    return response()->json([
        'success' => true,
        'data' => $categories
    ]);
}
public function subCategories($id)
{
    $categories = $this->categoryService->getSubCategories($id);

    return response()->json([
        'success'=>true,
        'data'=>$categories
    ]);
}
public function categoryQuestions($id)
{
    $questions = $this->questionService->getCategoryQuestions($id);

    return response()->json([
        'success'=>true,
        'data'=>$questions
    ]);
}
    
}