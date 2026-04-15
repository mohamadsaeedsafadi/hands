<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class CategoryManagementController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
       
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getAll();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:service_categories,id'
        ]);

        return $this->service->create($data);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:service_categories,id'
        ]);

        return $this->service->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}