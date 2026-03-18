<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class CategoryManagementController extends Controller
{
    protected $categoryService;
    protected $questionService;

    public function __construct(
        CategoryService $categoryService,
        QuestionService $questionService
    ) {
        $this->categoryService = $categoryService;
        $this->questionService = $questionService;
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:service_categories,id'
        ]);

        $category = $this->categoryService->createCategory($request->all());

        return response()->json($category);
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'question' => 'required|string',
            'type' => 'required|in:text,number,select,multi_select',
            'options' => 'nullable|array',
            'is_required' => 'boolean'
        ]);

        $question = $this->questionService->createQuestion($request->all());

        return response()->json($question);
    }
}