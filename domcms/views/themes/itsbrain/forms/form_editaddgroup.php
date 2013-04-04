<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="<?= ((isset($group)) ? 'editGroup' : 'addGroup'); ?>" title="View Group Details">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
            	<?php
					if(isset($group)) :
						echo form_open('/admin/groups/edit',array('id'=>'editGroupForm','class' => 'validate mainForm formPop'));
					else :
						echo form_open('/admin/groups/add',array('id'=>'addGroupForm','class'=>'validate mainForm formPop'));				
					endif;
				?>
                    <fieldset>
                        <div class="rowElem noborder">
                            <label>Group Name</label>
                            <div class="formRight">
								<?php
									if(isset($group->Name)) {
										echo form_input(array('id'=>'group_name','name'=>'name','class'=>'validate[required]','value'=>$group->Name));
									}else {
										echo form_input(array('id'=>'group_name','name'=>'name','class'=>'validate[required]'));
									}	
								?>
							</div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label style="padding-top:10px;">Member Of</label>
                            <div class="formRight" style="text-align:left;padding-top:10px;margin-left:68px;float:left;width:auto;">
                            	<?php
									echo form_dropdown('agency', $agencies, ((isset($group->AgencyID)) ? $group->AgencyID : ''),'id="memberOf" class="chzn-select" disabled="disabled" style="margin-left:10px;"');
								?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Notes</label>
                            <div class="formRight">
                               <?= form_textarea(array('name'=>'notes','id'=>'groupNotes','value'=>((isset($group->Description)) ? $group->Description : ''))); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
						<?php if(isset($group->Status)) { ?>
                            <div class="rowElem noborder">
                                
                                <div class="formRight" style="text-align:left;padding-top:15px;">
                                    <input type="radio" id="radio1" name="status" value="1" <?= (($group->Status >= 1) ? 'checked="checked"' : ''); ?> />
                                    <label style="float:none;display:inline;" for="radio1">Enable</label>
                                    <input type="radio" id="radio2" name="status" value="0" <?= (($group->Status < 1) ? 'checked="checked"' : ''); ?>  />
                                    <label style="float:none;display:inline;" for="radio2">Disable</label>
                                </div>
                                <div class="fix"></div>
                            </div>
                        <?php } ?>
                        <div class="submitForm">
                            <?php if(isset($group->GroupId)) { ?>
                                <input type="hidden" name="agency_id" value="<?= $group->AgencyId; ?>" />
                            <?php } ?>
                            <input type="submit" value="submit" class="redBtn" />
                        </div>
                    </fieldset>
               	<?= form_close(); ?>
                <div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<style type="text/css">
.rowElem > label {padding-top:5px;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	$('.formPop').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/groups/form<?= ((isset($group)) ? '?gid=' . $group->GroupId : ''); ?>',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= (isset($group)) ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						groupListTable();
					}); 
				}else {
					msg = '<?= (isset($group)) ? 'There was a problem with editing the group requested. Please try again.':'There was a problem adding the group. Please try again.'; ?>';
					jAlert(msg,'Error');
				}
			}
		});
	});
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		$('#viewGroup div.tab_container div.tab_content').hide();
		$('#viewGroup div.tab_container').find(content).css({'display':'block'});
	});
	
	<?php if(isset($group)) { ?>
	$("#editGroup").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php }else { ?>
	$("#addGroup").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php } ?>
</script>
