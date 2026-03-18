<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceCategory;
use App\Models\ServiceQuestion;
class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_admin'
        ]);
        /*
|--------------------------------------------------------------------------
| 1 HOME SERVICES
|--------------------------------------------------------------------------
*/

$home = ServiceCategory::create([
'name' => 'خدمات منزلية',
'parent_id' => null
]);

$cleaning = ServiceCategory::create([
'name' => 'تنظيف',
'parent_id' => $home->id
]);

$plumbing = ServiceCategory::create([
'name' => 'سباكة',
'parent_id' => $home->id
]);

$electric = ServiceCategory::create([
'name' => 'كهرباء',
'parent_id' => $home->id
]);

$appliances = ServiceCategory::create([
'name' => 'صيانة أجهزة',
'parent_id' => $home->id
]);

$paint = ServiceCategory::create([
'name' => 'دهان',
'parent_id' => $home->id
]);

$carpentry = ServiceCategory::create([
'name' => 'نجارة',
'parent_id' => $home->id
]);

$this->questionsCleaning($cleaning->id);
$this->questionsPlumbing($plumbing->id);
$this->questionsElectric($electric->id);
$this->questionsAppliance($appliances->id);
$this->questionsPaint($paint->id);
$this->questionsCarpentry($carpentry->id);


/*
|--------------------------------------------------------------------------
| 2 CAR SERVICES
|--------------------------------------------------------------------------
*/

$car = ServiceCategory::create([
'name' => 'خدمات سيارات',
'parent_id' => null
]);

$mechanic = ServiceCategory::create([
'name' => 'ميكانيك',
'parent_id' => $car->id
]);

$wash = ServiceCategory::create([
'name' => 'غسيل سيارات',
'parent_id' => $car->id
]);

$this->questionsMechanic($mechanic->id);
$this->questionsCarWash($wash->id);


/*
|--------------------------------------------------------------------------
| 3 TECH SERVICES
|--------------------------------------------------------------------------
*/

$tech = ServiceCategory::create([
'name'=>'خدمات تقنية',
'parent_id'=>null
]);

$screen = ServiceCategory::create([
'name'=>'تركيب شاشات',
'parent_id'=>$tech->id
]);

$camera = ServiceCategory::create([
'name'=>'تركيب كاميرات',
'parent_id'=>$tech->id
]);

$programming = ServiceCategory::create([
'name'=>'برمجة وتصميم مواقع',
'parent_id'=>$tech->id
]);

$this->questionsScreen($screen->id);
$this->questionsCamera($camera->id);
$this->questionsProgramming($programming->id);


/*
|--------------------------------------------------------------------------
| 4 EDUCATION
|--------------------------------------------------------------------------
*/

$edu = ServiceCategory::create([
'name'=>'خدمات تعليمية',
'parent_id'=>null
]);

$tutor = ServiceCategory::create([
'name'=>'مدرس خصوصي',
'parent_id'=>$edu->id
]);

$translate = ServiceCategory::create([
'name'=>'ترجمة',
'parent_id'=>$edu->id
]);

$this->questionsTutor($tutor->id);
$this->questionsTranslation($translate->id);


/*
|--------------------------------------------------------------------------
| 5 HEALTH
|--------------------------------------------------------------------------
*/

$health = ServiceCategory::create([
'name'=>'خدمات صحية وجمالية',
'parent_id'=>null
]);

$nurse = ServiceCategory::create([
'name'=>'تمريض',
'parent_id'=>$health->id
]);

$barber = ServiceCategory::create([
'name'=>'حلاقة',
'parent_id'=>$health->id
]);

$makeup = ServiceCategory::create([
'name'=>'ميك اب',
'parent_id'=>$health->id
]);

$this->questionsNurse($nurse->id);
$this->questionsBarber($barber->id);
$this->questionsMakeup($makeup->id);


/*
|--------------------------------------------------------------------------
| 6 EVENTS
|--------------------------------------------------------------------------
*/

$event = ServiceCategory::create([
'name'=>'مناسبات',
'parent_id'=>null
]);

$photo = ServiceCategory::create([
'name'=>'تصوير',
'parent_id'=>$event->id
]);

$organize = ServiceCategory::create([
'name'=>'تنظيم حفلات',
'parent_id'=>$event->id
]);

$this->questionsPhotography($photo->id);
$this->questionsEvent($organize->id);


/*
|--------------------------------------------------------------------------
| 7 LOGISTICS
|--------------------------------------------------------------------------
*/

$log = ServiceCategory::create([
'name'=>'خدمات لوجستية',
'parent_id'=>null
]);

$delivery = ServiceCategory::create([
'name'=>'توصيل طلبات',
'parent_id'=>$log->id
]);

$moving = ServiceCategory::create([
'name'=>'نقل عفش',
'parent_id'=>$log->id
]);

$this->questionsDelivery($delivery->id);
$this->questionsMoving($moving->id);


/*
|--------------------------------------------------------------------------
| 8 OTHER
|--------------------------------------------------------------------------
*/

$other = ServiceCategory::create([
'name'=>'أخرى',
'parent_id'=>null
]);

$guard = ServiceCategory::create([
'name'=>'حراسة',
'parent_id'=>$other->id
]);

$pet = ServiceCategory::create([
'name'=>'رعاية حيوان',
'parent_id'=>$other->id
]);

$tailor = ServiceCategory::create([
'name'=>'خياطة',
'parent_id'=>$other->id
]);

$this->questionsGuard($guard->id);
$this->questionsPet($pet->id);
$this->questionsTailor($tailor->id);

}

/*
|--------------------------------------------------------------------------
| CLEANING QUESTIONS
|--------------------------------------------------------------------------
*/

private function questionsCleaning($id)
{

$questions = [

'ما نوع المكان المطلوب تنظيفه؟',
'كم عدد الغرف؟',
'هل يوجد مطبخ؟',
'هل يوجد حمامات؟ كم عددها؟',
'هل يوجد سجاد يحتاج تنظيف؟',
'هل تحتاج تنظيف النوافذ؟',
'هل يوجد حيوانات أليفة في المنزل؟',
'هل يوجد أثاث يحتاج تنظيف عميق؟',
'ما مستوى الاتساخ؟ (خفيف / متوسط / شديد)',
'ما المساحة التقريبية للمكان بالمتر؟',
'هل تحتاج مواد تنظيف خاصة؟',
'هل تريد الخدمة لمرة واحدة أم بشكل دوري؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}

/*
|--------------------------------------------------------------------------
| PLUMBING QUESTIONS
|--------------------------------------------------------------------------
*/

private function questionsPlumbing($id)
{

$questions=[

'ما المشكلة في السباكة؟',
'أين موقع المشكلة؟ (مطبخ / حمام / حديقة)',
'هل يوجد تسريب مياه؟',
'هل المياه مقطوعة؟',
'هل هناك انسداد؟',
'هل تحتاج تركيب قطعة جديدة؟',
'ما نوع القطعة المطلوب تركيبها؟',
'هل لديك القطعة أم يجب على الفني توفيرها؟',
'منذ متى بدأت المشكلة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}

/*
|--------------------------------------------------------------------------
| MECHANIC QUESTIONS
|--------------------------------------------------------------------------
*/

private function questionsMechanic($id)
{

$questions=[

'ما نوع السيارة؟',
'ما موديل السيارة؟',
'ما المشكلة في السيارة؟',
'هل السيارة تعمل أم لا؟',
'هل يوجد صوت غريب؟',
'هل يوجد تسريب سوائل؟',
'هل تحتاج سحب السيارة؟',
'ما موقع السيارة حاليا؟',
'متى ظهرت المشكلة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}
    }
    private function questionsElectric($id)
{

$questions=[

'ما نوع المشكلة الكهربائية؟',
'هل يوجد انقطاع كهرباء كامل أم جزئي؟',
'هل المشكلة في مفتاح كهرباء أم مقبس؟',
'هل يوجد ماس كهربائي؟',
'هل تحتاج تركيب إنارة جديدة؟',
'كم عدد نقاط الكهرباء المطلوب إصلاحها؟',
'هل لديك القطع المطلوبة أم يجب على الفني توفيرها؟',
'هل المشكلة طارئة؟',
'ما موقع المشكلة داخل المنزل؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsPaint($id)
{

$questions=[

'ما نوع المكان المطلوب دهانه؟',
'كم عدد الغرف؟',
'ما المساحة التقريبية بالمتر؟',
'هل الجدران تحتاج معجون قبل الدهان؟',
'هل يوجد دهان قديم يجب إزالته؟',
'ما اللون المطلوب؟',
'هل الطلاء داخلي أم خارجي؟',
'هل تحتاج دهان السقف أيضاً؟',
'هل توفر الطلاء أم يجب على الفني توفيره؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsCarpentry($id)
{

$questions=[

'ما نوع العمل المطلوب؟',
'هل تحتاج إصلاح أم تركيب جديد؟',
'ما نوع القطعة الخشبية؟',
'ما أبعاد القطعة المطلوبة؟',
'هل لديك التصميم المطلوب؟',
'هل الخشب متوفر أم يجب توفيره؟',
'كم عدد القطع المطلوبة؟',
'هل العمل داخل المنزل أم خارجه؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsCarWash($id)
{

$questions=[

'ما نوع السيارة؟',
'هل تريد غسيل داخلي أم خارجي؟',
'هل تريد تلميع السيارة؟',
'هل السيارة متسخة بشدة؟',
'هل تحتاج تنظيف المقاعد؟',
'هل تحتاج تنظيف المحرك؟',
'ما موقع السيارة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsScreen($id)
{

$questions=[

'ما نوع الشاشة؟',
'ما حجم الشاشة بالبوصة؟',
'هل تحتاج تركيب على الحائط أم طاولة؟',
'هل الحامل متوفر؟',
'هل تحتاج تمديد كابلات؟',
'هل تحتاج ضبط القنوات؟',
'ما نوع الجدار؟',
'هل المكان مجهز للكهرباء؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsCamera($id)
{

$questions=[

'كم عدد الكاميرات المطلوبة؟',
'هل الكاميرات داخلية أم خارجية؟',
'هل المكان منزل أم شركة؟',
'هل يوجد نظام مراقبة سابق؟',
'هل تحتاج تسجيل فيديو؟',
'هل تحتاج مشاهدة عبر الهاتف؟',
'هل الكاميرات متوفرة أم يجب توفيرها؟',
'ما المساحة التقريبية للمكان؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsProgramming($id)
{

$questions=[

'ما نوع الموقع المطلوب؟',
'هل الموقع تعريفي أم متجر إلكتروني؟',
'كم عدد الصفحات؟',
'هل لديك تصميم جاهز؟',
'هل تحتاج لوحة تحكم؟',
'هل تحتاج بوابة دفع؟',
'ما اللغة المطلوبة للموقع؟',
'ما موعد التسليم المطلوب؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsTutor($id)
{

$questions=[

'ما المادة المطلوبة؟',
'ما الصف الدراسي؟',
'هل التدريس حضوري أم أونلاين؟',
'كم عدد الساعات المطلوبة أسبوعياً؟',
'ما مستوى الطالب الحالي؟',
'ما الهدف من الدروس؟',
'ما موقع الطالب؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsTranslation($id)
{

$questions=[

'ما اللغة الأصلية للنص؟',
'ما اللغة المطلوب الترجمة إليها؟',
'ما نوع النص؟',
'كم عدد الكلمات؟',
'هل تحتاج ترجمة احترافية أم عادية؟',
'هل تحتاج تدقيق لغوي؟',
'ما موعد التسليم المطلوب؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsNurse($id)
{

$questions=[

'ما نوع الخدمة الطبية المطلوبة؟',
'هل المريض رجل أم امرأة؟',
'ما عمر المريض؟',
'هل المريض قادر على الحركة؟',
'هل الخدمة مؤقتة أم يومية؟',
'كم عدد الساعات المطلوبة؟',
'هل يوجد تقارير طبية؟',
'ما موقع المريض؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsBarber($id)
{

$questions=[

'ما نوع الحلاقة المطلوبة؟',
'هل الحلاقة للمنزل أم في الصالون؟',
'هل تريد حلاقة شعر فقط أم شعر ولحية؟',
'هل تحتاج تصفيف شعر؟',
'ما موقع الخدمة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsMakeup($id)
{

$questions=[

'ما نوع المناسبة؟',
'هل المكياج للعروس؟',
'هل تحتاج تسريحة شعر؟',
'كم عدد الأشخاص؟',
'ما موقع الخدمة؟',
'ما موعد المناسبة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsPhotography($id)
{

$questions=[

'ما نوع المناسبة؟',
'كم عدد ساعات التصوير؟',
'هل تحتاج تصوير فيديو أيضاً؟',
'هل تحتاج تعديل الصور؟',
'ما موقع التصوير؟',
'ما تاريخ المناسبة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsEvent($id)
{

$questions=[

'ما نوع الحفل؟',
'كم عدد الضيوف المتوقع؟',
'هل تحتاج ديكور؟',
'هل تحتاج طعام؟',
'هل تحتاج موسيقى؟',
'ما موقع الحفل؟',
'ما تاريخ الحفل؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsDelivery($id)
{

$questions=[

'ما نوع الطلب؟',
'من أين سيتم الاستلام؟',
'إلى أين سيتم التوصيل؟',
'ما وزن الطلب؟',
'هل الطلب قابل للكسر؟',
'هل الدفع عند الاستلام؟',
'ما الوقت المطلوب للتوصيل؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsMoving($id)
{

$questions=[

'ما نوع الأثاث؟',
'كم عدد الغرف؟',
'هل يوجد طابق مرتفع؟',
'هل يوجد مصعد؟',
'هل تحتاج فك وتركيب الأثاث؟',
'من أين سيتم النقل؟',
'إلى أين سيتم النقل؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}

private function questionsGuard($id)
{

$questions=[

'ما نوع المكان؟',
'كم عدد الحراس المطلوبين؟',
'هل الحراسة ليلاً أم نهاراً؟',
'ما مدة الحراسة المطلوبة؟',
'ما موقع المكان؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsPet($id)
{

$questions=[

'ما نوع الحيوان؟',
'كم عمر الحيوان؟',
'ما نوع الرعاية المطلوبة؟',
'هل يحتاج الحيوان أدوية؟',
'كم مدة الرعاية؟',
'ما موقع الخدمة؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}

private function questionsTailor($id)
{

$questions=[

'ما نوع الملابس؟',
'هل العمل تفصيل أم تعديل؟',
'ما المقاسات المطلوبة؟',
'هل القماش متوفر؟',
'كم عدد القطع؟',
'ما موعد التسليم المطلوب؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
private function questionsAppliance($id)
{

$questions=[

'ما نوع الجهاز؟',
'ما الماركة؟',
'ما موديل الجهاز؟',
'ما المشكلة التي تواجهها؟',
'هل الجهاز يعمل أم لا يعمل إطلاقاً؟',
'هل يظهر رمز خطأ على الشاشة؟',
'متى بدأت المشكلة؟',
'هل تم إصلاح الجهاز سابقاً؟',
'هل الجهاز داخل الضمان؟'

];

foreach($questions as $q){

ServiceQuestion::create([
'category_id'=>$id,
'question'=>$q,
'type'=>'text',
'required'=>true
]);

}

}
}


