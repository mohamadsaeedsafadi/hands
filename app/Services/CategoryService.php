<?php
namespace App\Services;
use Illuminate\Support\Facades\Cache;
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
        $category = $this->categoryRepo->create($data);

    $this->clearCategoryCache();

    return $category;
    }

   public function listCategories()
{
    $page = request()->get('page', 1);

    return Cache::remember("categories.main.page.$page", 3600, function () {
        return $this->categoryRepo->getMainWithChildren();
    });
}


  public function getCategoryWithQuestions($id)
{
    return Cache::remember("categories.withQuestions.$id", 3600, function () use ($id) {
        return $this->categoryRepo->findWithQuestions($id);
    });
}
public function getMainCategories()
{
    $page = request()->get('page', 1);

    return Cache::remember("categories.main.only.page.$page", 3600, function () {
        return $this->categoryRepo->getMainCategories();
    });
}
public function getSubCategories($parentId)
{
    $page = request()->get('page', 1);

    return Cache::remember("categories.sub.$parentId.page.$page", 3600, function () use ($parentId) {
        return $this->categoryRepo->getSubCategories($parentId);
    });
}
public function getAll()
{
    $page = request()->get('page', 1);

    return Cache::remember("categories.all.page.$page", 3600, function () {
        return $this->categoryRepo->all();
    });
}

    public function create($data)
    {
       $category = $this->categoryRepo->create($data);

    $this->clearCategoryCache();

    return $category;
    }

   public function update($id, $data)
{
    $category = $this->categoryRepo->update($id, $data);

    $this->clearCategoryCache();

    return $category;
}

   public function delete($id)
{
    $category = $this->categoryRepo->delete($id);

    $this->clearCategoryCache();

    return $category;
}

    private function clearCategoryCache()
{
    foreach (range(1, 10) as $page) {
        Cache::forget("categories.main.page.$page");
        Cache::forget("categories.main.only.page.$page");
        Cache::forget("categories.all.page.$page");
    }

    Cache::flush(); 
}
}