<?php
namespace App\Services;

use App\Enums\NotificationType;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\ServiceRequestRepository;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ServiceRequestService
{
    protected $requestRepo;
    protected $check;
    protected $provider;
    public function __construct(ServiceRequestRepository $requestRepo , CategoryRepository $check ,ProviderRepository $provider)
    {
        $this->requestRepo = $requestRepo;
        $this->check = $check;
        $this->provider=$provider;
    }

    public function createRequest($user, array $data)
    {
        $openRequest = ServiceRequest::where('user_id', $user->id)
    ->where('category_id', $data['category_id'])
    ->whereIn('status', ['pending', 'in_progress'])
    ->exists();

if ($openRequest) {
   return response()->json('لديك طلب مفتوح بالفعل لهذه الخدمة.');
}
$check2 = $this->check->getMainCategoriesbyid($data['category_id']);
if($check2 == $data['category_id']){
     return response()->json('must be subcat');
}
        $category = ServiceCategory::with('questions')
            ->findOrFail($data['category_id']);

        $answers = $data['answers'];
$images = request()->file('answers');
       foreach ($category->questions as $question) {

    if ($question->is_required && !isset($answers[$question->id])) {
        return("السؤال {$question->question} مطلوب.");
    }

    if (isset($answers[$question->id])) {

      if ($question->type === 'image') {

    $questionImages = $images[$question->id] ?? null;

    if (!$questionImages) {
        throw new \Exception("الصورة مطلوبة لهذا السؤال");
    }

    $uploadedImages = [];

    
    if (!is_array($questionImages)) {
        $questionImages = [$questionImages];
    }

    foreach ($questionImages as $image) {
        $path = $image->store('requests', 'public');
        $uploadedImages[] = $path;
    }

    $answers[$question->id] = $uploadedImages;


        } else {

            $this->validateAnswerType(
                $question,
                $answers[$question->id]
            );
        }
    }
}


        $serviceRequest = $this->requestRepo->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'answers' => $answers,
            'status' => 'pending'
        ]);
  // في createRequest بعد الحفظ
$category->load('providers'); // ضمان تحميل المزودين

foreach ($category->providers as $provider) {
    // مسح كاش الصفحة الأولى على الأقل
    Cache::forget("provider:{$provider->id}:available_requests:page_1");
}

dispatch(function () use ($category, $serviceRequest, $user) {
    foreach ($category->providers as $provider) {
        if ($provider->id !== $user->id) {
            NotificationService::send(
                $provider,
                "طلب خدمة جديد",
                "تمت إضافة طلب جديد في قسم {$category->name}",
                NotificationType::NEW_SERVICE_REQUEST,
                [
                    'service_request_id' => $serviceRequest->id,
                    'category_id'        => $category->id,
                ]
            );
        }
    }
});
        
return $serviceRequest;
    }

    private function validateAnswerType($question, $answer)
    {
        switch ($question->type) {

            case 'number':
                if (!is_numeric($answer)) {
                    return response()->json("إجابة {$question->question} يجب أن تكون رقم.");
                }
                break;

            case 'select':
                if (!in_array($answer, $question->options)) {
                  return response()->json("إجابة غير صحيحة للسؤال {$question->question}");
                }
                break;

            case 'multi_select':
                if (!is_array($answer)) {
                    return response()->json("إجابة {$question->question} يجب أن تكون مصفوفة.");
                }
                break;
                case 'image':
    if (!($answer instanceof \Illuminate\Http\UploadedFile)) {
        throw new Exception("يجب رفع صورة");
    }
    break;
        }
    }
 public function getAvailableRequestsForProvider($provider)
{
    if ($provider->role !== 'provider') {
        return response()->json('غير مصرح');
    }

    $x = $this->provider->findifhavecat();
    if ($x == null) {
        return response()->json('select cat first');
    }

   

  $page = request()->get('page', 1);
$key = "provider:{$provider->id}:available_requests:page_{$page}";

return Cache::remember($key, 300, function () use ($provider) {
    return $this->requestRepo->getRequestsForProvider($provider);
});

}
public function acceptedRequests($user)
{
    if ($user->role === 'provider') {

        return $this->requestRepo
            ->acceptedForProvider($user);
    }

    return $this->requestRepo
        ->acceptedForUser($user);
}
public function rejected($user)
{
   

        return $this->requestRepo
            ->rejected($user);
    

    
}
}