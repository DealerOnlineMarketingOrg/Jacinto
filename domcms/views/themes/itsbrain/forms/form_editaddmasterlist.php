<div class="uDialog">
    <div class="dialog-message popper" id="<?= (($page == 'edit') ? 'editMasterlist' : 'addMasterlist'); ?>" title="<?= (($page == 'edit') ? 'Edit' : 'Add'); ?> Masterlist">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
            	<?php
					if($page == 'edit') :
						echo form_open('/admin/masterlist/edit',array('id'=>'editMasterlistForm','class' => 'validate mainForm formPop','style' => 'text-align:left'));
					else :
						echo form_open('/admin/masterlist/add',array('id'=>'addMasterlistForm','class'=>'validate mainForm formPop','style' => 'text-align:left'));				
					endif;
				?>
                    <fieldset>
                    	<div class="rowElem noborder">
                            <label><span class="req">*</span> Parent Client</label>
                            <div class="formRight searchDrop">
                                <select class="chzn-select validate[required]" style="width:350px" name="parent">
                                    <option value=""></option>
                                    <?php 
                                        foreach($clients as $client) :
                                            echo '<option ' . (($client->ClientID == 
											$masterlist->DealershipID) ? ' selected' : '') . ' value="' . 
											$client->ClientID . '">' . 
											$client->Name . '</option>';
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Masterlist Type</label>
                            <div class="formRight searchDrop">
                                <select class="chzn-select validate[required]" style="width:350px" name="type">
                                    <option value="CID" <?= (($masterlist->Type == 'CID') ? 'selected="selected"' : ''); ?>>Client</option>
                                    <option value="VID" <?= (($masterlist->Type == 'VID') ? 'selected="selected"' : ''); ?>>Vendor</option>
                                    <option value="GID" <?= (($masterlist->Type == 'GID') ? 'selected="selected"' : ''); ?>>General</option>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                       	<div class="rowElem noborder">
                            <label><span class="req">*</span> Job Title Type</label>
                            <div class="formRight">
                                <select class="chzn-select validate[required]" style="width:350px" name="jobTitleType">
									<?php
                                        foreach ($types as $type) {
                                            echo '<option value="'.$type->Id.'"'.(($type->Id == $masterlist->Title) ? ' selected' : '').'>'.$type->Name.'</option>';
                                        }
									?>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Job Title</label>
                            <div class="formRight">
                                <?= form_input(array('class'=>'validate[required]','name'=>'JobTitle','id'=>'JobTitle','value'=>$masterlist->JobTitle)); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Name</label>
                            <div class="formRight"><label style="margin:0;color:red">First:</label><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=>$masterlist->FirstName,'style'=>'margin:0')); ?></div><div class="fix"></div>
                            <div class="formRight"><label style="margin:0;color:red">Last:</label><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>$masterlist->LastName,'style'=>'margin:0')); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Personal Email</label>
                            <?php
								// Locate primary.
								foreach ($contact->Email as $type => $email) {
									if ($type == $contact->PrimaryEmailType) { ?>
										<div class="formRight"><label style="margin:0;color:red">Personal Email:</label><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>$type.'EmailAddress','id'=>'email','value'=>$email,'style'=>'margin:0')); ?></div><div class="fix"></div>
									<?php }
								}
							?>
                            <?php
								// Locate others.
								foreach ($contact->Email as $type => $email) {
									if ($type != $contact->PrimaryEmailType) { ?>
										<div class="formRight"><label style="margin:0;color:red"><?= $type.' Email'; ?>:</label><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>$type.'EmailAddress','id'=>'email','value'=>$email,'style'=>'margin:0')); ?></div><div class="fix"></div>
									<?php }
								}
							?>
                        </div>
                        <div class="rowElem noborder">
                        	<label>Address</label>
                            <div class="formRight"><label style="margin:0;color:red">Street:</label><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => ((isset($masterlist->Address['street'])) ? $masterlist->Address['street'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                        	<div class="formRight"><label style="margin:0;color:red">City:</label><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>((isset($masterlist->Address['city'])) ? $masterlist->Address['city'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                            <div class="formRight searchDrop"><label style="margin:0;color:red">State:</label><div class="fix"></div>
                                <?= showStates(((isset($masterlist->Address['state'])) ? $masterlist->Address['state'] : false)); ?>
                                <?= ((isset($masterlist->Address['state'])) ? '' : '<span class="formNote">No state found for user</span>'); ?>
                            </div><div class="fix"></div>
                            <div class="formRight"><label style="margin:0;color:red">Zip:</label><?= form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'zip','id'=>'zip','value' => ((isset($masterlist->Address['zipcode'])) ? $masterlist->Address['zipcode'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Phone</label>
                            <?php
								// Locate primary.
								foreach ($contact->Phone as $type => $phone) {
									if ($type == $contact->PrimaryPhoneType) { ?>
										<div class="formRight"><label style="margin:0;color:red">Main/Direct:</label><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>$type.'phone','id'=>'phone','value'=>$phone,'style'=>'margin:0')); ?></div><div class="fix"></div>
									<?php }
								}
							?>
                            <?php
								// Locate others.
								foreach ($contact->Phone as $type => $phone) {
									if ($type != $contact->PrimaryPhoneType) { ?>
										<div class="formRight"><label style="margin:0;color:red"><?= $type.' Phone'; ?>:</label><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>$type.'phone','id'=>'phone','value'=>$phone,'style'=>'margin:0')); ?></div><div class="fix"></div>
									<?php }
								}
							?>
                        </div>
                        <div class="rowElem noborder"><label>Notes</label><div class="formRight"><?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes', 'value' => $masterlist->Notes)); ?></div><div class="fix"></div></div>
                            
                        <div class="submitForm">               
		                	<input type="hidden" name="masterlist_id" value="<?= $masterlist->MasterlistID; ?>" />
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
			url:'/admin/masterlists/form<?= (($page == 'edit') ? '?cid=' . $masterlist->MasterlistID : ''); ?>',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= ($page == 'edit') ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						masterlistListTable();
					}); 
				}else {
					msg = '<?= ($page == 'edit') ? 'There was a problem with editing the masterlist requested. Please try again.':'There was a problem adding the masterlist. Please try again.'; ?>';
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
		$('#viewMasterlist div.tab_container div.tab_content').hide();
		$('#viewMasterlist div.tab_container').find(content).css({'display':'block'});
	});
	
	<?php if($page == 'edit') { ?>
	$("#editMasterlist").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php }else { ?>
	$("#addMasterlist").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php } ?>
</script>
