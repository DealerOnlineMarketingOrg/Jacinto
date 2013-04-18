<div class="uDialog">
	<?php $pageID = (($page == 'edit') ? 'editContactEmail' : 'addContactEmail'); ?>
    <div class="dialog-message popper" id="<?= $pageID; ?>" title="<?= (($page == 'edit') ? 'Edit' : 'Add'); ?> Email">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <div class="tab_container">
            		<div id="contactEmail" class="tab_content">
						<?php
                            if($page == 'edit') :
                                echo form_open('/admin/contacts/editEmail',array('id'=>'editContactEmailForm','class' => 'validate mainForm formPop','style' => 'text-align:left'));
                            else :
                                echo form_open('/admin/contacts/addEmail',array('id'=>'addContactEmailForm','class'=>'validate mainForm formPop','style' => 'text-align:left'));				
                            endif;
                        ?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="contactEmailType" class="chzn-select validate[required]" style="width:350px" name="type">
                                            <option value="Home" <?= ($contact) ? (($type == 'home') ? 'selected="selected"' : '') : ''; ?>>Home</option>
                                            <option value="Work" <?= ($contact) ?
												(($type == 'work' || $type == 'main') ? 'selected="selected"' : '') : 'selected="selected"'; ?>>Work</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="fix"></div>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Email</label>
                                    <div class="formRight">
                                        <?= form_input(array('class'=>'validate[required]','name'=>'EmailNumber','id'=>'EmailNumber','value'=>($contact) ? $contact->Email[$type] : '')); ?>
										<span class="formNote">example@example.com</span></div>
                                    </div>
                                </div>    
                                <div class="fix"></div>
                                <div class="submitForm">               
                                    <input type="hidden" name="contact_id" value="<?= ($contact) ? $contact->ContactID : ''; ?>" />
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
				</div> 
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
			url:'/admin/contacts/formEmail<?= (($page == 'edit') ? '?cid=' . (($contact) ? $contact->ContactID : '') : ''); ?>',
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
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
		}
		
		if(activeContent == 'contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
		}
		<?php } ?>
		
		//alert(content);
	});
	
	<?php if($page != 'edit') { ?>
	$("#addContactEmail").dialog({
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
					class:'greenBtn saveEmailBtn',
					text:"Add",
					click:function() { $('.formPop').submit(); }
				},

		]
	});
	<?php }else { ?>
	$("#editContactEmail").dialog({
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
					class:'redBtn saveEmailBtn',
					text:"Save",
					click:function() { $('.formPop').submit(); }
				},
		]
	});
	<?php } ?>
</script>
