<?php
namespace App\Repositories;

use App\Models\ServiceCategory;

class CategoryRepository
{
    public function create(array $data)
    {
        return ServiceCategory::create($data);
    }

    public function getMainWithChildren()
    {
        return ServiceCategory::with('children')
            ->whereNull('parent_id')
            ->get();
    }

    public function findWithQuestions($id)
    {
        return ServiceCategory::with('questions')
            ->findOrFail($id);
    }
    public function getMainCategories()
{
    return ServiceCategory::whereNull('parent_id')
        ->select('id','name')
        ->get();
}
public function getSubCategories($parentId)
{
    return ServiceCategory::where('parent_id',$parentId)
        ->select('id','name','parent_id')
        ->get();
}
}