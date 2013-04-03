<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editGroup" title="View Group Details">
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
                            <label>Member Of</label>
                            <div class="formRight" style="text-align:left;padding-top:10px;margin-left:68px;float:left;width:auto;">
                            	<?php
									echo form_dropdown('agency', $agencies, ((isset($group->AgencyID)) ? $group->AgencyID : ''),'id="memberOf" class="chzn-select" disabled="disabled" style="margin-left:10px;"');
								?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label style="margin-right:20px;">Member Since</label>
                            <div class="formRight"><?= form_input(array('class'=>'datepicker validate[required]','name'=>'created','id'=>'createdDate','value'=>((isset($group->JoinDate)) ? date('m-d-Y', strtotime($group->JoinDate)) : ''))); ?></div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Notes</label>
                            <div class="formRight">
                               <?= form_textarea(array('name'=>'notes','id'=>'groupNotes','value'=>(($group->Description) ? $group->Description : ''))); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
						<?php if(isset($group->Status)) { ?>
                            <div class="rowElem noborder">
                                <label>Enable/Disable</label>
                                <div class="formRight" style="text-align:left;padding-top:15px;">
                                    <input type="radio" id="radio1" name="status" value="1" <?= (($group->Status >= 1) ? 'checked="checked"' : ''); ?> />
                                    <label style="float:none;display:inline;" for="radio1">Enabled</label>
                                    <input type="radio" id="radio2" name="status" value="0" <?= (($group->Status < 1) ? 'checked="checked"' : ''); ?>  />
                                    <label style="float:none;display:inline;" for="radio2">Disabled</label>
                                </div>
                                <div class="fix"></div>
                            </div>
                        <?php } ?>
                        <div class="submitForm">
                            <?php if(isset($group->GroupId)) { ?>
                                <input type="hidden" name="agency_id" value="<?= $group->GroupId; ?>" />
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
<script type="text/javascript">
	jQuery(".chzn-select").chosen();
	jQuery(".datepicker").datepicker({ 
		defaultDate: +7,
		autoSize: true,
		appendText: '(dd-mm-yyyy)',
		dateFormat: 'mm-dd-yy',
	});	

	jQuery('ul.tabs li a').live('click',function() {
		//remove all activetabs
		jQuery('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		jQuery(this).parent().addClass('activeTab');
		var content = 'div#' + jQuery(this).attr('rel');
		//alert(content);
		jQuery('#viewGroup div.tab_container div.tab_content').hide();
		jQuery('#viewGroup div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery("#editGroup").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: false
	});
</script>
