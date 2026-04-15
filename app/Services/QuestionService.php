<?php
namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\QuestionRepository;

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
        return $this->questionRepo->create($data);
    }
    public function getCategoryQuestions($categoryId)
{
    
    $check= $this->rebo->getMainCategoriesbyid($categoryId);
    if($check == $categoryId){
return 'must be subcat';
    }
    return $this->rebo->findWithQuestions($categoryId);
}
}