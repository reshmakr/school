<?php
namespace App\Http\Controllers;

use App\Company;
use App\CompanyPhoneNumbers;
use App\MenuMeta;
use App\MenuKeys;
use App\ActivityLog;
use App\DialLog;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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
        $title='Home';
        $filter_company=Input::get('filter_name');
        if(isset($filter_company)){
            $companies = Company::where('name', $filter_company)->orWhere('name', 'like', '%' . $filter_company . '%')->orWhere('phone_numbers', 'like', '%' . $filter_company . '%')->paginate(50);
        
        }else{
            $companies = Company::where('status', 1)->paginate(50);
        }
        
       
        
        return view('company.index',compact('companies','title'));
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
         $title='Add New Business Profile';
        return view('company.create',compact('menu_meta','menu_keys','title'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { ini_set('max_input_vars','3000' );
     //echo '<pre>';print_r($request->block);die();
      $tier_count=count($request->block)-1;
        $menu_details=array();
        if(isset($request->block[0]['main_menu']))
        foreach($request->block as $tier_val){
            if(isset($tier_val['main_menu'])){
            $dial_tree_menus=$tier_val['main_menu'];
            $tier_val['tier_child']=array_filter($tier_val['tier_child']); 
            if($tier_val['tier_label']==''){
                foreach($tier_val['main_menu'] as $menu_key=>$menu_val){
              
                    $menu_details=MenuKeys::find($menu_key);
                    $i=$menu_details->id;
                    $main_menu_details[$i]['id']=$menu_details->id;
                    $main_menu_details[$i]['name']=$menu_details->name;
                    $main_menu_details[$i]['slug']=$menu_details->slug;
                    $main_menu_details[$i]['button_number']=$menu_details->button_number;
                    if(isset($tier_val['sub_menu'][$menu_key])){
                       $main_menu_details[$i]['submenu_slug']=$tier_val['sub_menu'][$menu_key];
                    }else{ 
                        $tier_val['sub_menu'][$menu_key]=$main_menu_details[$i]['submenu_slug']='split';
                    }
                    
                    if($tier_val['sub_menu'][$menu_key]=='dial_number'){
                        $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        
                    }elseif($tier_val['sub_menu'][$menu_key]=='spanish'){
                        $main_menu_details[$i]['submenu_label']='N/A';
                    }else{
                        $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        
                        if($tier_val['tier_child'][$menu_key] && $request->block[$tier_val['tier_child'][$menu_key]]){
                             $child_details=$request->block[$tier_val['tier_child'][$menu_key]];
                             //first start
                             $x=0;
							// echo '<pre>'; print_r($child_details);die();
							 if(isset($child_details['main_menu'])){
                             foreach($child_details['main_menu'] as $child_menu_key=>$menu_val){
                                    $menu_child_details=MenuKeys::find($child_menu_key);
                                    $j=$menu_child_details->id;
									//print_r($j);
									//var_dump($child_details['sub_menu_label'][$j]);
									//die();
                                    $main_menu_details[$i]['list'][$x]['name']=$menu_child_details->name;
                                    $main_menu_details[$i]['list'][$x]['slug']=$menu_child_details->slug;
                                    $main_menu_details[$i]['list'][$x]['button_number']=$menu_child_details->button_number;
									if(!isset($child_details['sub_menu_label'][$j])){
										return response()->json(['errors'=>'Error Occured please try again later!']);
										\Session::flash('error_msg', 'Error Occured please try again later!');
										return redirect('/home');
									}
										
									$main_menu_details[$i]['list'][$x]['label']=$child_details['sub_menu_label'][$j];
                                      
                                    if(isset($child_details['sub_menu'][$j])){
                                       $main_menu_details[$i]['list'][$x]['submenu_slug']=$child_details['sub_menu'][$j];
                                    }else{ 
                                        $child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
                                    }
                                    
                                    $main_menu_details[$i]['list'][$x]['slug']=$child_details['sub_menu'][$j];
                                    if($child_details['sub_menu'][$j]=='dial_number'){
                                        $main_menu_details[$i]['list'][$x]['label']=$child_details['sub_menu_label'][$j];
                                        
                                    }elseif($child_details['sub_menu'][$j]=='spanish'){
                                        $main_menu_details[$i]['list'][$x]['submenu_label']='N/A';
                                    }else{ 
									 $main_menu_details[$i]['list'][$x]['label']=$child_details['sub_menu_label'][$j];
                                     
									//second tier start
										$second_tier=array_filter($child_details['tier_child']); 
										if($second_tier[$child_menu_key]){
											$second_child_details=$request->block[$second_tier[$child_menu_key]];
											$y=0;
											if(isset($second_child_details['main_menu'])){
											foreach($second_child_details['main_menu'] as $second_child_menu_key=>$second_menu_val){
											  
												$second_menu_child_details=MenuKeys::find($second_child_menu_key);
												$z=$second_menu_child_details->id;
												
												$main_menu_details[$i]['list'][$x]['list'][$y]['name']=$second_menu_child_details->name;
												$main_menu_details[$i]['list'][$x]['list'][$y]['slug']=$second_menu_child_details->slug;
												$main_menu_details[$i]['list'][$x]['list'][$y]['button_number']=$second_menu_child_details->button_number;
												if(isset($second_child_details['sub_menu'][$second_child_menu_key])){
												   $main_menu_details[$i]['list'][$x]['list'][$y]['submenu_slug']=$second_child_details['sub_menu'][$second_child_menu_key];
												}else{ 
													$second_child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
												}
												
												if($second_child_details['sub_menu'][$second_child_menu_key]=='dial_number'){
													$main_menu_details[$i]['list'][$x]['list'][$y]['label']=$second_child_details['sub_menu_label'][$second_child_menu_key];
													
												}elseif($second_child_details['sub_menu'][$second_child_menu_key]=='spanish'){
													$main_menu_details[$i]['list'][$x]['list'][$y]['submenu_label']='N/A';
												}else{ 
													$main_menu_details[$i]['list'][$x]['list'][$y]['label']=$second_child_details['sub_menu_label'][$second_child_menu_key];
													$main_menu_details[$i]['list'][$x]['list'][$y]['label']=$second_child_details['sub_menu_label'][$child_menu_key];
													//third tier start													
														$third_tier=array_filter($second_child_details['tier_child']); 
															if(isset($third_tier[$second_child_menu_key] )){
															 $third_child_details=$request->block[$third_tier[$second_child_menu_key]];
															 $p=0;
															 if(isset($third_child_details['main_menu'])){
															 foreach($third_child_details['main_menu'] as $third_child_menu_key=>$third_menu_val){

																	$third_menu_child_details=MenuKeys::find($third_child_menu_key);
																	$q=$third_menu_child_details->id;
																	
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['tier_relation']=0;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['id']=$third_menu_child_details->id;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['name']=$third_menu_child_details->name;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['slug']=$third_menu_child_details->slug;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['button_number']=$third_menu_child_details->button_number;
																	if(isset($third_child_details['sub_menu'][$third_child_menu_key])){
																	   $main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['submenu_slug']=$third_child_details['sub_menu'][$third_child_menu_key];
																	}else{ 
																		$third_child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
																	}
																	
																	
																	if($second_child_details['sub_menu'][$second_child_menu_key]=='dial_number'){
																		$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['label']=$third_child_details['sub_menu_label'][$third_child_menu_key];
																		
																	}elseif($third_child_details['sub_menu'][$third_child_menu_key]=='spanish'){
																		$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['submenu_label']='N/A';
																	}else{ 
																		$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['label']=$third_child_details['sub_menu_label'][$third_child_menu_key];
																		//fouth child start
																				
																			$fourth_tier=array_filter($third_child_details['tier_child']); 
																				if(isset($fourth_tier[$third_child_menu_key] )){
																				 $fourth_child_details=$request->block[$fourth_tier[$third_child_menu_key]];
																				 $r=0;
																				 if(isset($fourth_child_details['main_menu'])){
																				 foreach($fourth_child_details['main_menu'] as $fourth_child_menu_key=>$foutyh_menu_val){

																						$fourth_menu_child_details=MenuKeys::find($fourth_child_menu_key);
																						$q=$fourth_menu_child_details->id;
																						$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['name']=$fourth_menu_child_details->name;
																						$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['slug']=$fourth_menu_child_details->slug;
																						$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['button_number']=$fourth_menu_child_details->button_number;
																						if(isset($fourth_child_details['sub_menu'][$fourth_child_menu_key])){
																						   $main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['submenu_slug']=$fourth_child_details['sub_menu'][$fourth_child_menu_key];
																						}else{ 
																							$fourth_child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
																						}
																						
																						
																						if($third_child_details['sub_menu'][$third_child_menu_key]=='dial_number'){
																							$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['label']=$fourth_child_details['sub_menu_label'][$fourth_child_menu_key];
																							
																						}elseif($fourth_child_details['sub_menu'][$fourth_child_menu_key]=='spanish'){
																							$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['submenu_label']='N/A';
																						}else{ 
																							$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['label']=$fourth_child_details['sub_menu_label'][$fourth_child_menu_key];
																						
																							
																						}
																					 $r=$r+1;
																					} 
																				}
																			}												
																		//fourth child end
																		
																	}
																 $p=$p+1;
																} 
															}
														}

														//third tier end
													  
												}
											 $y=$y+1;
											} 
										}
										}

									//second tier end
                                        
                                    }
                                 $x=$x+1;
						} }
                             //end
                        }
                    
                       
                    }
                 
                }
                
            }else{
               
                
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
            return response()->json(['errors'=>'Error Occured please try again later!']);
             \Session::flash('error_msg', 'Error Occured please try again later!');
			 return redirect('/home');
             return redirect()->back()->withInput();
        }
            
        }else{
        $menu_json='';
        $menu_array_json='';
     }

         $this->validate($request, [
            'name' => Rule::unique('companies')->where(function ($query) {
                     $query->where('status', 1);
                     })
        ]);
          //echo '<pre>';print_r($request->all());die();
         $validator = Validator::make($request->all(), [
            'name' => 'required|max:200|unique:companies',
            'company_phone_numbers' => 'required|min:1|numericarray|numericphonearray',
            'website' => 'sometimes|nullable|validwebsite',
            'company_logo'=>'sometimes|nullable|image|mimes:jpg,jpeg,png|max:500000',
            'monday.from_time'=>'required_with:monday_checked|sometimes|nullable|date_format:H:i',
            'monday.to_time'=>'required_with:monday.from_time|sometimes|nullable|date_format:H:i',
            'tuesday.from_time'=>'required_with:tuesday_checked|sometimes|nullable|date_format:H:i',
            'tuesday.to_time'=>'required_with:tuesday.from_time|sometimes|nullable|date_format:H:i',
            'wednesday.from_time'=>'required_with:wednesday_checked|sometimes|nullable|date_format:H:i',
            'wednesday.to_time'=>'required_with:wednesday.from_time|sometimes|nullable|date_format:H:i',
            'thursday.from_time'=>'required_with:thursday_checked|sometimes|nullable|date_format:H:i',
            'thursday.to_time'=>'required_with:thursday.from_time|sometimes|nullable|date_format:H:i',
            'friday.from_time'=>'required_with:friday_checked|sometimes|nullable|date_format:H:i',
            'friday.to_time'=>'required_with:friday.from_time|sometimes|nullable|date_format:H:i',
            'saturday.from_time'=>'required_with:saturday_checked|sometimes|nullable|date_format:H:i',
            'saturday.to_time'=>'required_with:saturday.from_time|sometimes|nullable|date_format:H:i',
            'sunday.from_time'=>'required_with:sunday_checked|sometimes|nullable|date_format:H:i',
            'sunday.to_time'=>'required_with:sunday.from_time|sometimes|nullable|date_format:H:i',
            'facebook_url' => 'sometimes|nullable|validwebsite',
            'twitter_url' => 'sometimes|nullable|validwebsite',
            'instagram_url' => 'sometimes|nullable|validwebsite',
            'yelp_url' => 'sometimes|nullable|validwebsite',
            'pinterest_url' => 'sometimes|nullable|validwebsite',
            'contact_name' => 'sometimes|nullable||regex:/^[\pL\s\-]+$/u',
            'contact_phone' => 'sometimes|nullable||numeric|digits_between:10,10',
            'contact_email' => 'sometimes|nullable||email',
        ], [
            'company_name.required' => "Company Name is required",
            'company_phone_numbers' => 'Company Phone Number is required',
        ]);

         
           //echo '<pre>';
          // print_r($validator->errors()->all());die();

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
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
             $company->user_id=auth()->user()->id;
             if($company->save()){
                $company_id=$company->id;
                 $activity_log = new ActivityLog;
                 $activity_log->type='COMPANY CREATED';
                 $activity_log->description= $company->name.' - Added';
                 $activity_log->user_id=auth()->user()->id;
                 $activity_log->save();
                //upload image
            if ($request->hasFile('company_logo')){ 
             
                    $company_logo = Company::find($company_id);
                   
                    $file = request()->file('company_logo');
                    $img = Image::make($request->company_logo)->resize(200,null,function ($constraint) {
                    $constraint->aspectRatio();
                    })->crop(150,150);
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
             \Session::flash('success_msg', 'Business Profile is successfully added!');
			 
             return response()->json(['success'=>'Business Profile is successfully added']);
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
        $title=$company->name;
        $menu_meta = MenuMeta::all();
        $menu_keys = MenuKeys::all();
        $company_phone_numbers = CompanyPhoneNumbers::where('status', 1)->where('company_id', $id)->paginate(10);
        return view('company.show',compact('company','menu_meta','menu_keys','title','company_phone_numbers'));
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
        $title='Update '.$company->name;
        $company->company_social_urls=json_decode($company->social_urls,true);
        $company->company_working_hours=json_decode($company->working_hours,true);
       // $company->company_phone_numbers=explode(",",$company->phone_numbers);
        $company_contact_numbers = \App\CompanyPhoneNumbers::where('company_id',$id)->where('status',1)->get();
        $company->company_phone_numbers=$company_contact_numbers;
        $menu_meta = MenuMeta::all();
        $menu_keys = MenuKeys::all();
        return view('company.edit',compact('company','menu_meta','menu_keys','title'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
         
           
      $tier_count=count($request->block)-1;
        $menu_details=array();
        if(isset($request->block[0]['main_menu']))
        foreach($request->block as $tier_val){
            if(isset($tier_val['main_menu'])){
            $dial_tree_menus=$tier_val['main_menu'];
            $tier_val['tier_child']=array_filter($tier_val['tier_child']); 
            if($tier_val['tier_label']==''){
                foreach($tier_val['main_menu'] as $menu_key=>$menu_val){
              
                    $menu_details=MenuKeys::find($menu_key);
                    $i=$menu_details->id;
                    $main_menu_details[$i]['id']=$menu_details->id;
                    $main_menu_details[$i]['name']=$menu_details->name;
                    $main_menu_details[$i]['slug']=$menu_details->slug;
                    $main_menu_details[$i]['button_number']=$menu_details->button_number;
                    if(isset($tier_val['sub_menu'][$menu_key])){
                       $main_menu_details[$i]['submenu_slug']=$tier_val['sub_menu'][$menu_key];
                    }else{ 
                        $tier_val['sub_menu'][$menu_key]=$main_menu_details[$i]['submenu_slug']='split';
                    }
                    
                    if($tier_val['sub_menu'][$menu_key]=='dial_number'){
                        $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        
                    }elseif($tier_val['sub_menu'][$menu_key]=='spanish'){
                        $main_menu_details[$i]['submenu_label']='N/A';
                    }else{
                        $main_menu_details[$i]['label']=$tier_val['sub_menu_label'][$menu_key];
                        //
                        if($tier_val['tier_child'][$menu_key] && isset($request->block[$tier_val['tier_child'][$menu_key]])){
                             $child_details=$request->block[$tier_val['tier_child'][$menu_key]];
                             //first start
                             $x=0;
							 if(isset($child_details['main_menu'])){
                             foreach($child_details['main_menu'] as $child_menu_key=>$menu_val){
                                    $menu_child_details=MenuKeys::find($child_menu_key);
                                    $j=$menu_child_details->id;
                                    $main_menu_details[$i]['list'][$x]['name']=$menu_child_details->name;
                                    $main_menu_details[$i]['list'][$x]['slug']=$menu_child_details->slug;
                                    $main_menu_details[$i]['list'][$x]['button_number']=$menu_child_details->button_number;
									
									
						$main_menu_details[$i]['list'][$x]['label']=isset($child_details['sub_menu_label'][$j])?$child_details['sub_menu_label'][$j]:'';
                                      
                                    if(isset($child_details['sub_menu'][$j])){
                                       $main_menu_details[$i]['list'][$x]['submenu_slug']=$child_details['sub_menu'][$j];
                                    }else{ 
                                        $child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
                                    }
                                    
                                    $main_menu_details[$i]['list'][$x]['slug']=$child_details['sub_menu'][$j];

									if($child_details['sub_menu'][$j]=='dial_number'){ 
									
                                        $main_menu_details[$i]['list'][$x]['label']=$child_details['sub_menu_label'][$j];
                                        
                                    }elseif($child_details['sub_menu'][$j]=='spanish'){
                                        $main_menu_details[$i]['list'][$x]['submenu_label']='N/A';
                                    }else{ 
									 $main_menu_details[$i]['list'][$x]['label']=isset($child_details['sub_menu_label'][$j])?$child_details['sub_menu_label'][$j]:'';
                                     
									//second tier start
										$second_tier=array_filter($child_details['tier_child']); 
										if(isset($second_tier[$child_menu_key])){
											$second_child_details=$request->block[$second_tier[$child_menu_key]];
											$y=0;
											if(isset($second_child_details['main_menu'])){
											foreach($second_child_details['main_menu'] as $second_child_menu_key=>$second_menu_val){
											  
												$second_menu_child_details=MenuKeys::find($second_child_menu_key);
												$z=$second_menu_child_details->id;
												
												$main_menu_details[$i]['list'][$x]['list'][$y]['name']=$second_menu_child_details->name;
												$main_menu_details[$i]['list'][$x]['list'][$y]['slug']=$second_menu_child_details->slug;
												$main_menu_details[$i]['list'][$x]['list'][$y]['button_number']=$second_menu_child_details->button_number;
												if(isset($second_child_details['sub_menu'][$second_child_menu_key])){
												   $main_menu_details[$i]['list'][$x]['list'][$y]['submenu_slug']=$second_child_details['sub_menu'][$second_child_menu_key];
												}else{ 
													$second_child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
												}
												
												if($second_child_details['sub_menu'][$second_child_menu_key]=='dial_number'){
													$main_menu_details[$i]['list'][$x]['list'][$y]['label']=$second_child_details['sub_menu_label'][$second_child_menu_key];
													
												}elseif($second_child_details['sub_menu'][$second_child_menu_key]=='spanish'){
													$main_menu_details[$i]['list'][$x]['list'][$y]['submenu_label']='N/A';
												}else{ 
													$main_menu_details[$i]['list'][$x]['list'][$y]['label']=$second_child_details['sub_menu_label'][$second_child_menu_key];
													$main_menu_details[$i]['list'][$x]['list'][$y]['label']=$second_child_details['sub_menu_label'][$child_menu_key];
													//third tier start													
														$third_tier=array_filter($second_child_details['tier_child']); 
															if(isset($third_tier[$second_child_menu_key] )){
															 $third_child_details=$request->block[$third_tier[$second_child_menu_key]];
															 $p=0;
															 if($third_child_details['main_menu']){
															 foreach($third_child_details['main_menu'] as $third_child_menu_key=>$third_menu_val){

																	$third_menu_child_details=MenuKeys::find($third_child_menu_key);
																	$q=$third_menu_child_details->id;
																	
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['tier_relation']=0;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['id']=$third_menu_child_details->id;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['name']=$third_menu_child_details->name;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['slug']=$third_menu_child_details->slug;
																	$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['button_number']=$third_menu_child_details->button_number;
																	if(isset($third_child_details['sub_menu'][$third_child_menu_key])){
																	   $main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['submenu_slug']=$third_child_details['sub_menu'][$third_child_menu_key];
																	}else{ 
																		$third_child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
																	}
																	
																	
																	if($second_child_details['sub_menu'][$second_child_menu_key]=='dial_number'){
																		$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['label']=$third_child_details['sub_menu_label'][$third_child_menu_key];
																		
																	}elseif($third_child_details['sub_menu'][$third_child_menu_key]=='spanish'){
																		$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['submenu_label']='N/A';
																	}else{ 
																		$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['label']=$third_child_details['sub_menu_label'][$third_child_menu_key];
																		//fouth child start
																				
																			$fourth_tier=array_filter($third_child_details['tier_child']); 
																				if(isset($fourth_tier[$third_child_menu_key]) ){
																				 $fourth_child_details=$request->block[$fourth_tier[$third_child_menu_key]];
																				 $r=0;
																				 if(isset($fourth_child_details['main_menu'])){
																				 foreach($fourth_child_details['main_menu'] as $fourth_child_menu_key=>$foutyh_menu_val){

																						$fourth_menu_child_details=MenuKeys::find($fourth_child_menu_key);
																						$q=$fourth_menu_child_details->id;
																						$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['name']=$fourth_menu_child_details->name;
																						$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['slug']=$fourth_menu_child_details->slug;
																						$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['button_number']=$fourth_menu_child_details->button_number;
																						if(isset($fourth_child_details['sub_menu'][$fourth_child_menu_key])){
																						   $main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['submenu_slug']=$fourth_child_details['sub_menu'][$fourth_child_menu_key];
																						}else{ 
																							$fourth_child_details['sub_menu'][$j]=$main_menu_details[$j]['submenu_slug']='split';
																						}
																						
																						
																						if($third_child_details['sub_menu'][$third_child_menu_key]=='dial_number'){
																							$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['label']=$fourth_child_details['sub_menu_label'][$fourth_child_menu_key];
																							
																						}elseif($fourth_child_details['sub_menu'][$fourth_child_menu_key]=='spanish'){
																							$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['submenu_label']='N/A';
																						}else{ 
																							$main_menu_details[$i]['list'][$x]['list'][$y]['list'][$p]['list'][$r]['label']=$fourth_child_details['sub_menu_label'][$fourth_child_menu_key];
																						
																							
																						}
																					 $r=$r+1;
																					} 
																				}
																			}else{
																				
																			}												
																		//fourth child end
																		
																	}
																 $p=$p+1;
																} 
															}
														}

														//third tier end
													  
												}
											 $y=$y+1;
											} 
										}
										}

									//second tier end
                                        
                                    }
                                 $x=$x+1;
						} }
                             //end
                        }else{
							//error
							return response()->json(['errors'=>'Error Occured please try again later!']);
							 \Session::flash('error_msg', 'Error Occured please try again later!');
							 return redirect('/home');
							 return redirect()->back()->withInput();
						}
                    
                       
                    }
                 
                }
                
            }else{
               
                
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
            return response()->json(['errors'=>'Error Occured please try again later!']);
             \Session::flash('error_msg', 'Error Occured please try again later!');
			 return redirect('/home');
             return redirect()->back()->withInput();

        }
            
        }else{
        $menu_json='';
        $menu_array_json='';
     }
        $id=$request->post('id'); 
        $this->validate($request, [
            'name' => Rule::unique('companies')->ignore($id),
        ]);
        
         $validator = Validator::make($request->all(), [
            'name' => 'required|max:200|unique:companies,id,:id',
            'company_phone_numbers' => 'required|min:1|numericarray|validphonenumber:id,'.$id,
            'website' => 'sometimes|nullable|validwebsite',
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
            'facebook_url' => 'sometimes|nullable|validwebsite',
            'twitter_url' => 'sometimes|nullable|validwebsite',
            'instagram_url' => 'sometimes|nullable|validwebsite',
            'yelp_url' => 'sometimes|nullable|validwebsite',
            'pinterest_url' => 'sometimes|nullable|validwebsite',
            'contact_name' => 'sometimes|nullable||regex:/^[\pL\s\-]+$/u',
            'contact_phone' => 'sometimes|nullable||numeric|digits_between:10,10',
            'contact_email' => 'sometimes|nullable||email',
        ], [
            'company_name.required' => "Company Name is required",
            'company_phone_numbers' => 'Company Phone Number is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
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
                CompanyPhoneNumbers::where('company_id', '=', $company_id)->update(['status' => 0]);
				
                foreach($company_contact_numbers as $numbers){
					
                    // $company_numbers = CompanyPhoneNumbers::find($numbers);
                    $company_numbers=CompanyPhoneNumbers::where('phone_number', $numbers)->first();
					//var_dump($numbers);die();
					//var_dump($company_numbers);die();
                    if($company_numbers){
                        $company_numbers->company_id = $company_id;
                        $company_numbers->phone_number=$numbers;
                        $company_numbers->status=1;
                        $company_numbers->save();
                    }else{
                        $company_numbers = new \App\CompanyPhoneNumbers;
                        $company_numbers->company_id = $company_id;
                        $company_numbers->phone_number=$numbers;
                        $company_numbers->save();
                    }
                    
                }   
            }
            \Session::flash('success_msg', 'Business Profile is successfully updated!');
			return response()->json(['success'=>'Business Profile is successfully added']);

			 }else{
				 \Session::flash('error_msg', 'sdError Occured please try again later!');
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
        $company = Company::find($id);
        $company->delete();
        DB::table('company_phone_numbers')->where('company_id', $id)->delete();
         \Session::flash('success_msg','Business Profile Removed Successfully!');
         return redirect()->route('company.index');
    }
     public function filter(Request $request, Company $company)
    {
         // Search for a user based on their company.
        if ($request->has('company')) {
            return $Company->where('name', $request->input('company'))
                ->get();
        }
        return Company::all();
    }
    public function menu(Request $request){
       $id=$request->get('id','');
       $btn_id=$request->get('btn_id','');
       $tier_title=$request->get('tier_title','');
       $tier_heading=$request->get('tier_heading','');
       $menu_parent=$request->get('menu_parent','');
       $tier_parent=$request->get('tier_parent','');
	   $visbiletier=$request->get('visbiletier','');
	   if($tier_heading=='Time Delayed Action'){
		   $display='none';
	   }else{
		   $display='visible';
	   }
       if(isset($id)){
        $tier_key=$id;
         $menu_meta = MenuMeta::all();
         $menu_keys = MenuKeys::all();
         return view('company._partial_menu_template',compact('tier_key','menu_meta','menu_keys','btn_id','tier_title','tier_heading','menu_parent','tier_parent','visbiletier','display'));
       }
        
    }
    
    public function autoComplete(Request $request) {
        $query = $request->get('term','');
        if(is_numeric($query)){
            $phone_numbers=CompanyPhoneNumbers::where('phone_number', $query)->orWhere('phone_number', 'like', '%' . $query . '%')->Where('status',1)->paginate(10);
            $data=array();
            foreach ($phone_numbers as $number) {
                    $data[]=array('value'=>$number->phone_number,'id'=>$number->company_id);
            }
        }else{
            $company_list=Company::where('name', $query)->orWhere('name', 'like', '%' . $query . '%')->orWhere('phone_numbers', 'like', '%' . $query . '%')->paginate(10);
            $data=array();
            foreach ($company_list as $company) {
                    $data[]=array('value'=>$company->name,'id'=>$company->id);
            }
        }
        
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }
   
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callhistory($id)
    { 
        $title='Call History : '.$id;
        $phone_number=$id;
        $dial_list =DialLog::select('dialed_from')->where('dialed_to',$phone_number)->groupBy('dialed_from')->get()->toArray() ;
		$dial_history = DB::select("SELECT dialed_from, COUNT(dialed_from)as dialcount FROM dial_log WHERE dialed_to=".$phone_number." GROUP BY dialed_from HAVING COUNT(dialed_from) > 1");

        return view('company.callhistory',compact('phone_number','dial_list','title','dial_history'));
    }
    
    public function autoFilter(Request $request) {
	
        $query = $request->post('filter_name');
		$draw=$request->post('draw');
		$skip=$request->post('start',0);
		$limit=10;

		$totalRecords= Company::where('status', '=',1 )->get();
        if($query){
            $totalRecordsWithFilter= Company::where('name', $query)->orWhere('name', 'like', '%' . $query . '%')->orWhere('phone_numbers', 'like', '%' . $query . '%')->get();
            $company_list=Company::where('name', $query)->orWhere('name', 'like', '%' . $query . '%')->orWhere('phone_numbers', 'like', '%' . $query . '%')->orderBy('id','DESC')->skip($skip)->take($limit)->get();
            $data=array();
            foreach ($company_list as $company) {
                    $phone_numbers=explode(",", $company->phone_numbers);
					 if($phone_numbers){
						
						if(count($phone_numbers) >1){
							$phone=$phone_numbers[0]." <a href='".route('company.edit',$company->id)."' class='btn-link btn-link-sm more-link'>More</a>";
						}else{
							$phone=$phone_numbers[0];
						}
					 }else{
						$phone=$company->phone_numbers;
					 }
					 $delete="  <button class='btn btn-danger btn-xs delete' data-toggle='modal' data-companyid='".$company->id."' data-target='#deleteModal' data-url='". url('/company/delete/'.$company->id)."'>Remove</button>";
                   $company_name="<a href='".url('company/profile/'.$company->id)."'>$company->name</a>";
				   $data[]=array('id'=>$company->id,'company'=>$company_name,"phone_number"=>$phone,"action"=>$delete);
            }
        }else{
            $totalRecordsWithFilter= Company::where('status', '=',1 )->orderBy('id','DESC')->get();
            $company_list=Company::where('status', '=',1 )->orderBy('id','DESC')->skip($skip)->take($limit)->get();
            foreach ($company_list as $company) { 
					$phone_numbers=explode(",", $company->phone_numbers);
					 if($phone_numbers){
						
						if(count($phone_numbers) >1){
							$phone=$phone_numbers[0]." <a href='".route('company.edit',$company->id)."' class='btn-link btn-link-sm more-link'>More</a>";
						}else{
							$phone=$phone_numbers[0];
						}
					 }else{
						$phone=$company->phone_numbers;
					 }
					 $delete="  <button class='btn btn-danger btn-xs delete' data-toggle='modal' data-companyid='".$company->id."' data-target='#deleteModal' data-url='". url('/company/delete/'.$company->id)."'>Remove</button>";
                     $company_name="<a href='".url('company/profile/'.$company->id)."'>$company->name</a>";
					$data[]=array('id'=>$company->id,'company'=>$company_name,"phone_number"=>$phone,"action"=>$delete);
            }
        }
         
		$response = array(
		  "draw" => intval($draw),
		  "iTotalRecords" => count($totalRecords),
		  "iTotalDisplayRecords" => count($totalRecordsWithFilter),
		  "aaData" => $data
		);

		echo json_encode($response);
    }
}