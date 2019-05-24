<?php

namespace App\Http\Controllers;
use App\Company;
use App\CompanyPhoneNumbers;
use App\MenuMeta;
use App\MenuKeys;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use session;
use Illuminate\Validation\Rule;
class CompanyController extends Controller
{
	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $filter_company=Input::get('filter_name');

        if(isset($filter_company)){
            $companies = Company::where('name', $filter_company)->orWhere('name', 'like', '%' . $filter_company . '%')->orWhere('phone_numbers', 'like', '%' . $filter_company . '%')->paginate(10);
        
        }else{
            $companies = Company::where('status', 1)->paginate(10);
        }
        

        
        
        return view('company.index',compact('companies'));
    }
        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
         $menu_meta = MenuMeta::all();
         $menu_keys = MenuKeys::all();
        return view('company.create',compact('menu_meta','menu_keys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 

        echo '<pre>';print_r($request->block);die();
       
        $tier_count=count($request->block)-1;
        $menu_details=array();
if(isset($request->block[0]['main_menu']))
foreach($request->block as $tier_val){
  
            $dial_tree_menus=$tier_val['main_menu'];
            if($tier_val['tier_label']==''){

                foreach($tier_val['main_menu'] as $menu_key=>$menu_val){
                    $menu_details=MenuKeys::find($menu_key);
                    $i=$menu_details->id;
                    $main_menu_details[$i]['tier_relation']=0;
                    $main_menu_details[$i]['id']=$menu_details->id;
                    $main_menu_details[$i]['name']=$menu_details->name;
                    $main_menu_details[$i]['slug']=$menu_details->slug;
                    if(isset($tier_val['sub_menu'][$menu_key])){
                       $main_menu_details[$i]['submenu_slug']=$tier_val['sub_menu'][$menu_key];
                    }else{ 
                        $tier_val['sub_menu'][$menu_key]=$main_menu_details[$i]['submenu_slug']='split';
                    }
                    
                    $main_menu_details[$i]['submenu']['slug']=$tier_val['sub_menu'][$menu_key];
                    if($tier_val['sub_menu'][$menu_key]=='dial_number'){
                        $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        $main_menu_details[$i]['submenu']['label']=$tier_val['sub_menu_label'][$menu_key];
                        $main_menu_details[$i]['submenu']['id']=1;
                        
                    }elseif($tier_val['sub_menu'][$menu_key]=='spanish'){
                        $main_menu_details[$i]['submenu_label']='N/A';
                        $main_menu_details[$i]['submenu']['label']='N/A';
                        $main_menu_details[$i]['submenu']['id']=3;

                    }else{
                        $main_menu_details[$i]['submenu']['id']=2;
                         $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        $main_menu_details[$i]['submenu']['label']=$tier_val['sub_menu_label'][$menu_key];
                       
                        
                    }
                 
                }
                
            }else{
                $tier_count=count($request->block)-1;
                for($j=1;$j<=$tier_count;$j++){
                      
                      $replace_string='block_'.($j-1).'_';
                      $tier_parent=str_replace($replace_string,"",$request->block[$j]['tier_label']);
                     // $main_menu_details[$tier_parent]['submenu']['split']=$request->block[$j];
                     //start
                     foreach($request->block[$j]['main_menu'] as $menu_key=>$menu_val){
                            $menu_details=MenuKeys::find($menu_key);
                            
                            $main_menu_details[$tier_parent]['submenu']['split']['id']=$menu_details->id;
                           $main_menu_details[$tier_parent]['submenu']['split']['name']=$menu_details->name;
                           $main_menu_details[$tier_parent]['submenu']['split']['slug']=$menu_details->slug;
                            if(isset($request->block[$j]['sub_menu'][$menu_key])){
                              $main_menu_details[$tier_parent]['submenu']['split']['submenu_slug']=$request->block[$j]['sub_menu'][$menu_key];
                            }else{ 
                                $request->block[$j]['sub_menu'][$menu_key]=$main_menu_details[$i]['submenu_slug']='split';
                            }
                            
                           $main_menu_details[$tier_parent]['submenu']['split']['submenu']['slug']=$request->block[$j]['sub_menu'][$menu_key];
                            if($request->block[$j]['sub_menu'][$menu_key]=='dial_number'){
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu_label']=$request->block[$j]['sub_menu_label'][$menu_key];
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['submenu_label']=$request->block[$j]['sub_menu_label'][$menu_key];
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['id']=1;
                                
                            }elseif($request->block[$j]['sub_menu'][$menu_key]=='spanish'){
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu_label']='N/A';
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['submenu_label']='N/A';
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['id']=3;

                            }else{
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['id']=2;
                                $main_menu_details[$tier_parent]['submenu']['split']['submenu_label']=$request->block[$j]['sub_menu_label'][$menu_key];
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['submenu_label']=$request->block[$j]['sub_menu_label'][$menu_key];
                               
                                
                            }
                         
                        }
                     //end
                  
                }
                
            }
            if(!empty($main_menu_details)){
            $menu_json=json_encode($main_menu_details,true);
            $menu_array_json=json_encode($request->block,true);
        }else{
            $menu_json='';
            $menu_array_json='';
        }
            
        }else{

        $menu_json='';
        $menu_array_json='';
     }
        
        //echo '<pre>';print_r($menu_json);die();
         $this->validate($request, [
            'name' => Rule::unique('companies')->where(function ($query) {
                     $query->where('status', 1);
                     })
        ]);
         $validator = Validator::make($request->all(), [
            'name' => 'required|max:200|regex:/^[\pL\s\-]+$/u|unique:companies',
            'company_phone_numbers' => 'required|min:1|numericarray',
            'website' => 'sometimes|nullable|regex:/^http:\/\/\w+(\.\w+)*(:[0-9]+)?\/?$/',
            'company_address1'=>'sometimes|nullable|alpha_dash',
            'company_address2'=>'sometimes|nullable|alpha_dash',
            'state'=>'sometimes|nullable|alpha_dash',
            'city'=>'sometimes|nullable|alpha_dash',
            'zipcode'=>'sometimes|nullable|alpha_dash',
            'company_logo'=>'sometimes|nullable|image|mimes:jpg,jpeg,png|max:500000',
            'monday.from_time'=>'sometimes|nullable|date_format:H:i',
            'monday.to_time'=>'required_with:monday.from_time|sometimes|nullable|date_format:H:i',
            'tuesday.from_time'=>'sometimes|nullable|date_format:H:i',
            'tuesday.to_time'=>'required_with:tuesday.from_time|sometimes|nullable|date_format:H:i',
            'wednesday.from_time'=>'sometimes|nullable|date_format:H:i',
            'wednesday.to_time'=>'required_with:wednesday.from_time|sometimes|nullable|date_format:H:i',
            'thursday.from_time'=>'sometimes|nullable|date_format:H:i',
            'thursday.to_time'=>'required_with:thursday.from_time|sometimes|nullable|date_format:H:i',
            'friday.from_time'=>'sometimes|nullable|date_format:H:i',
            'friday.to_time'=>'required_with:friday.from_time|sometimes|nullable|date_format:H:i',
            'saturday.from_time'=>'sometimes|nullable|date_format:H:i',
            'saturday.to_time'=>'required_with:saturday.from_time|sometimes|nullable|date_format:H:i',
            'sunday.from_time'=>'sometimes|nullable|date_format:H:i',
            'sunday.to_time'=>'required_with:sunday.from_time|sometimes|nullable|date_format:H:i',
            'facebook_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'twitter_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'instagram_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'yelp_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'pinterest_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'contact_name' => 'sometimes|nullable||regex:/^[\pL\s\-]+$/u',
            'contact_phone' => 'sometimes|nullable||numeric|digits_between:10,10',
            'contact_email' => 'sometimes|nullable||email',
        ], [
            'company_name.required' => "Company Name is required",
            'company_phone_numbers' => 'Company Phone Number is required',
        ]);
        if ($validator->fails()) {
           // \Session::flash('error_msg', 'Please fill required fields');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $company_contact_numbers=$request->post('company_phone_numbers');
            if(count($company_contact_numbers) > 1){
            $company_contact_number=implode(",",$company_contact_numbers);
            }else{
                $company_contact_number=$company_contact_numbers[0];
            }
            $social_urls=array(
              "facebook" => $request->post('facebook_url'),
              "twitter" => $request->post('facebook_url'),
              "instagram" => $request->post('instagram_url'),
              "yelp" => $request->post('yelp_url'),
               "pinterest" => $request->post('pinterest_url')
            );
            $company_working_hours=array(
            "monday" => $request->post('monday'),
            "tuesday" => $request->post('tuesday'),
            "wednesday" => $request->post('wednesday'),
            "thursday" => $request->post('thursday'),
            "friday" => $request->post('friday'),
            "saturday" => $request->post('saturday'),
            "sunday" => $request->post('sunday')
            );
            
             $company = new \App\Company;
             $company->name = $request->post('name');
             $company->phone_numbers=$company_contact_number;
             $company->address1=$request->post('company_address1');
             $company->address2=$request->post('company_address1');
             $company->state=$request->post('state');
             $company->city=$request->post('city');
             $company->zip=$request->post('zipcode');
             $company->website=$request->post('website');
             $company->status=1;
             $company->contact_name=$request->post('contact_name');
             $company->contact_phone=$request->post('contact_phone');
             $company->contact_email=$request->post('contact_email');
             $company->social_urls=json_encode($social_urls);
             $company->working_hours=json_encode($company_working_hours);
             $company->menu_json=$menu_json;
             $company->menu_array_json=$menu_array_json;
             if($company->save()){
                $company_id=$company->id;
                //upload image
            if ($request->hasFile('company_logo')){ 
             
                    $company_logo = Company::find($company_id);
                   
                    $file = request()->file('company_logo');
                    $img = Image::make($request->company_logo)->resize(200,null,function ($constraint) {
                    $constraint->aspectRatio();
                    })->crop(200,150);
                    $imageName = time().'.'.$request->company_logo->getClientOriginalExtension();
                    $request->company_logo->move(public_path('images/'.$company_id.'/'), $imageName);

                    $img->save(public_path('images/'.$company_id.'/').'thumbnail_'.$imageName.'');
                    $company_logo->thumb=config('app.url').'images/'.$company_id.'/'.'thumbnail_'.$imageName;
                    $company_logo->company_logo =config('app.url').'/images/'.$company_id.'/'.$imageName;
                    $company_logo->save();
                }
            //end image upload
            //save phone numbers
            if(count($company_contact_numbers) >= 1){
                foreach($company_contact_numbers as $numbers =>$phone_number_val){
                    $company_numbers = new \App\CompanyPhoneNumbers;
                    $company_numbers->company_id = $company_id;
                    $company_numbers->phone_number=$phone_number_val;
                    $company_numbers->save();
                }   
            }
            //end save phone numbers
             
                \Session::flash('success_msg', 'Company Added successfully!');
             }else{
                 \Session::flash('error_msg', 'Error Occured please try again later!');
             }
             
             
             

           
        }
        
         return redirect('/company');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = \App\Company::find($id);
        if(!isset($company->thumb)){
            $company->thumb=config('app.url').'images/no-logo.png';
        }
       
        $company->company_social_urls=json_decode($company->social_urls,true);
        $company->company_working_hours=json_decode($company->working_hours,true);
        $company->company_phone_numbers=explode(",",$company->phone_numbers);
        $menu_meta = MenuMeta::all();
        $menu_keys = MenuKeys::all();
        return view('company.show',compact('company','menu_meta','menu_keys'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = \App\Company::find($id);
        $company->company_social_urls=json_decode($company->social_urls,true);
        $company->company_working_hours=json_decode($company->working_hours,true);
       // $company->company_phone_numbers=explode(",",$company->phone_numbers);
        $company_contact_numbers = \App\CompanyPhoneNumbers::where('company_id',$id)->where('status',1)->get();
        $company->company_phone_numbers=$company_contact_numbers;
        $menu_meta = MenuMeta::all();
        $menu_keys = MenuKeys::all();
        return view('company.edit',compact('company','menu_meta','menu_keys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function update(Request $request){

      // unset($request->block['undefined']);
//unset($request->block['undefined']);
        $button_menu_list=$request->block;
        unset($button_menu_list['undefined']);
        
      // echo '<pre>';print_r($button_menu_list);die();
        $tier_count=count($button_menu_list)-1;
        $menu_details=array();
if(isset($button_menu_list[0]['main_menu']))
foreach($button_menu_list as $tier_val){
  
            $dial_tree_menus=$tier_val['main_menu'];
            if($tier_val['tier_label']==''){

                foreach($tier_val['main_menu'] as $menu_key=>$menu_val){
                    $menu_details=MenuKeys::find($menu_key);
                    $i=$menu_details->id;
                    $main_menu_details[$i]['tier_relation']=0;
                    $main_menu_details[$i]['id']=$menu_details->id;
                    $main_menu_details[$i]['name']=$menu_details->name;
                    $main_menu_details[$i]['slug']=$menu_details->slug;
                    if(isset($tier_val['sub_menu'][$menu_key])){
                       $main_menu_details[$i]['submenu_slug']=$tier_val['sub_menu'][$menu_key];
                    }else{ 
                        $tier_val['sub_menu'][$menu_key]=$main_menu_details[$i]['submenu_slug']='split';
                    }
                    
                    $main_menu_details[$i]['submenu']['slug']=$tier_val['sub_menu'][$menu_key];
                    if($tier_val['sub_menu'][$menu_key]=='dial_number'){
                        $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        $main_menu_details[$i]['submenu']['label']=$tier_val['sub_menu_label'][$menu_key];
                        $main_menu_details[$i]['submenu']['id']=1;
                        
                    }elseif($tier_val['sub_menu'][$menu_key]=='spanish'){
                        $main_menu_details[$i]['submenu_label']='N/A';
                        $main_menu_details[$i]['submenu']['label']='N/A';
                        $main_menu_details[$i]['submenu']['id']=3;

                    }else{
                        $main_menu_details[$i]['submenu']['id']=2;
                         $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        $main_menu_details[$i]['submenu']['label']=$tier_val['sub_menu_label'][$menu_key];
                       
                        
                    }
                 
                }
                
            }else{
                $tier_count=count($button_menu_list)-1;
                for($j=1;$j<=$tier_count;$j++){
                      
                      $replace_string='block_'.($j-1).'_';
                      $tier_parent=str_replace($replace_string,"",$button_menu_list[$j]['tier_label']);
                     // $main_menu_details[$tier_parent]['submenu']['split']=$request->block[$j];
                     //start
                     foreach($request->block[$j]['main_menu'] as $menu_key=>$menu_val){
                            $menu_details=MenuKeys::find($menu_key);
                            
                            $main_menu_details[$tier_parent]['submenu']['split']['id']=$menu_details->id;
                           $main_menu_details[$tier_parent]['submenu']['split']['name']=$menu_details->name;
                           $main_menu_details[$tier_parent]['submenu']['split']['slug']=$menu_details->slug;
                            if(isset($request->block[$j]['sub_menu'][$menu_key])){
                              $main_menu_details[$tier_parent]['submenu']['split']['submenu_slug']=$button_menu_list[$j]['sub_menu'][$menu_key];
                            }else{ 
                                $request->block[$j]['sub_menu'][$menu_key]=$main_menu_details[$i]['submenu_slug']='split';
                            }
                            
                           $main_menu_details[$tier_parent]['submenu']['split']['submenu']['slug']=$button_menu_list[$j]['sub_menu'][$menu_key];
                            if($request->block[$j]['sub_menu'][$menu_key]=='dial_number'){
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu_label']=$button_menu_list[$j]['sub_menu_label'][$menu_key];
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['submenu_label']=$button_menu_list[$j]['sub_menu_label'][$menu_key];
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['id']=1;
                                
                            }elseif($request->block[$j]['sub_menu'][$menu_key]=='spanish'){
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu_label']='N/A';
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['submenu_label']='N/A';
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['id']=3;

                            }else{
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['id']=2;
                                $main_menu_details[$tier_parent]['submenu']['split']['submenu_label']=$button_menu_list[$j]['sub_menu_label'][$menu_key];
                               $main_menu_details[$tier_parent]['submenu']['split']['submenu']['submenu_label']=$button_menu_list[$j]['sub_menu_label'][$menu_key];
                               
                                
                            }
                         
                        }
                     //end
                  
                }
                
            }
            if(!empty($main_menu_details)){
            $menu_json=json_encode($main_menu_details,true);
            $menu_array_json=json_encode($request->block,true);
        }else{
            $menu_json='';
            $menu_array_json='';
        }
            
        }else{

        $menu_json='';
        $menu_array_json='';
     }
        
        echo '<pre>';       print_r($main_menu_details); die();
        $id=$request->post('id'); 
        $this->validate($request, [
            'name' => Rule::unique('companies')->ignore($id),
        ]);
   
         $validator = Validator::make($request->all(), [
            'name' => 'required|max:200|regex:/^[\pL\s\-]+$/u|unique:companies,id,:id',
            'company_phone_numbers' => 'required|min:1|numericarray',
            'website' => 'sometimes|nullable|regex:/^http:\/\/\w+(\.\w+)*(:[0-9]+)?\/?$/',
            'company_address1'=>'sometimes|nullable|alpha_dash',
            'company_address2'=>'sometimes|nullable|alpha_dash',
            'state'=>'sometimes|nullable|alpha_dash',
            'city'=>'sometimes|nullable|alpha_dash',
            'zipcode'=>'sometimes|nullable|alpha_dash',
            'company_logo'=>'sometimes|nullable|image|mimes:jpg,jpeg,png|max:500000',
            'monday.from_time'=>'sometimes|nullable|date_format:H:i',
            'monday.to_time'=>'required_with:monday.from_time|sometimes|nullable|date_format:H:i',
            'tuesday.from_time'=>'sometimes|nullable|date_format:H:i',
            'tuesday.to_time'=>'required_with:tuesday.from_time|sometimes|nullable|date_format:H:i',
            'wednesday.from_time'=>'sometimes|nullable|date_format:H:i',
            'wednesday.to_time'=>'required_with:wednesday.from_time|sometimes|nullable|date_format:H:i',
            'thursday.from_time'=>'sometimes|nullable|date_format:H:i',
            'thursday.to_time'=>'required_with:thursday.from_time|sometimes|nullable|date_format:H:i',
            'friday.from_time'=>'sometimes|nullable|date_format:H:i',
            'friday.to_time'=>'required_with:friday.from_time|sometimes|nullable|date_format:H:i',
            'saturday.from_time'=>'sometimes|nullable|date_format:H:i',
            'saturday.to_time'=>'required_with:saturday.from_time|sometimes|nullable|date_format:H:i',
            'sunday.from_time'=>'sometimes|nullable|date_format:H:i',
            'sunday.to_time'=>'required_with:sunday.from_time|sometimes|nullable|date_format:H:i',
            'facebook_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'twitter_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'instagram_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'yelp_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'pinterest_url' => 'sometimes|nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'contact_name' => 'sometimes|nullable||regex:/^[\pL\s\-]+$/u',
            'contact_phone' => 'sometimes|nullable||numeric|digits_between:10,10',
            'contact_email' => 'sometimes|nullable||email',
        ], [
            'company_name.required' => "Company Name is required",
            'company_phone_numbers' => 'Company Phone Number is required',
        ]);
        if ($validator->fails()) {
           // \Session::flash('error_msg', 'Please fill required fields');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $company_contact_numbers=$request->post('company_phone_numbers');
            if(count($company_contact_numbers) > 1){
            $company_contact_number=implode(",",$company_contact_numbers);
            }else{
                $company_contact_numbers=array_values($company_contact_numbers);
                $company_contact_number=$company_contact_numbers[0];
            }
            
            $social_urls=array(
              "facebook" => $request->post('facebook_url'),
              "twitter" => $request->post('facebook_url'),
              "instagram" => $request->post('instagram_url'),
              "yelp" => $request->post('yelp_url'),
               "pinterest" => $request->post('pinterest_url')
            );
            $company_working_hours=array(
            "monday" => $request->post('monday'),
            "tuesday" => $request->post('tuesday'),
            "wednesday" => $request->post('wednesday'),
            "thursday" => $request->post('thursday'),
            "friday" => $request->post('friday'),
            "saturday" => $request->post('saturday'),
            "sunday" => $request->post('sunday')
            );
            
             $company= Company::find($id);
             $company->name = $request->post('name');
             $company->phone_numbers=$company_contact_number;
             $company->address1=$request->post('company_address1');
             $company->address2=$request->post('company_address1');
             $company->state=$request->post('state');
             $company->city=$request->post('city');
             $company->zip=$request->post('zipcode');
             $company->website=$request->post('website');
             $company->status=1;
             $company->contact_name=$request->post('contact_name');
             $company->contact_phone=$request->post('contact_phone');
             $company->contact_email=$request->post('contact_email');
             $company->social_urls=json_encode($social_urls);
             $company->working_hours=json_encode($company_working_hours);
             if($company->save()){
                $company_id=$company->id;
                //upload image
            if ($request->hasFile('company_logo')){ 
             
                    $company_logo = Company::find($company_id);
                   
                    $file = request()->file('company_logo');
                    $img = Image::make($request->company_logo)->resize(200,null,function ($constraint) {
                    $constraint->aspectRatio();
                    })->crop(200,150);
                    $imageName = time().'.'.$request->company_logo->getClientOriginalExtension();
                    $request->company_logo->move(public_path('images/'.$company_id.'/'), $imageName);

                    $img->save(public_path('images/'.$company_id.'/').'thumbnail_'.$imageName.'');
                    $company_logo->thumb=config('app.url').'images/'.$company_id.'/'.'thumbnail_'.$imageName;
                    $company_logo->company_logo =config('app.url').'/images/'.$company_id.'/'.$imageName;
                    $company_logo->save();
                }
            //end image upload
            //save phone numbers
            if(count($company_contact_numbers) >= 1){
                CompanyPhoneNumbers::where('company_id', '=', $company_id)->update(['status' => 0]);
                foreach($company_contact_numbers as $numbers =>$phone_number_val){
                     $company_numbers = CompanyPhoneNumbers::find($numbers);
                    if($company_numbers){
                        $company_numbers->company_id = $company_id;
                        $company_numbers->phone_number=$phone_number_val;
                        $company_numbers->status=1;
                        $company_numbers->save();
                    }else{
                        $company_numbers = new \App\CompanyPhoneNumbers;
                        $company_numbers->company_id = $company_id;
                        $company_numbers->phone_number=$phone_number_val;
                        $company_numbers->save();
                    }
                    
                }   
            }
            /* //get post data
            $postData = $request->all();
            //update post data
            \App\Company::find($id)->update($postData); */
            
            //store status message
            \Session::flash('success_msg', 'Company updated successfully!');
        }else{
            \Session::flash('error_msg', 'Error Occured please try again later!');
        }

        return redirect()->route('company.index');
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = \App\Company::find($id);
        $student = \App\Student::find($company->company_id);
        $company->delete();
        $company->students()->delete();
         return redirect()->route('company.index');
    }
     public function filter(Request $request, Company $company)
    {
         // Search for a user based on their company.
        if ($request->has('company')) {
            return $Company->where('name', $request->input('company'))
                ->get();
        }
        // Continue for all of the filters.

        // No filters have been provided, so
        // let's return all users. This is
        // bad - we should paginate in
        // reality.
        return Company::all();
    }
}
