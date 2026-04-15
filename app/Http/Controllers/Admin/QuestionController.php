<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceQuestion;
use App\Services\Admin\AdminQuestionService;
use Illuminate\Http\Request;
class QuestionController extends Controller
{
    protected $service;

    public function __construct(AdminQuestionService $service)
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
        'category_id' => 'required|exists:service_categories,id',
        'question' => 'required|string',
        'type' => 'required|in:text,number,select,multi_select,image',
        'options' => [
    'nullable',
    'array',
    function ($attr, $value, $fail) use ($request) {
        if ($request->type === 'image' && !empty($value)) {
            $fail('Image type should not have options');
        }
    }
],
        'is_required' => 'boolean'
    ]);

    return $this->service->create($data);
}

    public function update($id, Request $request)
{
    $data = $request->validate([
        'category_id' => 'sometimes|exists:service_categories,id',
        'question' => 'sometimes|string',
        'type' => 'sometimes|in:text,number,select,multi_select,image',
        'options' => 'nullable|array',
        'is_required' => 'boolean'
    ]);

    return $this->service->update($id, $data);
}

    public function delete($id)
{
    $q = ServiceQuestion::findOrFail($id);
    $q->delete();

    return $q;
}
}