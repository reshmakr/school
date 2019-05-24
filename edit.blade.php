@extends('layouts.dashboard')
@section('content')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>

<link rel="stylesheet" href="https://rawgit.com/jonthornton/jquery-timepicker/master/jquery.timepicker.css">
<style>
#tierlabel_repeat_0{
  display:none;
}
</style>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular.min.js"></script>
  <script type="text/javascript">
 var app = angular.module('MyApp', [])
        app.controller('MyController', function ($scope,$location,$anchorScroll) {
            
                $scope.fieldGroups = [{ }];

                 $scope.getName = function ( $index) {
                  return $scope.name + '_' + $index + '_tier';
                }
                  $scope.getMenuName = function ( $index,$menu_id) {
                  return $scope.name + '[' + $index + '][main_menu]['+$menu_id+']';
                }
                  $scope.getMenuId = function ( $index,$menu_id) {
                  return  'menu_' + $index + '_'+$menu_id+'';
                }
                  $scope.getMenuSpanishClass = function ( $index,$menu_id) {
                  return  'menu_spanish_' + $index + '_'+$menu_id+'';
                }
                
                  $scope.getSubMenuName = function ( $index,$menu_id) {
                  return $scope.name + '[' + $index + '][sub_menu]['+$menu_id+']';
                }
                  $scope.getSubMenuLabel = function ( $index,$menu_id) {
                  return $scope.name + '[' + $index + '][sub_menu_label]['+$menu_id+']';
                }
                  $scope.getDataId = function ( $index) {
                  return  $index;
                }
                
                $scope.getTierLabel = function ($index) {
                   
                  return $scope.name + '[' + $index + '][tier_label]';
                }
                $scope.clone = function(index_id) {
                  $scope.fieldGroups.push({}); 
                  $location.hash('repeat_'+(index_id+1));
                  $anchorScroll();
                }
               $scope.clickEvent = function(obj) {
                
                  var el =$('.'+obj.target.attributes.class.value);
                  el.attr('disabled', 'disabled'); 
                  var idel =$('#'+obj.target.attributes.id.value);
                  idel.removeAttr('disabled'); 
                  $scope.fieldGroups.push({}); 
                  var indexid=parseFloat(obj.target.attributes.data.value)+1;
                  //console.log(indexid);
                  var tiername='block_'+indexid+'_tier';
                  $scope.tiername = obj.target.attributes.id.value;
                
                  //focus
                  $location.hash('repeat_'+(indexid));
                  $anchorScroll();      
              }
              $scope.addAttr = function(dial_number_id,spanish_id) {
              }

                $scope.delete = function(fieldGroup) { 
                   var index = $scope.fieldGroups.indexOf(fieldGroup)+1;
                     if($scope.fieldGroups.length!=index)
                        $scope.fieldGroups.splice(index, 1);    

                }
                $scope.tierName = function(id) {
                     if(id==0){
                        return 'Home';
                       }
                        return 'Tier '+id;
                       
                   };
                     

        });
      

 $(document).ready(function(){ 
        $(document).on('submit','#company-form',function(){
          var validation_error=false;
         $('input.checkbox-button:checkbox:checked').each(function () {
              var sThisVal = $(this).val();
              var input_btn_id=$(this).attr('btn-id');
              var spanish_btn_id=$(this).attr('spanish-btn-id');
              if ($('[btn-id="'+input_btn_id+'"]').is(":empty") && $("#"+spanish_btn_id).is(':checked')==false) {
               console.log(('[btn-id="'+input_btn_id+'"]').is(":empty"));
               console.log($("#"+spanish_btn_id).is(':checked')==false);
               validation_error=true;
              }
              
          });

         if(validation_error){
          $('#error-msg').html('<div class="alert alert-danger"><ul><li>DialTree options are missing</li></ul></div>');
          $( "#error-msg" ).focus();
          return false;
         }
         
        });
       //checkbox change
        $( document ).on( "change", ".checkbox-button", function () {


          var ischecked= $(this).is(':checked');
          if(!ischecked){
             console.log($(this).parent().parent().siblings('.row').removeAttr('ng-hide'));
console.log($(this).parent().parent().siblings('.row').toggleClass('ng-hide'));
            var splitcheckbutton=$(this).parent().parent().siblings('.row').children().children().siblings('.form-check').children().children().eq(1);

            if($(this).val()=='time_delayed_action'){
              var tierid=$(this).data("id");
              var tm_split=$(this).parent().parent().siblings('.row').children().children().siblings('.form-check').children().children();
              if($(tm_split).is(':checked')){
                 var next_tier_id=parseFloat(tierid)+1;
                    $('#repeat_'+next_tier_id).remove();
                    $('#repeat_'+tierid).find('.split_radio_button').removeAttr('disabled');
                 $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
              }
             
            }
            if($(splitcheckbutton).is(':checked')){
               var tierid=$(this).data("id");
               $('#repeat_'+tierid).find('.split_radio_button').removeAttr('disabled');
              $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
            }
            $(this).siblings("input:radio[disabled=false]:first").attr('checked', true);
            var closeClassName=$(this).data('class'); 
             $('.'+closeClassName).each(function() { 
               $(this).prop('checked',false);
               $(this).removeAttr('disabled');
             });
            if($(this).parent().parent().siblings('.row').children().children("input:first").hasClass('radiobuttons')){
              var tierid=$(this).data("id");
              var next_tier_id=parseFloat(tierid)+1;
              $('#repeat_'+next_tier_id).remove();
              $(this).parent().parent().siblings('.row').children().children("input:first").toggleClass("radiobuttons");
              
           }
          }else{
           
          }
          
      }); 
          //radio change
           $( document ).on( "change", ".rd",function(e){ 
              if($(this).val() == 'spanish') {
                  var thatInput=$(this).parent().parent().parent().children("input:first").val('N/A');
                  $(this).parent().parent().parent().children("input:first").attr('disabled', 'disabled'); 
                  if($(this).parent().parent().parent().children("input:first").hasClass('radiobuttons')){
                    var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                   var next_tier_id=parseFloat(tierid)+1;
                    $('#repeat_'+next_tier_id).remove();
                    $(this).parent().parent().parent().children("input:first").toggleClass("radiobuttons");
                    $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                  }
                  
              } else if($(this).val() == 'split'){ 
                $(this).attr('checked','checked');
               
                var thatInput=$(this).parent().parent().parent().children("input:first").val();
                $(this).parent().parent().parent().children("input:first").addClass("radiobuttons");
                  $(this).parent().parent().parent().children("input:first").removeAttr('disabled');
                  var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                  var next_tier_id=parseFloat(tierid)+1;

                  $('#hidden_repeat_'+next_tier_id).val($(this).attr('id'));
                 // console.log($('#h3_repeat_'+next_tier_id).text($(this).attr('databutton')));
                    
                   $('#tierlabel_repeat_'+next_tier_id).text(thatInput);

                  $('#repeat_'+tierid+' .split_radio_button').attr('disabled','disabled');
              }else {
                  var thatInput=$(this).parent().parent().parent().children("input:first").val('');
                  $(this).parent().parent().parent().children("input:first").removeAttr('disabled');
                  if($(this).parent().parent().parent().children("input:first").hasClass('radiobuttons')){
                     var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                  
                   var next_tier_id=parseFloat(tierid)+1;
                    $('#repeat_'+next_tier_id).remove();
                     $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                    $(this).parent().parent().parent().children("input:first").toggleClass("radiobuttons");
                    
                  }
              }

          });
         

      });
    </script>
    <script>
     /* $(document).ready(function(){ 
       
       //checkbox change
       $(".checkbox-button").change(function() { 
        alert('checked');
        console.log($(this).is(':checked'));
          var ischecked= $(this).is(':checked');
          if(!ischecked){
            
            $(this).siblings("input:radio[disabled=false]:first").attr('checked', true);
           //ng-hide

           // alert('uncheckd ' + $(this).val());
            
           // console.log($(this).parent().parent().children("input:first").data("id"));
 console.log($(this).parent().parent().siblings('.row').removeAttr('ng-hide'));
console.log($(this).parent().parent().siblings('.row').toggleClass('ng-hide'));
           if($(this).parent().parent().siblings('.row').children().children("input:first").hasClass('radiobuttons')){
              var tierid=$(this).data("id");
              var next_tier_id=parseFloat(tierid)+1;
              console.log(next_tier_id);
              console.log('#repeat_'+next_tier_id);
              console.log($('#repeat_'+next_tier_id).remove());
              $(this).parent().parent().siblings('.row').children().children("input:first").toggleClass("radiobuttons");
              console.log($(this).parent().parent().siblings('.row').children().children(".rd:first"));
              $(this).parent().parent().siblings('.row').children().children(".rd:first").attr('checked',true);
           }
          }else{
           
          }
          
      }); 
          //radio change
          $(".rd").change(function(e){
              if($(this).val() == 'spanish') {
                  var thatInput=$(this).parent().parent().parent().children("input:first").val('N/A');
                  $(this).parent().parent().parent().children("input:first").attr('disabled', 'disabled'); 
                  if($(this).parent().parent().parent().children("input:first").hasClass('radiobuttons')){
                    var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                   var next_tier_id=parseFloat(tierid)+1;
                   console.log(next_tier_id);
                   console.log('#repeat_'+next_tier_id);
                    console.log($('#repeat_'+next_tier_id).remove());
                    $(this).parent().parent().parent().children("input:first").toggleClass("radiobuttons");
                    $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                  }
                  
              } else if($(this).val() == 'split'){
                $(this).attr('checked','checked');
                var thatInput=$(this).parent().parent().parent().children("input:first").val();
                $(this).parent().parent().parent().children("input:first").addClass("radiobuttons");
                  $(this).parent().parent().parent().children("input:first").removeAttr('disabled');
                  var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                  var next_tier_id=parseFloat(tierid)+1;
                   console.log(next_tier_id);
                   console.log('#hidden_repeat_'+next_tier_id);
                  $('#hidden_repeat_'+next_tier_id).val($(this).attr('id'));
                  console.log(thatInput);
                   $('#tierlabel_repeat_'+next_tier_id).val(thatInput);
                  $('#repeat_'+tierid+' .split_radio_button').attr('disabled','disabled');
              }else {
                  var thatInput=$(this).parent().parent().parent().children("input:first").val('');
                  $(this).parent().parent().parent().children("input:first").removeAttr('disabled');
                  if($(this).parent().parent().parent().children("input:first").hasClass('radiobuttons')){
                     var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                  
                   var next_tier_id=parseFloat(tierid)+1;
                   console.log(next_tier_id);
                   console.log('#repeat_'+next_tier_id);
                    console.log($('#repeat_'+next_tier_id).remove());
                     $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                    $(this).parent().parent().parent().children("input:first").toggleClass("radiobuttons");
                    
                  }
              }

          });
         

      });*/
    </script>
<div class="container">
   <div class="row profile">
      <div class="box">
         <div class="box-body">
            <form method="POST" action="{{ route('company.update',$company->id) }}" enctype="multipart/form-data">
                                 {{csrf_field()}}
      <div class="col-md-12">
         @if(Session::has('success_msg'))
         <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
         @endif
         @if(Session::has('error_msg'))
         <div class="alert alert-danger">{{ Session::get('error_msg') }}</div>
         @endif
         @if ($errors->any())
         <div class="alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif
         <div class="panel panel-default">
            <div class="panel-heading">
               </br>
               </br>
            </div>
            <div class="panel-body">
               @if (session('status'))
               <div class="alert alert-success">
                  {{ session('status') }}
               </div>
               @endif
               <div class="bd-example bd-example-tabs">
                  <div class="row">
                     <div class="col-4">
                        <div class="space_50"></div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                           <a class="nav-link active show" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="true">Company Info</a>
                           <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Dial Tree</a>
                        </div>
                     </div>
                     <div class="col-7">
                        <div class="tab-content" id="v-pills-tabContent">
                            
                           <div class="tab-pane fade active show" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                              <div class="text-center">
                                 <h3>Company Profile</h3>
                              </div>
                             
                                 <div class="form-group">
                                    <label for="inputEmail4" class="required">Company Name</label>
                                    <input type="text" class="form-control" id="inputEmail4" placeholder="Company Name" name="name" value="{{ $company->name }}">
                                    <input type="hidden" class="form-control" name="id" value="{{ $company->id }}">
                                 </div>
                                 <div class="">
                                    <label for="inputEmail4" class="required">Company phone Numbers #</label>
                                 </div>
                                 
                                    @if(old('company_phone_numbers'))
                                    
                                    @foreach(old('company_phone_numbers') as $phone_numbers =>$phone_number)
                                   
                                    <div  class="form-row">
                                       <div class="form-group col-md-8">
                                          <div>
                                             <input type='tel' class='form-control company_phone_number' id='inputEmail4' placeholder='Company phone Numbers #' name="company_phone_numbers[<?php echo $phone_numbers; ?>]" value="{{ $phone_number }}" maxlength="10" />
                                          </div>
                                       </div>
                                       <div class="form-group col-md-4 tn-buttons">
                                          <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i class="fa fa-plus"></i></button>
                                          @if($loop->index >0)
                                          <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i class="fa fa-remove"></i></button>
                                          @endif
                                       </div>
                                    </div>
                                    
                                    @endforeach
                                
                                    @else
                                    @if($company->company_phone_numbers)
                                    
                                    @foreach($company->company_phone_numbers as $phone_numberkey=>$phone_number)
                                     
                                     <div class="form-row">
                                       <div class="form-group col-md-8">
                                          <div>
                                             <input type='tel' class='form-control company_phone_number' id='inputEmail4' placeholder='Company phone Numbers #' name="company_phone_numbers[{{$phone_number->id}}]" value="{{ $phone_number->phone_number }}" maxlength="10" />
                                          </div>
                                       </div>
                                       <div class="form-group col-md-4 tn-buttons">
                                          <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i class="fa fa-plus"></i></button>
                                          @if($loop->index >0)
                                          <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i class="fa fa-remove"></i></button>
                                          @endif
                                       </div>
                                     </div>
                                     
                                    
                                    @endforeach
                                 
                                    @endif
                                    @endif
                                 
                                 <div  id="packagingappendhere"></div>
                                 <div class="form-group">
                                    <label for="inputAddress">Address 1</label>
                                    <input type="text" class="form-control" id="inputAddress" placeholder="Address 1" name="company_address1"  value="{{ $company->address1 }}" >
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Address 2</label>
                                    <input type="text" class="form-control" name="company_address2" id="inputAddress2" placeholder="Address 2"  value="{{ $company->address2 }}" >
                                 </div>
                                 <div class="form-row">
                                    <div class="form-group col-md-3">
                                       <label for="inputCity">City</label>
                                       <input name="city" type="text" class="form-control" id="inputCity"  value="{{ $company->city }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                       <label for="inputState">State</label>
                                       <input name="state"  value="{{ $company->state }}" type="text" class="form-control" id="inputCity" >
                                       <!-- <select id="inputState"name="state"  class="form-control">
                                          <option selected>Choose...</option>
                                          <option>...</option>
                                          </select> -->
                                    </div>
                                    <div class="form-group col-md-3">
                                       <label for="inputZip">Zip Code</label>
                                       <input name="zipcode" value="{{ $company->zip }}"type="text" class="form-control" id="inputZip" >
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputZip">Website</label>
                                    <input name="website" value="{{ $company->website }}" type="text" class="form-control" id="inputWebsite" >
                                 </div>
                                 <div class="form-row">
                                    <div class="form-group col-md-6">
                                       <label>Logo</label>
                                       <div class="input-group">
                                          <span class="input-group-btn">
                                          <span class="btn btn-default btn-file">
                                          Browse… <input  value="" type="file" id="imgInp" name="company_logo">
                                          </span>
                                          </span>
                                          <input type="text" value="{{ $company->thumb }}" class="form-control" readonly>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <img id='img-upload' width='30%'/>
                                       @if($company->thumb!='')
                                        <img src="{{ asset($company->thumb) }}" width='30%' class="img-thumbnail" alt="Company Logo" id="img_thumb">
                                        @endif
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="InputcompanyHours">Hours</label>
                                    <?php foreach($company->company_working_hours as $working_hours_key=>$working_hours_val){
                                     ?>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input" <?php if($working_hours_val['from_time']!=''){ echo 'checked'; }?>>
                                                </div>
                                             </div>
                                             <input type="text" readonly value="{{ $working_hours_key }}" class="form-control working_week" aria-label="Text input with checkbox"  >
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="{{$working_hours_key}}[from_time]" placeholder="09:00" autocomplete="off" value="{{ $working_hours_val['from_time'] }}" >
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="{{$working_hours_key}}[to_time]" value="{{ $working_hours_val['to_time'] }}" type="text" class="form-control to_time" placeholder="17:00" autocomplete="off" >
                                          </div>
                                       </div>
                                    </div>
                                    <?php } ?>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Social Media Links</label>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Facebook" aria-label="Username" aria-describedby="basic-addon1" value="{{ $company->company_social_urls['facebook'] }}" name="facebook_url" >
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-instagram fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Instagram" aria-label="Username" aria-describedby="basic-addon1" value="{{ $company->company_social_urls['instagram'] }}" name="instagram_url" >
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-yelp fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Yelp" aria-label="Username" aria-describedby="basic-addon1" value="{{ $company->company_social_urls['yelp'] }}" name="yelp_url" >
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Twitter" aria-label="Username" aria-describedby="basic-addon1" value="{{ $company->company_social_urls['twitter'] }}" name="twitter_url" >
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-pinterest fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Pinterest" aria-label="Username" aria-describedby="basic-addon1" value="{{ $company->company_social_urls['pinterest'] }}" name="pinterest_url" >
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Contact Name</label>
                                    <input name="contact_name" value="{{ $company->contact_name }}"  type="text" class="form-control" id="inputAddress2" placeholder="Contact Name" >
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Contact Phone</label>
                                    <input name="contact_phone" value="{{ $company->contact_phone }}" type="text" class="form-control" id="inputAddress2" placeholder="Contact Phone" >
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Contact Email</label>
                                    <input type="text" name="contact_email" value="{{ $company->contact_email }}" class="form-control" id="inputAddress2" placeholder="Contact Email" >
                                 </div>
                                 <button type="submit" class="btn btn-primary">Save</button>
                              
                           </div>
                           <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                              <div ng-app="MyApp" >
                        <div ng-controller="MyController" ng-init="name = 'block'">
                                 <div class=" menu_button-group">
                           <div class="row text-center flex-nowrap">
                            <?php
                             $tier_list=json_decode($company->menu_array_json,true);
                             $tier_list=(array)$tier_list;
                             $index=0;
                             foreach($tier_list as $tier_list_key =>$tier_list_val){ //echo '<pre>';print_r($tier_list); die();?>
                            <div class="col-sm-6 ng-scope" id="<?php echo 'repeat_{{$index}}';?>" ng-set-focus="<?php echo 'repeat_{{$index}}';?>" style="border-right: 2px solid #eee;
    margin-right: 10px;">

                               <h3><?php if($tier_list_key==0){ echo 'Home';}else{ echo 'Tier '.$tier_list_key;} ?></h3>
                              <input type="hidden" name="<?php echo '{{ getTierLabel('.$tier_list_key.') }}';?>" id="hidden_repeat_{{$tier_list_key}}" value="<?php if(isset($split_tier_value)){ echo $split_tier_value; }?>">
                              <?php 
                              if(isset($split_tier_label)){?>
                                <input type="text" class="form-control form-control-sm "name="tier_label[]" value="<?php echo $split_tier_label; ?>">
                               <br/>
                              <?php }

                              foreach($menu_keys as $menu_items=>$menu_item){
                                 
                               $button_id=$menu_item->id; ?>
                              
                              <div id="<?php echo 'parent_{{$index}}';?>">
                               <div class="form-group row">
                                <label class="switch control-label col-md-2" >
                                  <?php if(isset($tier_list_val['main_menu'][$menu_item->id]) && $tier_list_val['main_menu'][$menu_item->id]==$menu_item->slug){ ?>
                                  <input class="switch-input checkbox-button" type="checkbox" data="<?php echo '{{$index}}';?>" value="{{ $menu_item->slug }}"   data-size="small"  ng-true-value="menu_{{$tier_list_key}}_{{ $menu_item->id }}" ng-false-value="on" ng-model="check_{{$tier_list_key}}_{{ $menu_item->id}}" name=" <?php echo '{{ getMenuName('.$tier_list_key.','.$menu_item->id.') }}';?>" data="{{$tier_list_key}}"  ng-init="check_{{$tier_list_key}}_{{ $menu_item->id}} = menu_{{$tier_list_key}}_{{ $menu_item->id }}" id="{{$tier_list_key}}_{{$menu_item->slug}}" ng-checked="true">
                                  <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> 
                                  <?php }else{?> 
                                       <input class="switch-input" type="checkbox" data="{{$tier_list_key}}" value="{{ $menu_item->slug }}" data-size="small"  ng-true-value="menu_{{$tier_list_key}}_{{ $menu_item->id }}" ng-false-value="on" ng-model="check_{{$tier_list_key}}_{{ $menu_item->id}}" name=" <?php echo '{{ getMenuName('.$tier_list_key.','.$menu_item->id.') }}';?>"  ng-true-value="YES" ng-false-value="NO" data="<?php echo '{{$index}}';?>" ng-init="check_{{$tier_list_key}}_{{ $menu_item->id}}=menu_{{$tier_list_key}}_{{ $menu_item->id }}" />
                                      <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> 
                                  <?php } ?>
                                </label>
                                <label class="col-md-3">&nbsp;&nbsp; <?php echo $menu_item->name;?></label>
                                </div>
                              <div class="clearfix"></div>
                                <div ng-show="check_{{$tier_list_key}}_{{ $menu_item->id}} == 'menu_{{$tier_list_key}}_{{ $menu_item->id}}'" class="row"  <?php if(isset($tier_list_val['main_menu'][$menu_item->id]) && $tier_list_val['main_menu'][$menu_item->id]==$menu_item->slug){ echo 'ng-hide="false"'; }?> >
                                <?php if(isset($tier_list_val['main_menu'][$menu_item->id]) && $tier_list_val['main_menu'][$menu_item->id]==$menu_item->slug){ ?>
                                <div class="col-md-7 offset-md-2">
                                 <input type="text" id="txtPassportNumber" placeholder="add label" name="<?php echo '{{ getSubMenuLabel('.$tier_list_key.','.$menu_item->id.') }}';?>" class="form-control form-control-sm"  value="<?php echo $tier_list_val['sub_menu_label'][$menu_item->id]; ?>" />
                                 <?php foreach($menu_meta as $meta =>$meta_value){ 

                                  ?>
                                   <div class="form-check">
                                    <label class="form-check-label">
                                     @if($meta_value->slug=='dial_number')
                                        <input type="radio"  value="dial_number"  class="form-check-input" name="<?php echo '{{ getSubMenuName('.$tier_list_key.','.$menu_item->id.') }}';?>" ng-click="delete(fieldGroup)" <?php if(isset($tier_list_val['sub_menu_label'][$menu_item->id]) && isset($tier_list_val['sub_menu'][$menu_item->id]) && $tier_list_val['sub_menu'][$menu_item->id]=='dial_number'){ echo 'checked ';echo "ng-checked='true' ";} ?> ><?php echo $meta_value->name; ?>
                                     @elseif($meta_value->slug=='split')
                                          <input  type="radio" value="split" class="form-check-input menu_meta_split split_radio_button" <?php if(isset($tier_list_val['sub_menu_label'][$menu_item->id]) && !isset($tier_list_val['sub_menu'][$menu_item->id]) ){ $split_tier_value= 'block_'.$tier_list_key.'_'.$menu_item->id; $split_tier_label=$tier_list_val['sub_menu_label'][$menu_item->id]; echo 'checked ';echo "ng-checked='true' ";} ?> name="<?php echo '{{ getSubMenuName('.$tier_list_key.','.$menu_item->id.') }}';?>"  ><?php echo $meta_value->name; ?>
                                     @else
                                     <input type="radio"  value="spanish" class="form-check-input" name="<?php echo '{{ getSubMenuName('.$tier_list_key.','.$menu_item->id.') }}';?>" <?php if(isset($tier_list_val['sub_menu_label'][$menu_item->id]) && isset($tier_list_val['sub_menu'][$menu_item->id]) && $tier_list_val['sub_menu'][$menu_item->id]=='spanish'){ echo 'checked '; echo "ng-checked='true' ";} ?>  ><?php echo $meta_value->name; ?>
                                          
                                     @endif
                                     
                                    </label>
                                   </div>
                                 <?php } ?>
                                 
                                  <br>
                                </div>
                                <div class="clearfix"></div>
                                <br>
                              <?php }else{?> 
                                 <div class="col-md-7 offset-md-2">
                                 <input type="text" id="txtPassportNumber" placeholder="add label" name="<?php echo '{{ getSubMenuLabel($index,'.$menu_item->id.') }}';?>" class="form-control form-control-sm"  ng-disabled="menumeta.value == 'menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][spanish]'" data-id="<?php echo '{{$index}}';?>"/>
                                  <?php if($menu_item->slug=='time_delayed_action'){ ?>
                                    <div class="form-check">
                                       <label class="form-check-label">
                                          <input  type="radio" value="split" class="form-check-input menu_meta_split split_radio_button rd" name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>" id="menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][split]" data="<?php echo '{{$index}}';?>" ng-click="clickEvent($event);"  >Split
                                       </label>
                                     </div>
                                  <?php }else{?>
                                 <?php foreach($menu_meta as $meta =>$meta_value){ ?>
                                   <div class="form-check">
                                    <label class="form-check-label">
                                     @if($meta_value->slug=='dial_number')
                                        <input type="radio"  value="dial_number"  class="form-check-input rd " name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>"  checked><?php echo $meta_value->name; ?>
                                     @elseif($meta_value->slug=='split')
                                          <input  type="radio" value="split" class="form-check-input menu_meta_split split_radio_button rd" name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>" id="block_<?php echo '{{$index}}';?>_{{$menu_item->id}}" data="<?php echo '{{$index}}';?>" ng-click="clickEvent($event);" ><?php echo $meta_value->name; ?>
                                     @else
                                     <input type="radio"  value="spanish" class="form-check-input rd " name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>"><?php echo $meta_value->name; ?>
                                          
                                     @endif
                                     
                                    </label>
                                   </div>
                                 <?php } } ?>
                                 
                                  <br>
                                </div>
                              <?php } ?>
                              </div>
                              
                              </div>

                              
                              <?php } $index=$index+1;
                              ?>    
                              </div>
                            <?php } ?>
                            </div>
                         </div>
                               </div>              
                           </div>
                           </div>
                        </div>
                     </div>
                     <div class="clearfix"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
   </div>
</div>
 </div>
</div>

@endsection