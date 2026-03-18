<?php
namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepo->create($data);
    }

    public function listCategories()
    {
        return $this->categoryRepo->getMainWithChildren();
    }

    public function getCategoryWithQuestions($id)
    {
        return $this->categoryRepo->findWithQuestions($id);
    }
    public function getMainCategories()
{
    return $this->categoryRepo->getMainCategories();
}
public function getSubCategories($parentId)
{
    return $this->categoryRepo->getSubCategories($parentId);
}
}