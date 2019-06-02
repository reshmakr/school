@extends('layouts.dashboard')
@section('content')
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular.min.js"></script>
<!-- <script src="{{ asset('dist/js/create.js') }}"></script> -->
<!-- <script src="{{ asset('dist/js/create.min.js') }}"></script>   -->  

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
                
				  $scope.getSubSplitName = function ( $index,$menu_id) {
                  return $scope.name + '[' + $index + '][tier_child]['+$menu_id+']';
                }
                $scope.getTierLabel = function ($index) {
                   
                  return $scope.name + '[' + $index + '][tier_label]';
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
  var childArray = {};
  //split disabled 
  $('.split_radio_button:checked').each(function () {
    var tierid=$(this).parent().parent().parent().children("input:first").data("id");
    $('#repeat_'+tierid+' .split_radio_button').attr('disabled','disabled');
  });
        $(document).on('submit','#company-form',function(e){
          var validation_error=false;
         $('input.checkbox-button:checkbox:checked').each(function () {
              var sThisVal = $(this).val();
              var input_btn_id=$(this).attr('btn-id');
              var spanish_btn_id=$(this).attr('spanish-btn-id');
			var inputboxval=$(this).parent().parent().siblings('.row').children().find('.form-control-sm').val();
			//console.log(inputboxval.length);
			   //console.log($("#"+spanish_btn_id).is(':checked'));
              if (inputboxval.length==0 && $("#"+spanish_btn_id).is(':checked')==false) {
               validation_error=true;
              }
              
          });
         if(validation_error){
          $('#error-msg').html('<div class="alert alert-danger"><ul><li>DialTree options are missing</li></ul></div>');
          $( "#error-msg" ).focus();
          return false;
         }
          e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
               $.ajax({
                  url: "{{ url('company/store') }}",
                  method: 'post',
                  data: $('#company-form').serialize(),
                  success: function(data){ 
                    
                    if(data.success){
                      window.location.replace("{{ url('/home') }}");
                    } 
					$('.alert-danger').text('');
					if(data.errors){
						 $('.alert-danger').show();
                        $('.alert-danger').append('<p>DialTree options are missing</p>');
					}
					
                      $.each(data.errors, function(key, value){
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>'+value+'</p>');
                      });
                  }
                    
                  });
         
        });

       //checkbox change
        $( document ).on( "change", ".checkbox-button", function () {
          var ischecked= $(this).is(':checked');
          if(ischecked){
             if($(this).parent().parent().siblings('.row').hasClass('ng-hide')){
               $(this).parent().parent().siblings('.row').toggleClass('ng-hide')
            }
          }
           
          if(!ischecked){
             if(!$(this).parent().parent().siblings('.row').hasClass('ng-hide')){
               $(this).parent().parent().siblings('.row').toggleClass('ng-hide')
            }
            $(this).parent().parent().siblings('.row').removeAttr('ng-hide');
            $(this).parent().parent().siblings('.row').toggleClass('ng-hide');
            var splitcheckbutton=$(this).parent().parent().siblings('.row').children().children().siblings('.form-check').children().children().eq(1);

            if($(this).val()=='time_delayed_action'){
              var tierid=$(this).data("id");
              var tm_split=$(this).parent().parent().siblings('.row').children().children().siblings('.form-check').children().children();
              if($(tm_split).is(':checked')){
                 var next_tier_id=parseFloat(tierid)+1;
                    $('#repeat_'+next_tier_id).remove();
                    $('#repeat_'+tierid).find('.split_radio_button').removeAttr('disabled');
                 $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                  //$('#repeat_'+tierid).find('.split_radio_button').val('');

              }
             
            }
            if($(splitcheckbutton).is(':checked')){ 
               var tierid=$(this).data("id"); 
                  var splitchild=$(this).parent().parent().siblings('.row').children().children("input:first").data("child");
				  console.log($("[data-tierparent="+splitchild+"]").attr('id'));
               if( $("[data-tierparent="+splitchild+"]").attr('id')){
				   var datathirdchild=$("[data-tierparent=#"+splitchild+"]").attr('id');
				   var datafourthchild=$("[data-tierparent=#"+datathirdchild+"]").attr('id');
				   console.log(datathirdchild);
				   //console.log(datafourthchild);
				   //if(datafourthchild){
					//   $(datafourthchild).remove();
				   //}
				      $("[data-tierparent=#"+datathirdchild+"]").remove();
			   }
                  $("[data-tierparent=#"+splitchild+"]").remove();
				console.log(splitchild);
              $(splitchild).remove();
             $(this).parent().parent().siblings('.row').toggleClass('ng-hide');
            }else{
               $(this).parent().parent().siblings('.row').toggleClass('ng-hide');
            }
			
			$(this).siblings("input:radio[disabled=false]:first").attr('checked', true);
			$(this).parent().parent().siblings('.row').find(".menu_2").attr('checked', true);
           // $(this).siblings("input:radio[disabled=false]:first").attr('checked', true);
		   
            var closeClassName=$(this).data('class'); 
             $('#repeat_'+$(this).attr('data-id')+' .'+closeClassName).each(function() { 
			 if($(this).val()=='dial_number'){
				 $(this).parent().parent().parent().children("input:first").val('');
				  $(this).prop('checked',true);
			 }else{
				 $(this).parent().parent().parent().children("input:first").val('');
				  $(this).prop('checked',false);
			 }
              
               $(this).removeAttr('disabled');
             });
             
            if($(this).parent().parent().siblings('.row').children().children("input:first").hasClass('radiobuttons')){ 
              
              var tierid=$(this).data("id");
              var splitchild=$(this).parent().parent().siblings('.row').children().children("input:first").data("child");
              $(splitchild).remove();
              $(this).parent().parent().siblings('.row').children().children("input:first").toggleClass("radiobuttons");
              
           }
          }else{
           
          }
          
      }); 

		$( document ).on( "change paste keyup", ".radiobuttons",function(e){ 
			var tier_child_id=$(this).attr('data-child');
			$(tier_child_id).find('h5').text($(this).val());
		});
          //radio change
           $( document ).on( "change", ".rd",function(e){ 
            
           // console.log($(this).val());
              if($(this).val() == 'spanish') {
                  var thatInput=$(this).parent().parent().parent().children("input:first").val('N/A');
                  $(this).parent().parent().parent().children("input:first").attr('disabled', 'disabled'); 
                  if($(this).parent().parent().parent().children("input:first").hasClass('radiobuttons')){
                    var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                    var splitchild=$(this).parent().parent().parent().children("input:first").data("child");
                     $(splitchild).remove();
                      $(this).parent().parent().parent().find('.tierchild').val();
                    $(this).parent().parent().parent().children("input:first").toggleClass("radiobuttons");
                   // $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                  }
                  
              } else if($(this).val() == 'split'){  
                var numItems = $('.repeat-class').length; 
                $(this).attr('checked','checked');
                var thatInput=$(this).parent().parent().parent().children("input:first").val();
                $(this).parent().parent().parent().children("input:first").addClass("radiobuttons");
                  $(this).parent().parent().parent().children("input:first").removeAttr('disabled');
                  var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                  var next_tier_id=parseFloat(numItems);
                  $('#hidden_repeat_'+next_tier_id).val($(this).attr('id'));
                 // console.log($('#h3_repeat_'+next_tier_id).text($(this).attr('databutton')));
                    
                   $('#tierlabel_repeat_'+next_tier_id).text(thatInput);
                 
                  //clone div
                  $(this).attr('data-child','#repeat_'+next_tier_id);
                  //numItems
                 // $(this).closest("div.content").find("input[name=’rank’]").val();
				
                 $(this).parent().siblings().val(numItems);
                   $(this).parent().parent().parent().children("input:first").attr('data-child','#repeat_'+next_tier_id)
                   $(this).attr('data-tierchild','#repeat_'+next_tier_id)
				   $(this).parent().siblings('.tierchild').attr('data-tierchild','#repeat_'+next_tier_id);
				   $(this).parent().siblings('.tierchild').attr('data-child','#repeat_'+next_tier_id);
                   var get_url=$(this).attr('data-href');
                   var tier_heading=$(this).attr('data-parent');
                   var menu_parent=$(this).attr('data-parentmenuid');
                    /*$.get(get_url, { id: numItems,tier_parent:tierid, btn_id: $(this).attr('id') ,tier_title:thatInput,tier_heading:tier_heading,menu_parent:menu_parent},function (data) {
                       $(".flex-nowrap").append(data);
                    });*/
					var radioname=$(this).attr('name');
					// console.log($(this).attr('name'));
                    $.ajax({
							type: 'GET',
							url: get_url,
							data: { id: numItems,tier_parent:tierid, btn_id: $(this).attr('id') ,tier_title:thatInput,tier_heading:tier_heading,menu_parent:menu_parent},
							beforeSend: function() {
							      $('input[name="'+radioname+'"]').map(function() { 
								  //console.log($(this).val());
										 if($(this).val()=='split'){
											 
										 }else{
											 $(this).attr('disabled',true);
										 }  
									});
							},
							success: function(data) {
								$(".flex-nowrap").append(data);
								 $('input[name="'+radioname+'"]').map(function() { 
										 if($(this).val()=='split'){
											 
										 }else{
											 $(this).removeAttr('disabled');
										 }  
									});
							},
							error: function(xhr) { // if error occured
							   
							},
							complete: function() {
							  // $(".flex-nowrap").append(data);
							  //$('input[name='+$(this).attr('name')+']').removeAttr('disabled');
							}
						});
                    $('.repeat-class').hide();
                    
                    
					//console.log(tierid);
					if($('#repeat_'+tierid).attr('data-tierparent')){
						  $($('#repeat_'+tierid).attr('data-tierparent')).show();
						}
					
					if(tierid==2){
						//console.log('2- '+tierid);
						var tiersecond_parent=$('#repeat_'+tierid).attr('data-tierparent');
						//console.log('hbg '+tiersecond_parent);
						  $($(tiersecond_parent).attr('data-tierparent')).show();
					}
					if(tierid==3){
						//console.log('3- '+tierid);
						var tiersecond_parent=$('#repeat_'+tierid).attr('data-tierparent');
						$(tiersecond_parent).show();
						$($(tiersecond_parent).attr('data-tierparent')).show();
						//console.log($(tiersecond_parent).attr('data-tierparent'));
						var tierfirst_parent=$($(tiersecond_parent).attr('data-tierparent')).attr('data-tierparent');
						//console.log(tierfirst_parent);
						$(tierfirst_parent).show();
						
						
					}
					//console.log($('#repeat_'+tierid).attr('data-tierparent'));
                   $('#repeat_'+tierid).show();
                    $('#repeat_'+next_tier_id).show();
                    $('#repeat_0').show();
                    
                    
              }else {
                  var thatInput=$(this).parent().parent().parent().children("input:first").val('');
                  $(this).parent().parent().parent().children("input:first").removeAttr('disabled');
                  if($(this).parent().parent().parent().children("input:first").hasClass('radiobuttons')){
                     var tierid=$(this).parent().parent().parent().children("input:first").data("id");
                  
                   var next_tier_id=parseFloat(tierid)+1;
                    var numItems = $('.repeat-class').length;
                    var splitchild=$(this).parent().parent().parent().children("input:first").data("child");
                     $(splitchild).remove();
                      $(this).parent().parent().parent().find('.tierchild').val();
                    /*if(numItems >tierid){
                      for(var i=next_tier_id;i<=numItems;i++){
                        $('#repeat_'+i).remove();
                      }
                    }*/
                  //  $('#repeat_'+next_tier_id).remove();
                     $('#repeat_'+tierid+' .split_radio_button').removeAttr('disabled');
                    $(this).parent().parent().parent().children("input:first").toggleClass("radiobuttons");
                    
                  }
              }
          });
         
      });
 //split label click show child tier
        $( document ).on( "click", ".form-check-label-split", function () {
             if($(this).children().attr('data-tierchild')){
             $('.repeat-class').hide();
             $('#repeat_0').show();
			 
             //console.log($(this).children().attr('data-tierchild'));
            var parent=$($(this).children().attr('data-tierchild')).attr('data-tierparent');
			var child=$(this).attr('data-child');
            if(parent){
              $(parent).show();
            }
			//console.log($(this).children().attr('data-tier_parent'));
			$($(this).children().attr('data-tier_parent')).show();
            $($(this).children().attr('data-tierchild')).show();

          }
        });
</script>

<link rel="stylesheet" href="https://rawgit.com/jonthornton/jquery-timepicker/master/jquery.timepicker.css">
<div class="container">
   <div class="row profile">
      <div class="col-md-12">
         <div class="box">
         <div class="box-body">
		 <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data" id="company-form">
                                 {{csrf_field()}}
         @if(Session::has('success_msg'))
         <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
         @endif
         @if(Session::has('error_msg'))
         <div class="alert alert-danger">{{ Session::get('error_msg') }}</div>
         @endif
         <div class=" alert alert-danger" style="display:none">
           
          </div>
         @if ($errors->any())
         <div class="alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif
         <div id="error-msg"></div>
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
                     <div class="col-md-2 col-xs-12">
                        <div class="space_50"></div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                           <a class="nav-link active show" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="true">Company Info</a>
                           <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Dial Tree</a>
                        </div>
                     </div>
                     <div class="col-md-9 col-xs-12">
                        <div class="tab-content" id="v-pills-tabContent">
                           <div class="tab-pane fade active show" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
						   <div class="col-8 col-xs-12">
                              <div class="text-center">
                                 <h3>Add Company</h3>
                              </div>
                              
                                 <div class="form-group">
                                    <label for="inputEmail4" class="required"><span style="color: red">* </span>Company Name</label>
                                    <input type="text" class="form-control" id="inputEmail4" placeholder="Company Name" name="name" value="{{ old('name') }}">
                                 </div>
                                 <div class="">
                                    <label for="inputEmail4" class="required"><span style="color: red">* </span>Company phone Numbers #</label>
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
                                       <img id='img-upload' width="150px"/>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="InputcompanyHours">Hours</label>
                                    <div class="company_hours">
                                       <div class="form-row">
                                          <div class="form-group input-group input-group-sm col-md-3">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                   <input type="checkbox" name="monday_checked" aria-label="Checkbox for following text input">
                                                </div>
                                             </div>
                                             <input type="text"  readonly value="Monday" class="form-control" aria-label="Text input with checkbox">
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
                                                   <input type="checkbox" name="tuesday_checked" aria-label="Checkbox for following text input">
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
                                                   <input type="checkbox" name="wednesday_checked" aria-label="Checkbox for following text input">
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
                                                   <input type="checkbox" name="thursday_checked" aria-label="Checkbox for following text input">
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
                                                   <input type="checkbox" name="friday_checked" aria-label="Checkbox for following text input">
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
                                                   <input type="checkbox" name="saturday_checked" aria-label="Checkbox for following text input">
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
                                                   <input type="checkbox" name="sunday_checked" aria-label="Checkbox for following text input">
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
                                <div class="row" style="margin-top: 50px;
    margin-bottom: 50px;">

                                  <div class="col-md-6">
                                    <a href="{{ url('/home')}}" class="btn btn-default btn-block">Cancel</a>
                                  </div>
                                  <div class="col-md-6">
                                    <button type="submit" class="btn btn-info btn-block">Save</button>
                                  </div>
                                    
                                        
                                  </div>
                             </div>
                           </div>
                           <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                             
                               <div ng-app="MyApp" >
                        <div ng-controller="MyController" ng-init="name = 'block'" id="MyController">
                                 <div class=" menu_button-group">
                           <div class="row text-center flex-nowrap">
                            <div class="col-sm-4 ng-scope repeat-class" id="repeat_0" ng-set-focus="repeat_0" style="border-right: 2px solid #eee;
    margin-right: 10px;">
                              
                                    <h3 id="h3_repeat_0">Home</h3>
                              
                                
                               <input type="hidden" name="<?php echo '{{ getTierLabel(0) }}';?>" id="hidden_repeat_0" value="">
                               <h5 id="tierlabel_repeat_0"></h5>
                               <br/>
                              <?php 
                              foreach($menu_keys as $menu_items=>$menu_item){ ?>
                              
                              <div id="parent_0">
                               <div class="form-group row">
                                <label class="switch control-label col-md-2" >
                                  <input class="switch-input checkbox-button" type="checkbox" data-size="small" data-class="menu_{{ $menu_item->id }}" ng-true-value="menu_{{ $menu_item->id }}" ng-false-value="on" ng-model="check{{ $menu_item->id}}" name=" <?php echo '{{ getMenuName(0,'.$menu_item->id.') }}';?>"  ng-true-value="YES" ng-false-value="NO" data="0" value="{{ $menu_item->slug }}" data-id="0" btn-id="<?php echo '{{ getMenuId(0,'.$menu_item->id.') }}';?>" spanish-btn-id="<?php echo '{{ getMenuSpanishClass(0,'.$menu_item->id.') }}';?>"/>
                                  <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> 
                                </label>
                                <label>&nbsp;&nbsp; <?php echo $menu_item->name;?></label>
                                </div>
                              <div class="clearfix"></div>
                                <div class="row"  ng-show="check{{ $menu_item->id}} == 'menu_{{ $menu_item->id}}'">
                                
                                <div class="col-md-9 offset-md-2">
                                
                                  <?php if($menu_item->slug=='time_delayed_action'){ ?>
                                     <input type="text" data-id="0"  placeholder="# of seconds to delay" name="<?php echo '{{ getSubMenuLabel(0,'.$menu_item->id.') }}';?>" class="form-control form-control-sm menu_{{ $menu_item->id }}"  ng-disabled="menumeta.value == 'menu_meta[0][{{$menu_item->id}}][spanish]'" btnid="<?php echo '{{ getMenuId(0,'.$menu_item->id.') }}';?>"/>
                                    <div class="form-check">
                                       <label class="form-check-label form-check-label-split">
                                          <input  type="radio" value="split" class="form-check-input menu_meta_split split_radio_button rd menu_{{ $menu_item->id }}" name="<?php echo '{{ getSubMenuName(0,'.$menu_item->id.') }}';?>" id="block[0][{{$menu_item->id}}]" data="0" databutton="{{$menu_item->name}}" ng-click="clickEvent($event);" data-href="{{ route('menu_template') }}" data-parent="{{$menu_item->name}}" data-parentmenuid="<?php echo '0_'.$menu_item->slug;?>">Split
                                       </label>
                                      <input type="hidden" class="tierchild" name="<?php echo '{{ getSubSplitName(0,'.$menu_item->id.') }}';?>" value=""  data-child="" data-tierchild="" >
                                     </div>
                                  <?php }else{?>
                                     <input type="text" data-id="0" placeholder="add label" name="<?php echo '{{ getSubMenuLabel(0,'.$menu_item->id.') }}';?>" class="form-control form-control-sm menu_{{ $menu_item->id }}"  ng-disabled="menumeta.value == 'menu_meta[0][{{$menu_item->id}}][spanish]'"  btnid="<?php echo '{{ getMenuId(0,'.$menu_item->id.') }}';?>"/>
                                 <?php foreach($menu_meta as $meta =>$meta_value){ ?>
                                   <div class="form-check">
                                   
                                     @if($meta_value->slug=='dial_number')
										  <label class="form-check-label">
                                        <input type="radio"  value="dial_number"  class="form-check-input rd menu_{{ $menu_item->id }}" name="<?php echo '{{ getSubMenuName(0,'.$menu_item->id.') }}';?>"  checked><?php echo $meta_value->name; ?></label>
                                     @elseif($meta_value->slug=='split')
									  <label class="form-check-label form-check-label-split">
                                          <input type="radio" value="split" class="form-check-input menu_meta_split split_radio_button rd menu_{{ $menu_item->id }}" name="<?php echo '{{ getSubMenuName(0,'.$menu_item->id.') }}';?>" id="block[0][{{$menu_item->id}}]" data="0" databutton="{{$menu_item->name}}" ng-click="clickEvent($event);" data-href="{{ route('menu_template') }}" data-parent="{{$menu_item->name}}" data-parentmenuid="<?php echo '0_'.$menu_item->slug;?>"><?php echo $meta_value->name; ?>
										  </label>
										  <input type="hidden" class="tierchild" name="<?php echo '{{ getSubSplitName(0,'.$menu_item->id.') }}';?>" value="" data-child="" data-tierchild="" >
                                     @else
										  <label class="form-check-label">
                                     <input type="radio"   id="<?php echo '{{ getMenuSpanishClass(0,'.$menu_item->id.') }}';?>" value="spanish" class="form-check-input rd menu_{{ $menu_item->id }}" name="<?php echo '{{ getSubMenuName(0,'.$menu_item->id.') }}';?>"><?php echo $meta_value->name; ?>
                                          </label>
                                     @endif
                                     
                                   </div>
                                 <?php } } ?>
                                 
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