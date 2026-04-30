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
            ->paginate(10);
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
        ->paginate(10);
}
   public function getMainCategoriesbyid($id)
{
    
    return ServiceCategory::whereNull('parent_id')->where('id',$id)
        ->value('id');
}
public function getSubCategories($parentId)
{
    return ServiceCategory::where('parent_id',$parentId)
        ->select('id','name','parent_id')
        ->paginate(10);
}
 public function all()
    {
        return ServiceCategory::with('children')
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);
    }

   

    public function update($id, $data)
    {
        $cat = ServiceCategory::findOrFail($id);
        $cat->update($data);
        return $cat;
    }

    public function delete($id)
    {
        $cat = ServiceCategory::findOrFail($id);

        
        if ($cat->children()->exists()) {
            throw new \Exception("Cannot delete category with children");
        }
        if ($cat->questions()->exists()) {
    throw new \Exception("Has questions");
}

        $cat->delete();

        return $cat;
    }
}