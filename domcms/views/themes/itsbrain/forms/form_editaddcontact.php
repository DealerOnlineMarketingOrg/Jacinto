<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="<?= ((isset($contact)) ? 'editContact' : 'addContact'); ?>" title="<?= ((isset($contact)) ? 'Edit' : 'Add'); ?> Contact">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
            	<?php
					if(isset($contact)) :
						echo form_open('/admin/contacts/edit',array('id'=>'editContactForm','class' => 'validate mainForm formPop'));
					else :
						echo form_open('/admin/contacts/add',array('id'=>'addContactForm','class'=>'validate mainForm formPop'));				
					endif;
				?>
                    <fieldset>
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
                            <label><span class="req">*</span> Company</label>
                            <div class="formRight searchDrop">
                                <select class="chzn-select validate[required]" style="width:350px" name="company">
                                    <option value=""></option>
                                    <?php 
                                        foreach($clients as $client) :
                                            echo '<option ' . (($client->ClientID == $contact->DealershipID) ? ' selected="selected"' : '') . ' value="' . $client->ClientID . '">' . $client->Name . '</option>';
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Job Title</label>
                            <div class="formRight">
                                <?= form_input(array('class'=>'validate[required]','name'=>'JobTitle','id'=>'JobTitle','value'=>$contact->JobTitle)); ?>
                                <p class="formNote">Contacts Job Title</p>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Personal Email</label>
                            <div class="formRight"><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>'PersonalEmailAddress','id'=>'email','value'=>((isset($contact->Email['home'])) ? $contact->Email['home'] : ''))); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Work Email</label>
                            <div class="formRight"><?= form_input(array('class'=>'validate[custom[email]]','name'=>'WorkEmailAddress','id'=>'email','value'=>((isset($contact->Email['work'])) ? $contact->Email['work'] : ''))); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> First Name</label>
                            <div class="formRight"><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=>$contact->FirstName)); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Last Name</label>
                            <div class="formRight"><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>$contact->LastName)); ?></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder"><label>Address</label><div class="formRight"><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => ((isset($contact->Address['street'])) ? $contact->Address['street'] : ''))); ?></div><div class="fix"></div></div>
                        <div class="rowElem noborder"><label>City</label><div class="formRight"><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>((isset($contact->Address['city'])) ? $contact->Address['city'] : ''))); ?></div><div class="fix"></div></div>
                        <div class="rowElem noborder">
                            <label>State</label>
                            <div class="formRight searchDrop">
                                <?= showStates(((isset($contact->Address['state'])) ? $contact->Address['state'] : false)); ?>
                                <?= ((isset($contact->Address['state'])) ? '' : '<span class="formNote">No state found for user</span>'); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder"><label>Zip Code</label><div class="formRight"><?= form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'zip','id'=>'zip','value' => ((isset($contact->Address['zipcode'])) ? $contact->Address['zipcode'] : ''))); ?></div><div class="fix"></div></div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Direct Number</label>
                            <div class="formRight"><?= form_input(array('name'=>'DirectPhone','id'=>'phone','class'=>'maskPhone validate[required,custom[phone]]','value'=>((isset($contact->Phone['main'])) ? $contact->Phone['main'] : ''))); ?><span class="formNote">(999) 999-9999</span></div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Mobile Number</label>
                            <div class="formRight"><?= form_input(array('name'=>'MobilePhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($contact->Phone['mobile'])) ? $contact->Phone['mobile'] : ''))); ?><span class="formNote">(999) 999-9999</span></div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Fax Number</label>
                            <div class="formRight"><?= form_input(array('name'=>'FaxPhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($contact->Phone['fax'])) ? $contact->Phone['fax'] : ''))); ?><span class="formNote">(999) 999-9999</span></div>
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
			url:'/admin/contacts/form<?= ((isset($contact)) ? '?cid=' . $contact->ContactID : ''); ?>',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= (isset($contact)) ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						contactListTable();
					}); 
				}else {
					msg = '<?= (isset($contact)) ? 'There was a problem with editing the contact requested. Please try again.':'There was a problem adding the contact. Please try again.'; ?>';
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
	
	<?php if(isset($contact)) { ?>
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
