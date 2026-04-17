<?php
namespace App\Services;

use App\Http\Responses\ApiResponse;
use App\Repositories\CategoryRepository;
use App\Repositories\QuestionRepository;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\isNull;

class QuestionService
{
    protected $questionRepo;
    protected $rebo;
    public function __construct(QuestionRepository $questionRepo , CategoryRepository $rebo)
    {
        $this->questionRepo = $questionRepo;
        $this->rebo=$rebo;
    }

    public function createQuestion(array $data)
    {
        $question = $this->questionRepo->create($data);

    Cache::forget("category.{$data['category_id']}.questions");

    return $question;
    }
    public function getCategoryQuestions($categoryId)
{
    
    $check= $this->rebo->getMainCategoriesbyid($categoryId);
    if($check == $categoryId){
          throw new \Exception("Category must be a subcategory");
    }
     return Cache::remember("category.$categoryId.questions", 3600, function () use ($categoryId) {
        return $this->rebo->findWithQuestions($categoryId);
    });
}
}