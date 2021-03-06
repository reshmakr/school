@extends('layouts.dashboard')
@section('content')

 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular.min.js"></script>
  <script type="text/javascript">
        var app = angular.module('MyApp', [])
        app.controller('MyController', function ($scope,$location,$anchorScroll) {
            //This will hide the DIV by default.
            // $scope.checkboxSelection = 1;
            // $scope.isCheckboxSelected = function(data) {
             // console.log(data);
             // console.log($scope.checkboxSelection);
              //   return data === $scope.checkboxSelection;
             //};

                // $scope.IsVisible = false;
                // $scope.ShowHide = function () { 
                //   console.log($scope.ShowPassport);
                //     //If DIV is visible it will be hidden and vice versa.
                //     $scope.IsVisible = $scope.ShowPassport;
                // }
                //  $scope.change = function () {
                //     console.log($scope.newItemType)
                //     $scope.IsVisible = $scope.ShowPassport;
                // };

           
                $scope.fieldGroups = [{ }];

                 $scope.getName = function ( $index) {
                  return $scope.name + '_' + $index + '_tier';
                }
                  $scope.getMenuName = function ( $index,$menu_id) {
                    //console.log($scope.name + '[' + $index + '][main_menu]['+$menu_id+']');
                  return $scope.name + '[' + $index + '][main_menu]['+$menu_id+']';
                }
                  $scope.getSubMenuName = function ( $index,$menu_id) {
                    //console.log( $scope.name + '[' + $index + '][sub_menu]['+$menu_id+']');
                  return $scope.name + '[' + $index + '][sub_menu]['+$menu_id+']';
                }
                  $scope.getSubMenuLabel = function ( $index,$menu_id) {
                   // console.log( $scope.name + '[' + $index + '][sub_menu_label]['+$menu_id+']');
                  return $scope.name + '[' + $index + '][sub_menu_label]['+$menu_id+']';
                }

                $scope.clone = function(index_id) {
                  $scope.fieldGroups.push({}); 
                  $location.hash('repeat_'+(index_id+1));
                  $anchorScroll();
                    alert($(this));
                    console.log($(this).attr());
                }
               $scope.clickEvent = function(obj) {
                
                  var el =$('.'+obj.target.attributes.class.value);
                  el.attr('disabled', 'disabled'); 
                 
                  //console.log(obj.target.attributes.id.value);
                  var idel =$('#'+obj.target.attributes.id.value);
                  idel.removeAttr('disabled'); 
                  $scope.fieldGroups.push({}); 
                  var indexid=parseFloat(obj.target.attributes.data.value)+1;
                  //console.log(indexid);

                  var tiername='block_'+indexid+'_tier';
                  $scope.tiername = obj.target.attributes.id.value;
                  /*console.log(obj.target.attributes.id.value);
                   $scope.tiername =obj.target.attributes.id.value; 
                   console.log(tiername);
                   console.log($scope.tiername);*/
                  //focus
                  $location.hash('repeat_'+(indexid));
                  $anchorScroll();      
              }
              $scope.addAttr = function(dial_number_id,spanish_id) {
                //console.log(dial_number_id);
                //console.log(spanish_id);
              }

                $scope.delete = function(fieldGroup) { 
                   var index = $scope.fieldGroups.indexOf(fieldGroup)+1;
                   // console.log(index);
                   // console.log($scope.fieldGroups.length);
                     if($scope.fieldGroups.length!=index)
                        $scope.fieldGroups.splice(index, 1);    

                }
                $scope.deleteTier = function(obj) {  
                 // console.log('mainbutton close');
                 // console.log(obj.target.attributes.data.value);
                 // console.log("#repeat_"+parseFloat(obj.target.attributes.data.value+1));
                 console.log(obj.target.checked);
                  if(obj.target.checked==false){
                    $( "#repeat_"+parseFloat(obj.target.attributes.data.value+1)).remove();
                  } 
                  var parentId=obj.target.parentNode.parentNode.parentNode.id;
                  //console.log($('#'+parentId+' .form-check-input:first'));
                  
                  /*$('#'+parentId+' .form-check-input').each(function(){
                    console.log($(this).attr('checked'));
                    $(this).attr('checked',false);
                });*/

                  //:first
                    /*var index = $scope.fieldGroups.indexOf(fieldGroup);
                     if($scope.fieldGroups.length!=1)
                        $scope.fieldGroups.splice(index, 1); */     
                }
                
                $scope.tierName = function(id) {
                     if(id==0){
                        return 'Home';
                       }
                        return 'Tier '+id;
                       
                   };
                     

        });
      

    </script>
<link rel="stylesheet" href="https://rawgit.com/jonthornton/jquery-timepicker/master/jquery.timepicker.css">
<div class="container">
   <div class="row profile">
      <div class="col-md-12">
         <div class="box">
         <div class="box-body">
		 <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data">
                                 {{csrf_field()}}
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
                                 <h3>Add Company</h3>
                              </div>
                              
                                 <div class="form-group">
                                    <label for="inputEmail4" class="required">Company Name</label>
                                    <input type="text" class="form-control" id="inputEmail4" placeholder="Company Name" name="name" value="{{ old('name') }}">
                                 </div>
                                 <div class="">
                                    <label for="inputEmail4" class="required">Company phone Numbers #</label>
                                 </div>
                                 <div class="form-row">
                                    @if(old('company_phone_numbers'))
                                    @foreach(old('company_phone_numbers') as $phone_number)
                                    <div class="form-group col-md-8">
                                       <div>
                                          <input type='tel' class='form-control company_phone_number' id='inputEmail4' placeholder='Company phone Numbers #' name='company_phone_numbers[]'value="{{ old('company_phone_numbers')[$loop->index] }}" maxlength="10" />
                                       </div>
                                    </div>
                                    <div class="form-group col-md-4 tn-buttons">
                                       <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i class="fa fa-plus"></i></button>
                                       @if($loop->index >0)
                                       <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i class="fa fa-remove"></i></button>
                                       @endif
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="form-group col-md-8">
                                       <div>
                                          <input type='tel' class='form-control company_phone_number' id='inputEmail4' placeholder='Company phone Numbers #' name='company_phone_numbers[]' value="" maxlength="10">
                                          @error('phone_numbers')
                                          <div style="color:red">{{ $message }}</div>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="form-group col-md-4 tn-buttons">
                                       <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i class="fa fa-plus"></i></button>
                                    </div>
                                    @endif
                                 </div>
                                 <div  id="packagingappendhere">
                                    <!-- clone content-->
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress">Address 1</label>
                                    <input type="text" class="form-control" id="inputAddress" placeholder="Address 1" name="company_address1"  value="{{ old('company_address1') }}">
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Address 2</label>
                                    <input type="text" class="form-control" name="company_address2" id="inputAddress2" placeholder="Address 2"  value="{{ old('company_address2') }}">
                                 </div>
                                 <div class="form-row">
                                    <div class="form-group col-md-3">
                                       <label for="inputCity">City</label>
                                       <input name="city" type="text" class="form-control" id="inputCity"  value="{{ old('city') }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                       <label for="inputState">State</label>
                                       <input name="state"  value="{{ old('state') }}" type="text" class="form-control" id="inputCity">
                                       <!-- <select id="inputState"name="state"  class="form-control">
                                          <option selected>Choose...</option>
                                          <option>...</option>
                                          </select> -->
                                    </div>
                                    <div class="form-group col-md-3">
                                       <label for="inputZip">Zip Code</label>
                                       <input name="zipcode" value="{{ old('zipcode') }}"type="text" class="form-control" id="inputZip">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputZip">Website</label>
                                    <input name="website" value="{{ old('website') }}" type="text" class="form-control" id="inputWebsite">
                                 </div>
                                 <div class="form-row">
                                    <div class="form-group col-md-6">
                                       <label>Logo</label>
                                       <div class="input-group">
                                          <span class="input-group-btn">
                                          <span class="btn btn-default btn-file">
                                          Browse… <input  value="{{ old('company_logo') }}" type="file" id="imgInp" name="company_logo">
                                          </span>
                                          </span>
                                          <input type="text" class="form-control" readonly>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <img id='img-upload'/>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="InputcompanyHours">Hours</label>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Monday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="monday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('monday.from_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="monday[to_time]" value="{{ old('monday.to_time') }}" type="text" class="form-control to_time" placeholder="17:00" autocomplete="off">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Tuesday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="tuesday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('tuesday.from_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="tuesday[to_time]" type="text" class="form-control to_time" placeholder="17:00" autocomplete="off" value="{{ old('tuesday.to_time') }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Wednesday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="wednesday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('wednesday.from_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="wednesday[to_time]" type="text" class="form-control to_time" placeholder="17:00" autocomplete="off" value="{{ old('wednesday.to_time') }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm  col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Thursday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="thursday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('thursday.from_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="thursday[to_time]" type="text" class="form-control to_time" placeholder="17:00" autocomplete="off" value="{{ old('thursday.to_time') }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Friday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="friday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('friday.from_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="friday[to_time]" type="text" class="form-control to_time" placeholder="17:00" autocomplete="off" value="{{ old('friday.to_time') }}">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Saturday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="saturday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('saturday.to_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="saturday[to_time]" type="text" class="form-control to_time" value="{{ old('saturday.to_time') }}" placeholder="17:00" autocomplete="off">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text" readonly value="Sunday" class="form-control" aria-label="Text input with checkbox">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                             </div>
                                             <input id="timeWithDuration" type="text" class="form-control ui-timepicker-input from_time" name="sunday[from_time]" placeholder="09:00" autocomplete="off" value="{{ old('sunday.to_time') }}">
                                          </div>
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                             </div>
                                             <input id="duration" name="sunday[to_time]" type="text" class="form-control to_time" value="{{ old('sunday.to_time') }}" placeholder="17:00" autocomplete="off">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Social Media Links</label>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Facebook" aria-label="Username" aria-describedby="basic-addon1" value="{{ old('facebook_url') }}" name="facebook_url">
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-instagram fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Instagram" aria-label="Username" aria-describedby="basic-addon1" value="{{ old('instagram_url') }}" name="instagram_url">
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-yelp fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Yelp" aria-label="Username" aria-describedby="basic-addon1" value="{{ old('yelp_url') }}" name="yelp_url">
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Twitter" aria-label="Username" aria-describedby="basic-addon1" value="{{ old('twitter_url') }}" name="twitter_url">
                                    </div>
                                    <div class="input-group mb-3">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><i class="fa fa-pinterest fa-lg" aria-hidden="true"></i></span>
                                       </div>
                                       <input type="text" class="form-control" placeholder="Pinterest" aria-label="Username" aria-describedby="basic-addon1" value="{{ old('pinterest_url') }}" name="pinterest_url">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Contact Name</label>
                                    <input name="contact_name" value="{{ old('contact_name') }}"  type="text" class="form-control" id="inputAddress2" placeholder="Contact Name">
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Contact Phone</label>
                                    <input name="contact_phone" value="{{ old('contact_phone') }}" type="text" class="form-control" id="inputAddress2" placeholder="Contact Phone">
                                 </div>
                                 <div class="form-group">
                                    <label for="inputAddress2">Contact Email</label>
                                    <input type="text" name="contact_email" value="{{ old('contact_email') }}" class="form-control" id="inputAddress2" placeholder="Contact Email">
                                 </div>
                                 <button type="submit" class="btn btn-primary">Save</button>
                              </form>
                           </div>
                           <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                             
                               <div ng-app="MyApp" >
                        <div ng-controller="MyController" ng-init="name = 'block'">
                                 <div class=" menu_button-group">
                           <div class="row text-center flex-nowrap">
                            <div ng-repeat="fieldGroup in fieldGroups" class="col-sm-6 ng-scope" id="<?php echo 'repeat_{{$index}}';?>" ng-set-focus="<?php echo 'repeat_{{$index}}';?>" style="border-right: 2px solid #eee;
    margin-right: 10px;">

                               <h3><?php echo '{{ tierName($index) }}';?></h3>
                              <?php 
                              foreach($menu_keys as $menu_items=>$menu_item){ ?>
                              
                              <div id="<?php echo 'parent_{{$index}}';?>">
                               <div class="form-group row">
                                <label class="switch  switch-flat control-label col-md-4" >
                                  <input class="switch-input" type="checkbox" data-size="small"  ng-true-value="menu_{{ $menu_item->id }}" ng-false-value="on" ng-model="check{{ $menu_item->id}}" name=" <?php echo '{{ getMenuName($index,'.$menu_item->id.') }}';?>" ng-click="deleteTier($event)" ng-true-value="YES" ng-false-value="NO" data="<?php echo '{{$index}}';?>" value="{{ $menu_item->slug }}"/>
                                  <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> 
                                </label>
                                <label class="col-md-3">&nbsp;&nbsp; <?php echo $menu_item->name;?></label>
                                </div>
                              <div class="clearfix"></div>
                                <div class="row"  ng-show="check{{ $menu_item->id}} == 'menu_{{ $menu_item->id}}'">
                                
                                <div class="col-md-7 offset-md-2">
                                 <input type="text" id="txtPassportNumber" placeholder="add label" name="<?php echo '{{ getSubMenuLabel($index,'.$menu_item->id.') }}';?>" class="form-control form-control-sm"   ng-model="form.OtherProgram" ng-disabled="disableOption === true" ng-required="form.Program<?php echo '{{$index}}';?>{{$menu_item->id}} != menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][dial_number] "/>
                                 <?php foreach($menu_meta as $meta =>$meta_value){ ?>
                                   <div class="form-check">
                                    
                                     @if($meta_value->slug=='dial_number')
                                     <label class="form-check-label">
                                        <input type="radio"  value="menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][{{$meta_value->slug}}]"  class="form-check-input" name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>"  checked ng-model="form.Program01" ng-click="disableOption = false;delete(fieldGroup)" required><?php echo $meta_value->name; ?>
                                         </label>
                                     @elseif($meta_value->slug=='split')
                                        <label class="form-check-label">
                                          <input disabled="disabled" type="radio" value="menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][{{$meta_value->slug}}]" class="form-check-input menu_meta_split split_radio_button" name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>" id="menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][split]" data="<?php echo '{{$index}}';?>" ng-click="clickEvent($event);" ><?php echo $meta_value->name; ?>
                                           </label>
                                     @else
                                     <label class="form-check-label">
                                     <input type="radio"  ng-model="form.Program0.1" ng-click="disableOption = true;delete(fieldGroup)"  required  value="menu_meta[<?php echo '{{$index}}';?>][{{$menu_item->id}}][{{$meta_value->slug}}]" class="form-check-input" name="<?php echo '{{ getSubMenuName($index,'.$menu_item->id.') }}';?>"  ><?php echo $meta_value->name; ?>
                                           </label>
                                     @endif
                                     
                                   
                                   </div>
                                 <?php } ?>
                                 
                                  <br>
                                </div>
                                <div class="clearfix"></div>
                                <br>
                              </div>
                              
                              </div>

                              
                              <?php }
                              ?>    
                       
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