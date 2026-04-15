<?php

namespace App\Services\Admin;

use App\Repositories\QuestionRepository;

class AdminQuestionService
{
    protected $repo;

    public function __construct(QuestionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create($data)
    {
        return $this->repo->create($data);
    }

    public function update($id, $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}