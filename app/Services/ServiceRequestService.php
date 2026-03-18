<?php
namespace App\Services;

use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Repositories\ServiceRequestRepository;
use Exception;

class ServiceRequestService
{
    protected $requestRepo;

    public function __construct(ServiceRequestRepository $requestRepo)
    {
        $this->requestRepo = $requestRepo;
    }

    public function createRequest($user, array $data)
    {
        $openRequest = ServiceRequest::where('user_id', $user->id)
    ->where('category_id', $data['category_id'])
    ->whereIn('status', ['pending', 'in_progress'])
    ->exists();

if ($openRequest) {
    throw new Exception('لديك طلب مفتوح بالفعل لهذه الخدمة.');
}
        $category = ServiceCategory::with('questions')
            ->findOrFail($data['category_id']);

        $answers = $data['answers'];

        foreach ($category->questions as $question) {

            if ($question->is_required && !isset($answers[$question->id])) {
                throw new Exception("السؤال {$question->question} مطلوب.");
            }

            if (isset($answers[$question->id])) {

                $this->validateAnswerType(
                    $question,
                    $answers[$question->id]
                );
            }
        }

        return $this->requestRepo->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'answers' => $answers,
            'status' => 'pending'
        ]);
    }

    private function validateAnswerType($question, $answer)
    {
        switch ($question->type) {

            case 'number':
                if (!is_numeric($answer)) {
                    throw new Exception("إجابة {$question->question} يجب أن تكون رقم.");
                }
                break;

            case 'select':
                if (!in_array($answer, $question->options)) {
                    throw new Exception("إجابة غير صحيحة للسؤال {$question->question}");
                }
                break;

            case 'multi_select':
                if (!is_array($answer)) {
                    throw new Exception("إجابة {$question->question} يجب أن تكون مصفوفة.");
                }
                break;
        }
    }
    public function getAvailableRequestsForProvider($provider)
{
    if ($provider->role !== 'provider') {
        throw new Exception('غير مصرح');
    }

    return $this->requestRepo
        ->getRequestsForProvider($provider);
}
}