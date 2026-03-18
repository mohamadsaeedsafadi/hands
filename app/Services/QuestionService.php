<?php
namespace App\Services;

use App\Repositories\QuestionRepository;

class QuestionService
{
    protected $questionRepo;

    public function __construct(QuestionRepository $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function createQuestion(array $data)
    {
        return $this->questionRepo->create($data);
    }
    public function getCategoryQuestions($categoryId)
{
    return $this->questionRepo->getCategoryQuestions($categoryId);
}
}