<div class="uDialog">
	<?php $pageID = (($page == 'edit') ? 'editContact' : 'addContact'); ?>
    <div class="dialog-message popper" id="<?= $pageID; ?>" title="<?= (($page == 'edit') ? 'Edit' : 'Add'); ?> Contact">
        <div class="uiForm">
			<style type="text/css">
				#editClient label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editClient .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <ul class="tabs">
	                    <li class="activeTab"><a href="javascript:void(0);" rel="contactDetails">Contact Details</a></li>
		                <?php if ($page == 'edit') { ?>
                    	<li><a href="javascript:void(0);" rel="websites">Websites</a></li>
	                    <li><a href="javascript:void(0);" rel="contactInfo">Contact Info</a></li>
        		     	<?php } ?>
                </ul>
                <div class="tab_container">
            		<div id="contactDetails" class="tab_content">
						<?php
                            if($page == 'edit') :
                                echo form_open('/admin/contacts/edit',array('id'=>'editContactForm','class'=>'validate mainForm formPop','style'=>'text-align:left'));
                            else :
                                echo form_open('/admin/contacts/add',array('id'=>'addContactForm','class'=>'validate mainForm formPop','style'=>'text-align:left'));				
                            endif;
                        ?>
                            <fieldset>
								<div class="rowElem noborder">
                                    <label><span class="req">*</span> Name</label>
                                    <div class="formRight">
										<div style="position:relative;float:left"><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=>(($contact) ? $contact->FirstName : ''),'style'=>'margin:0','style'=>'width:22em !important','placeholder'=>'Enter First Name')); ?><span class="formNote">First Name</span></div>
                                    	<div style="position:relative;float:left"><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>(($contact) ? $contact->LastName : ''),'style'=>'margin:0','style'=>'width:22em !important','placeholder'=>'Enter Last Name')); ?><span class="formNote">Last Name</span></div>
                                </div>
                                <div class="fix"></div>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="contactType" class="chzn-select validate[required]" style="width:350px" name="type">
                                            <option value="CID" <?= ($contact) ? (($contact->TypeCode == 'CID') ? 'selected="selected"' : '') : ''; ?>>Client</option>
                                            <option value="VID" <?= ($contact) ? (($contact->TypeCode == 'VID') ? 'selected="selected"' : '') : ''; ?>>Vendor</option>
                                            <!-- Default -->
                                            <option value="GID" <?= ($contact) ?
												(($contact->TypeCode == 'GID' || $contact->TypeCode == '') ? 'selected="selected"' : '') : 'selected="selected"'; ?>>General</option>
                                        </select>
                                    </div>
                                    <div class="fix"></div>
                                    <div id="contactParentClient" style="<?= ($contact) ? (($contact->TypeCode == 'CID') ? '' : 'display:none') : 'display:none'; ?>">
                                        <label><span class="req">*</span> Client</label>
                                        <div class="formRight searchDrop">
                                            <select class="chzn-select validate[required]" style="width:350px" name="parentClient">
                                                <option value=""></option>
                                                <?php 
													foreach($clients as $client) :
														echo '<option ' . ((($contact) ? ($client->ClientID == 
														$contact->DealershipID) : false) ? ' selected' : '') . ' value="' . 
														$client->ClientID . '">' . 
														$client->Name . '</option>';
													endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="contactParentVendor" style="<?= ($contact) ? (($contact->TypeCode == 'VID') ? '' : 'display:none') : 'display:none'; ?>">
                                        <label><span class="req">*</span> Vendor</label>
                                        <div class="formRight searchDrop">
                                            <select class="chzn-select validate[required]" style="width:350px" name="parentVendor">
                                                <option value=""></option>
                                                <?php 
													foreach($vendors as $vendor) :
														echo '<option ' . ((($contact) ? ($vendor->ID == 
														$contact->DealershipID) : false) ? ' selected' : '') . ' value="' .
														$vendor->ID . '">' .
														$vendor->Name . '</option>';
													endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="contactParentGeneral" style="<?= ($contact) ? (($contact->TypeCode == 'GID') ? '' : 'display:none') : 'display:none'; ?>">
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Title</label>
                                    <div class="formRight">
                                        <select class="chzn-select validate[required]" style="width:350px" name="jobTitleType">
                                            <?php
                                                foreach ($types as $type) {
                                                    echo '<option value="'.$type->Id.'"'.((($contact) ? ($type->Id == $contact->Title) : false) ? ' selected' : '').'>'.$type->Name.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Address</label>
                                    <div class="formRight">
										<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => ((isset($contact->Address['street'])) ? $contact->Address['street'] : ''),'style'=>'margin:0','placeholder'=>'Enter Street')); ?>
                                    </div>
                                    <div class="fix"></div>
                                    <label>City</label>
                                    <div class="formRight">
										<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>((isset($contact->Address['city'])) ? $contact->Address['city'] : ''),'style'=>'margin:0','placeholder'=>'Enter City')); ?>
                                    </div>
                                    <div class="fix"></div>
                                    <label>State</label>
                                    <div class="formRight searchDrop" style="margin-top:15px;margin-bottom:10px">
                                        <?= showStates(((isset($contact->Address['state'])) ? $contact->Address['state'] : false)); ?>
                                        <?php //((isset($contact->Address['state'])) ? '' : '<span class="formNote">No state found for user</span>'); ?>
                                    </div>
                                    <div class="fix"></div>
                                    <label>Zip</label>
                                    <div class="formRight">
										<?= form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'zip','id'=>'zip','value' => ((isset($contact->Address['zipcode'])) ? $contact->Address['zipcode'] : ''),'style'=>'margin:0','placeholder'=>'Enter Zip Code')); ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <?php if ($page == 'add') { ?>
                                    <div class="rowElem noborder">
                                        <label>Primary Email</label>
                                        <?php $currentEmail = '';
                                        if (isset($contact->Email)) {
                                            foreach ($contact->Email as $type => $email) {
                                                if ($type == $contact->PrimaryEmailType) {
                                                    $currentEmail = $email;
                                                    break;
                                                }
                                            }
                                        } ?>
                                        <div class="formRight"><?= form_input(array('class'=>'validate[custom[email]]','name'=>'email','id'=>'email','value'=>$currentEmail,'style'=>'margin:0','placeholder'=>'Enter Work Email')); ?></div>
                                        <div class="fix"></div>
                                    </div>
                                    <div class="rowElem noborder">
                                        <label>Primary Phone</label>
                                        <?php $currentPhone = '';
                                        if (isset($contact->Phone)) {
                                            foreach ($contact->Phone as $type => $phone) {
                                                if ($type == $contact->PrimaryPhoneType) {
                                                    $currentPhone = $phone;
                                                    break;
                                                }
                                            }
                                        } ?>
                                        <div class="formRight">
                                            <div style="position:relative;float:left"><?= form_input(array('name'=>'phone','id'=>'phone','class'=>'maskPhoneExt validate[required,custom[phone]]','value'=>$currentPhone,'style'=>'margin:0','placeholder'=>'Enter Work Phone','style'=>'width:25em !important')); ?><span class="formNote">(999) 999-9999 x99999</span></div>
                                    </div>
                                    <div class="fix"></div>
                                <?php } ?>
                                <div class="rowElem noborder">
                                	<label>Notes</label>
                                    <div class="formRight">
										<?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes','value'=>(($contact) ? $contact->Notes : ''))); ?>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                                <div class="submitForm">
                                    <input type="hidden" name="contact_id" value="<?= (($contact) ? $contact->ContactID : ''); ?>" />
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
                    <?php if ($page == 'edit') { ?>
                    <div id="websites" class="tab_content" style="display:none;">
                        <?= $websites; ?>
                    </div>
                    <div id="contactInfo" class="tab_content" style="display:none;">
                        <div style="margin-top:10px;margin-bottom:60px;">
                            <div style="text-align:center;"><label><div class="iPhone" style="width:14em;margin:0 auto;">Phone Numbers</div></label></div>
                            <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                                <thead>
                                    <tr>
                                        <td width="10%" style="text-align:left;padding-left:10px;">Primary</td>
                                        <td width="80%" style="text-align:left;padding-left:10px;">Phone Number</td>
                                        <td width="10%" style="text-align:left;padding-left:10px;">Actions</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($contact) foreach ($contact->Phone as $type => $phone) { ?>
                                    <tr>
                                        <td width="10%"><div style="width:20px;margin:0 auto;"><input type="radio" class="phonePrimary" name="phonePrimary" value="<?= $phone; ?>" <?= ($phone == $contact->PrimaryPhoneType) ? 'checked' : ''; ?> /></div></td>
                                        <td width="80%"><?= (($contact) ? $contact->Phone[$type] : ''); ?></td>
                                        <td width="10%"><div style="width:20px;margin:0 auto;"><a title="Edit Phone Number" href="javascript:editPhone('<?= $contact->ContactID; ?>','<?= $type; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a></div></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <a href="javascript:addPhone('<?= $contact->ContactID; ?>');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Phone</a>
                        </div>
                        
                        <div style="margin-top:10px;">
                            <div style="text-align:center;"><label class="iMail"><div class="iMail" style="width:14em;margin:0 auto;">Email Addresses</div></label></div>
                            <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                                <thead>
                                    <tr>
                                        <td width="10%" style="text-align:left;padding-left:10px;">Primary</td>
                                        <td width="80%" style="text-align:left;padding-left:10px;">Email Addresses</td>
                                        <td width="10%" style="text-align:left;padding-left:10px;">Actions</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($contact) foreach ($contact->Email as $type => $email) { ?>
                                    <tr>
                                        <td width="10%"><div style="width:20px;margin:0 auto;"><input type="radio" class="emailPrimary" name="emailPrimary" value="<?= $email; ?>" <?= ($email == $contact->PrimaryEmailType) ? 'checked' : ''; ?> /></div></td>
                                        <td width="80%"><?= (($contact) ? $contact->Email[$type] : ''); ?></td>
                                        <td width="10%"><div style="width:20px;margin:0 auto;"><a title="Edit Email" href="javascript:editEmail('<?= $contact->ContactID; ?>','<?= $type; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a></div></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <a href="javascript:addEmail('<?= $contact->ContactID; ?>');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Email</a>
                        </div>
                    <div class="fix"></div>
                    </div>
                    <?php } ?>
				</div> 
        	</div> <? //end widget ?>
		</div>
	</div>
</div>

<div id="addContactPhonePop"></div>
<div id="editContactPhonePop"></div>
<div id="addContactEmailPop"></div>
<div id="editContactEmailPop"></div>

<style type="text/css">
.rowElem > label {padding-top:5px;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	
	$('#contactType').change(function(e) {
		$('#contactParentClient').css('display',(($(this).val()) == 'CID' ? '' : 'none'));
		$('#contactParentVendor').css('display',(($(this).val()) == 'VID' ? '' : 'none'));
		$('#contactParentGeneral').css('display',(($(this).val()) == 'GID' ? '' : 'none'));
	});
	
	$('.formPop').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/contacts/form<?= (($page == 'edit') ? '?uid=' . (($contact) ? $contact->ContactID : '') : ''); ?>',
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
		//alert(content);
		$('#<?= $pageID; ?> div.tab_container div.tab_content').hide();
		$('#<?= $pageID; ?> div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		<?php if(isset($view)) { ?>
		
		<?php }else { ?>
		
		if(activeContent == 'contactDetails') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').removeClass('hidden');
			}
		}
		<?php } ?>
		
		//alert(content);
	});
	
	<?php if($page == 'edit') { ?>
	$("#editContact").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$('#<?= $pageID; ?>').dialog('close')}
			},
				{
					class:'redBtn saveContactBtn',
					text:"Save",
					click:function() { $('.formPop').submit(); }
				},
			<?php if(GateKeeper('Website_Add',$this->user['AccessLevel'])) { ?>
				{
					class:'greenBtn hidden addWebsiteBtn',
					text:"Add New Website",
					click:function() { addWebsiteForm('<?= ($contact) ? $contact->ContactID : ''; ?>')}
				},
			<?php } ?>
				{
					class:'redBtn hidden savePrimariesBtn',
					text:"Save",
					click:function() { updatePrimaries('<?= ($contact) ? $contact->ContactID : ''; ?>',$(".phonePrimary:checked").val(),$(".emailPrimary:checked").val())}
				},

		]
	});
	<?php }else { ?>
	$("#addContact").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$('#<?= $pageID; ?>').dialog('close')}
			},
				{
					class:'greenBtn addContactBtn',
					text:"Add",
					click:function() { $('.formPop').submit(); }
				},
		]
	});
	<?php } ?>
</script>
