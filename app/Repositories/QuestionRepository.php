<?php
namespace App\Repositories;

use App\Models\ServiceQuestion;

class QuestionRepository
{
    public function all()
{
    return ServiceQuestion::with('category')->latest()->get();
}

    public function create($data)
    {
        return ServiceQuestion::create($data);
    }

    public function update($id, $data)
    {
        $q = ServiceQuestion::findOrFail($id);
        $q->update($data);
        return $q;
    }

    public function delete($id)
    {
        return ServiceQuestion::destroy($id);
    }
}
