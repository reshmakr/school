

<div  ng-show="model_{{$menu_parent}} == 'split'" class=" ng-scope repeat-class col-sm-4" id="repeat_{{$tier_key}}" ng-set-focus="repeat_{{$tier_key}}"  data-menuparent="{{$menu_parent}}" data-tierparent=#repeat_{{$tier_parent}}>
                               <?php $hometiername= $tier_key; ?>
                               <?php if($hometiername =='0'){ ?>
                                  <h3> <?php echo '{{ tierName('+$tier_key+') }}'; ?></h3>
                               <?php }else{ ?>
                                    <h3 id="h3_repeat_{{$tier_key}}">{{$tier_heading}}</h3>
                                <?php } ?>
                               <input type="hidden" name="block[{{$tier_key}}][tier_label]" id="hidden_repeat_{{$tier_key}}" value="{{$btn_id}}">
                               <h5 id="tierlabel_repeat_{{$tier_key}}">{{$tier_title}}</h5>
                               <br/>
                              <?php 
                              foreach($menu_keys as $menu_items=>$menu_item){ ?>
                              
                              <div id="parent_{{$tier_key}}">
                               <div class="form-group row">
                                <label class="switch control-label col-md-2" >
                                  <input class="switch-input checkbox-button" type="checkbox" data-size="small" data-class="menu_{{ $menu_item->id }}" ng-true-value="menu_{{$tier_key}}_{{ $menu_item->id }}" ng-false-value="on" ng-model="check_{{$tier_key}}_{{ $menu_item->id}}" name=" block[{{$tier_key}}][main_menu][{{$menu_item->id}}]" data="{{$tier_key}}"  ng-init="check_{{$tier_key}}_{{ $menu_item->id}} = menu_{{$tier_key}}_{{ $menu_item->id }}" id="{{$tier_key}}_{{$menu_item->slug}}" ng-checked="true"  btn-id="<?php echo '{{ getMenuId('.$tier_key.','.$menu_item->id.') }}';?>" spanish-btn-id="<?php echo '{{ getMenuSpanishClass('.$tier_key.','.$menu_item->id.') }}';?>" value="{{ $menu_item->slug }}" data-id="{{$tier_key}}"/>
                                  <span class="switch-label" data-on="On" data-off="Off"></span> <span class="switch-handle"></span> 
                                </label>
                                <label >&nbsp;&nbsp; <?php echo $menu_item->name;?></label>
                                </div>
                              <div class="clearfix"></div>
                                <div  ng-show="check_{{$tier_key}}_{{ $menu_item->id}} == 'menu_{{$tier_key}}_{{ $menu_item->id}}'" class="row ng-hide" data-button-hide="{{$tier_key}}_{{$menu_item->slug}}">
                                
                                <div class="col-md-9 offset-md-2">
                                
                                  <?php if($menu_item->slug=='time_delayed_action'){ ?>
                                     <input type="text" data-id="{{$tier_key}}"  placeholder="# of seconds to delay" name="<?php echo '{{ getSubMenuLabel('.$tier_key.','.$menu_item->id.') }}';?>" class="form-control form-control-sm menu_{{ $menu_item->id }}"  class="form-control form-control-sm menu_{{ $menu_item->id }}"  ng-disabled="menumeta.value == 'menu_meta[{{$tier_key}}][{{$menu_item->id}}][spanish]'"  btnid="<?php echo '{{ getMenuId('.$tier_key.','.$menu_item->id.') }}';?>"/>
                                    <div class="form-check">
                                       <label class="form-check-label form-check-label-split">
                                          <input  type="radio" value="split"  class="form-check-input menu_meta_split split_radio_button rd menu_{{ $menu_item->id }}"  name="block[{{$tier_key}}][sub_menu][{{$menu_item->id}}]" id="block[{{$tier_key}}][{{$menu_item->id}}]" data="{{$tier_key}}" databutton="{{$menu_item->name}}" ng-click="clickEvent($event);" data-href="{{ route('menu_template') }}" data-parent="{{$menu_item->name}}" data-parentmenuid="{{$tier_key}}_{{$menu_item->slug}}" <?php if($tier_parent==3){ echo 'disabled'; }?> >Split
                                       </label>
                                       <input type="hidden" class="tierchild" name=" block[{{$tier_key}}][tier_child][{{$menu_item->id}}]" value=""  data-child="" data-tierchild="">
                                     </div>
                                  <?php }else{?>
                                     <input type="text" data-id="{{$tier_key}}" placeholder="add label" name="block[{{$tier_key}}][sub_menu_label][{{$menu_item->id}}]" class="form-control form-control-sm menu_{{ $menu_item->id }}"  ng-disabled="menumeta.value == 'menu_meta[{{$tier_key}}][{{$menu_item->id}}][spanish]'"  btnid="<?php echo '{{ getMenuId('.$tier_key.','.$menu_item->id.') }}';?>"/>
                                 <?php foreach($menu_meta as $meta =>$meta_value){ ?>
                                   <div class="form-check">
                                   
                                     @if($meta_value->slug=='dial_number')
                                     <label class="form-check-label">
                                        <input type="radio"  value="dial_number"  class="form-check-input rd menu_{{ $menu_item->id }}" name="block[{{$tier_key}}][sub_menu][{{$menu_item->id}}]" checked="checked" ><?php echo $meta_value->name; ?>
                                      </label>
                                     @elseif($meta_value->slug=='split')
                                     <label class="form-check-label form-check-label-split">
                                          <input  type="radio" value="split" class="form-check-input menu_meta_split split_radio_button rd menu_{{ $menu_item->id }}"  name="block[{{$tier_key}}][sub_menu][{{$menu_item->id}}]" id="block[{{$tier_key}}][{{$menu_item->id}}]" data="{{$tier_key}}" databutton="{{$menu_item->name}}" ng-click="clickEvent($event);" data-href="{{ route('menu_template') }}" data-parent="{{$menu_item->name}}" data-parentmenuid="{{$tier_key}}_{{$menu_item->slug}}"  <?php if($tier_parent==3){ echo 'disabled'; }?>><?php echo $meta_value->name; ?></label>
                                          <input type="hidden" class="tierchild" name=" block[{{$tier_key}}][tier_child][{{$menu_item->id}}]" value=""  data-child="" data-tierchild="">
                                     @else
                                     <label class="form-check-label">
                                     <input type="radio" id="<?php echo '{{ getMenuSpanishClass('.$tier_key.','.$menu_item->id.') }}';?>" value="spanish" class="form-check-input rd menu_{{ $menu_item->id }}"  name="block[{{$tier_key}}][sub_menu][{{$menu_item->id}}]"><?php echo $meta_value->name; ?></label>
                                          
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