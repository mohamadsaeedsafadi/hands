<?php
namespace App\Repositories;

use App\Models\ProviderProfile;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProviderRepository {
  
   
public function findifhavecat(){
   $x= Auth::user()->id;
     return DB::table('provider_categories')->where('provider_id',$x)->value('category_id');
 
}
  
   
}