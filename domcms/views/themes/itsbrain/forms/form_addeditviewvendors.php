<?php
	if(isset($vendor)) {
		$vendor->Address = mod_parser($vendor->Address);
	}
?>
<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="editVendor" title="<?= ((isset($vendor)) ? 'Edit' : 'View'); ?> Vendor Details">
        <div class="uiForm">
			<style type="text/css">
				#editVendor label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editVendor .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Vendor Details</a></li>
                    <?php if(isset($websites)) { ?>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <?php } 
					if(isset($contacts)) { ?>
            		<li><a href="javascript:void(0);" rel="contacts">Contacts</a></li>
                    <?php } ?>
            	</ul>
            	<div class="tab_container">
            		<div id="clientInfo" class="tab_content">
						<?php
                            if(isset($vendor)) :
                                echo form_open('/admin/vendor/edit',array('id'=>'editVendorForm','class' => 'validate mainForm formPop','style'=>'text-align:left;'));
                            else :
                                echo form_open('/admin/vendor/add',array('id'=>'addVendorForm','class'=>'validate mainForm formPop','style'=>'text-align:left;'));				
                            endif;
                        ?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Vendor Name</label>
                                    <div class="formRight">
                                        <?php
											if(isset($view)) {
													echo form_input(array('disabled'=>'disabled','id'=>'vendor_name','name'=>'name','class'=>'validate[required]','value'=>$vendor->Name));
											}else {
												if(isset($vendor->Name)) {
													echo form_input(array('id'=>'vendor_name','name'=>'name','class'=>'validate[required]','value'=>$vendor->Name));
												}else {
													echo form_input(array('id'=>'group_name','name'=>'name','class'=>'validate[required]'));
												}	
											}
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Primary Phone</label>
                                    <div class="formRight">
                                        <?php
											if(isset($view)) {
													echo form_input(array('disabled'=>'disabled','id'=>'phone','name'=>'phone','class'=>'validate[custom[phone]] maskPhoneExt','value'=>$vendor->Phone));
											}else {
												if(isset($vendor->Phone)) {
													echo form_input(array('id'=>'phone','name'=>'phone','class'=>'validate[custom[phone]] maskPhoneExt','value'=>$vendor->Phone));
												}else {
													echo form_input(array('id'=>'phone','name'=>'phone','class'=>'validate[custom[phone]] maskPhoneExt'));
												}	
											}
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Street</label>
                                    <div class="formRight">
                                        <?php
											if(isset($view)) {
													echo form_input(array('disabled'=>'disabled','id'=>'street','name'=>'street','class'=>'','value'=>(isset($vendor->Address['street'])) ? $vendor->Address['street'] : ''));
											}else {
												if(isset($vendor->Address)) {
													echo form_input(array('id'=>'phone','name'=>'street','class'=>'','value'=>(isset($vendor->Address['street'])) ? $vendor->Address['street'] : ''));
												}else {
													echo form_input(array('id'=>'phone','name'=>'street','class'=>''));
												}	
											}
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>City</label>
                                    <div class="formRight">
                                        <?php
											if(isset($view)) {
													echo form_input(array('disabled'=>'disabled','id'=>'state1','name'=>'city','class'=>'','value'=>(isset($vendor->Address['city'])) ? $vendor->Address['city'] : ''));
											}else {
												if(isset($vendor->Address)) {
													echo form_input(array('id'=>'city1','name'=>'city','class'=>'','value'=>(isset($vendor->Address['city'])) ? $vendor->Address['city'] : ''));
												}else {
													echo form_input(array('id'=>'city1','name'=>'city','class'=>''));
												}	
											}
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>State</label>
                                    <div class="formRight">
										<?php if(isset($view)) {
                                            echo showStates(((isset($vendor->Address['state'])) ? $vendor->Address['state'] : false),true);
                                        }else {
                                            echo showStates(((isset($vendor->Address['state'])) ? $vendor->Address['state'] : false));
                                        } ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Zip Code</label>
                                    <div class="formRight">
                                        <?php
											if(isset($view)) {
													echo form_input(array('disabled'=>'disabled','id'=>'zip','name'=>'zipcode','class'=>'','value'=>(isset($vendor->Address['zipcode'])) ? $vendor->Address['zipcode'] : ''));
											}else {
												if(isset($vendor->Address)) {
													echo form_input(array('maxlength'=>'5','id'=>'city','name'=>'zipcode','class'=>'','value'=>(isset($vendor->Address['zipcode'])) ? $vendor->Address['zipcode'] : ''));
												}else {
													echo form_input(array('maxlength'=>'5','id'=>'city','name'=>'zipcode','class'=>''));
												}	
											}
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Notes</label>
                                    <div class="formRight">
                                    	<?php if(isset($view)) {
											echo form_textarea(array('name'=>'notes','id'=>'notes','disabled'=>'disabled','value'=>((isset($vendor->Notes)) ? $vendor->Notes : '')));	
										}else {
                                       		echo form_textarea(array('name'=>'notes','id'=>'notes','value'=>((isset($vendor->Notes)) ? $vendor->Notes : ''))); 
										}?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <?php if(!isset($view)) { ?>
                                <div class="submitForm">
                                    <?php if(isset($vendor->ID)) { ?>
                                        <input type="hidden" name="ID" value="<?= $vendor->ID; ?>" />
                                    <?php } ?>
                                    <input type="submit" value="<?= (isset($vendor)) ? 'Save' : 'Add'; ?>" class="<?= (isset($vendor)) ? 'redBtn' : 'greenBtn'; ?>" />
                                </div>
                                <?php } ?>
                            </fieldset>
                        <?= form_close(); ?>
                     </div>
                     <div id="websites" class="tab_content" style="display:none;">
                     	<?php 
							if(isset($view)) {
								echo WebsiteListingTable($vendor->ID,true,true,true,true);	
							}else {
								echo WebsiteListingTable($vendor->ID,true,false,true,true);	
							}
						 ?>
                        <script type="text/javascript">
							jQuery('.websiteTable').dataTable({
								"bJQueryUI": true,
								"sPaginationType": "full_numbers",
								"sDom": '<""f>t<"F"lp>',
								'iDisplayLength':1000,
								"aLengthMenu": [[-1,10,25,50],['All',10,25,50]],
								'bFilter':false,
							});
						</script>

                     </div>
                     <div id="contacts" class="tab_content" style="display:none;">
                    	<?php 
							if(isset($view)) { 
								echo ContactsListingTable($vendor->ID,true,true,true,'VID');
							}else {
                    			echo ContactsListingTable($vendor->ID,true,false,true,'VID');
							}
						?>
                        <script type="text/javascript">
							jQuery('.contacts').dataTable({
								"bJQueryUI": true,
								"sPaginationType": "full_numbers",
								"sDom": '<""f>t<"F"lp>',
								'iDisplayLength':1000,
								"aLengthMenu": [[-1,10,25,50],['All',10,25,50]],
								'bFilter':false,
							});
						</script>
                     </div>
                  </div>
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
	var $ = jQuery;
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	$(".maskIntPhone").mask("+33 999 999 999");
	$(".maskTin").mask("99-9999999");
	$(".maskSsn").mask("999-99-9999");
	$(".maskProd").mask("a*-999-a999", { placeholder: " " });
	$(".maskEye").mask("~9.99 ~9.99 999");
	$(".maskPo").mask("PO: aaa-999-***");
	$(".maskPct").mask("99%");

	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});

	<?php if(!isset($view)) { ?>

	$('#editVendorForm,#addVendorForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/vendors/form<?= ((isset($vendor)) ? '?vid=' . $vendor->ID : ''); ?>',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= (isset($vendor)) ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						vendorTable();
					}); 
				}else {
					msg = '<?= (isset($vendor)) ? 'There was a problem with editing the vendor requested. Please try again.':'There was a problem adding the vendor. Please try again.'; ?>';
					jAlert(msg,'Error');
				}
			}
		});
	});
	<?php } ?>
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		
		$('#editVendor div.tab_container div.tab_content').hide();
		$('#editVendor div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		<?php if(!isset($view)) { ?>
				
		if(activeContent == 'contacts') {
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
		
		if(activeContent == 'clientInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
		}
		<?php } ?>

	});
	
	$("#editVendor").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$('#editVendor').dialog('close')}
			},
			<?php if(GateKeeper('Website_Add',$this->user['AccessLevel'])) { ?>
				<?php if(!isset($view) && isset($vendor->ID)) { ?>
				{
					class:'greenBtn hidden addWebsiteBtn',
					text:"Add New Website",
					click:function() { addWebsiteForm('<?= $vendor->ID; ?>')}
				}, <?php } ?>
			<?php } ?>
			<?php if(GateKeeper('Contact_Add',$this->user['AccessLevel'])) { ?>
				<?php if(!isset($view)) { ?>
				{
					class:'greenBtn hidden addContactBtn',
					text:"Add New Contact",
					click:function() { addContact()}
				}, <?php } ?>
			<?php } ?>
		] 
	});
</script>
