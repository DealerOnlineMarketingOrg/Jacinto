<div class="uDialog">
    <div class="dialog-message popper" id="<?= (($page == 'edit') ? 'editContact' : 'addContact'); ?>" title="<?= (($page == 'edit') ? 'Edit' : 'Add'); ?> Contact">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
            	<?php
					if($page == 'edit') :
						echo form_open('/admin/contacts/edit',array('id'=>'editContactForm','class' => 'validate mainForm formPop','style' => 'text-align:left'));
					else :
						echo form_open('/admin/contacts/add',array('id'=>'addContactForm','class'=>'validate mainForm formPop','style' => 'text-align:left'));				
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
											$contact->DealershipID) ? ' selected' : '') . ' value="' . 
											$client->ClientID . '">' . 
											$client->Name . '</option>';
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Contact Type</label>
                            <div class="formRight searchDrop">
                                <select class="chzn-select validate[required]" style="width:350px" name="type">
                                    <option value="CID" <?= (($contact->Type == 'CID') ? 'selected="selected"' : ''); ?>>Client</option>
                                    <option value="VID" <?= (($contact->Type == 'VID') ? 'selected="selected"' : ''); ?>>Vendor</option>
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
                                            echo '<option value="'.$type->Id.'"'.(($type->Id == $contact->Title) ? ' selected' : '').'>'.$type->Name.'</option>';
                                        }
									?>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Job Title</label>
                            <div class="formRight">
                                <?= form_input(array('class'=>'validate[required]','name'=>'JobTitle','id'=>'JobTitle','value'=>$contact->JobTitle)); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Name</label>
                            <div class="formRight"><label style="margin:0;color:red">First:</label><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=>$contact->FirstName,'style'=>'margin:0')); ?></div><div class="fix"></div>
                            <div class="formRight"><label style="margin:0;color:red">Last:</label><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>$contact->LastName,'style'=>'margin:0')); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Personal Email</label>
                            <div class="formRight"><label style="margin:0;color:red">Home:</label><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>'PersonalEmailAddress','id'=>'email','value'=>((isset($contact->Email['home'])) ? $contact->Email['home'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                            <div class="formRight"><label style="margin:0;color:red">Work:</label><?= form_input(array('class'=>'validate[custom[email]]','name'=>'WorkEmailAddress','id'=>'email','value'=>((isset($contact->Email['work'])) ? $contact->Email['work'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                        	<label>Address</label>
                            <div class="formRight"><label style="margin:0;color:red">Street:</label><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => ((isset($contact->Address['street'])) ? $contact->Address['street'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                        	<div class="formRight"><label style="margin:0;color:red">City:</label><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>((isset($contact->Address['city'])) ? $contact->Address['city'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                            <div class="formRight searchDrop"><label style="margin:0;color:red">State:</label><div class="fix"></div>
                                <?= showStates(((isset($contact->Address['state'])) ? $contact->Address['state'] : false)); ?>
                                <?= ((isset($contact->Address['state'])) ? '' : '<span class="formNote">No state found for user</span>'); ?>
                            </div><div class="fix"></div>
                            <div class="formRight"><label style="margin:0;color:red">Zip:</label><?= form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'zip','id'=>'zip','value' => ((isset($contact->Address['zipcode'])) ? $contact->Address['zipcode'] : ''),'style'=>'margin:0')); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Phone</label>
                            <div class="formRight"><label style="margin:0;color:red">Main/Direct:</label><?= form_input(array('name'=>'DirectPhone','id'=>'phone','class'=>'maskPhone validate[required,custom[phone]]','value'=>((isset($contact->Phone['main'])) ? $contact->Phone['main'] : ''),'style'=>'margin:0')); ?><span class="formNote">(999) 999-9999</span></div>
                            <div class="formRight"><label style="margin:0;color:red">Mobile:</label><?= form_input(array('name'=>'MobilePhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($contact->Phone['mobile'])) ? $contact->Phone['mobile'] : ''),'style'=>'margin:0')); ?><span class="formNote">(999) 999-9999</span></div>
                            <div class="formRight"><label style="margin:0;color:red">Fax:</label><?= form_input(array('name'=>'FaxPhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($contact->Phone['fax'])) ? $contact->Phone['fax'] : ''),'style'=>'margin:0')); ?><span class="formNote">(999) 999-9999</span></div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder"><label>Notes</label><div class="formRight"><?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes', 'value' => $contact->Notes)); ?></div><div class="fix"></div></div>
                            
                        <div class="submitForm">               
		                	<input type="hidden" name="contact_id" value="<?= $contact->ContactID; ?>" />
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
			url:'/admin/contacts/form<?= (($page == 'edit') ? '?cid=' . $contact->ContactID : ''); ?>',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= ($page == 'edit') ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						contactListTable();
					}); 
				}else {
					msg = '<?= ($page == 'edit') ? 'There was a problem with editing the contact requested. Please try again.':'There was a problem adding the contact. Please try again.'; ?>';
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
		$('#viewContact div.tab_container div.tab_content').hide();
		$('#viewContact div.tab_container').find(content).css({'display':'block'});
	});
	
	<?php if($page == 'edit') { ?>
	$("#editContact").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php }else { ?>
	$("#addContact").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php } ?>
</script>
