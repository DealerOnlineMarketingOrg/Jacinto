<div class="uDialog">
    <div class="dialog-message" id="addWebsiteForm" title="<?= (($website) ? 'Edit ' . $client->Name . ' Website' : 'Add New Website To ' . $client->Name); ?>">
        <div class="uiForm">
        	 <div class="widget" style="margin-top:-10px;padding-top:0;">
				<?= form_open(base_url() . 'admin/clients/add_website',array('id'=>'web','class'=>'valid mainForm','style'=>'text-align:left;')); ?>
					<fieldset>
						<div class="rowElem noborder noSearch">
		                    <label><span class="req">*</span> Vendor</label>
		                    <div class="formRight">
		                        <select id="vendors" name="vendor" class="chzn-select validate[required]" style="float:left;">
                                	<option value="">Choose a Vendor</option>
		                        	<? foreach($vendors as $vendor) : ?>
		                        		<?php if(isset($website)) { ?>
		                        			<option <?= ($website->Vendor == $vendor->ID) ? 'selected="selected"' : ''; ?> value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
		                        		<?php }else { ?>
		                        			<option value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
		                        		<?php } ?>
		                        	<? endforeach; ?>
                                    <option value="custom">Custom</option>
		                        </select>
                                <div id="CustomVendor" style="float:left;display:none;">
                                	<?= form_input(array('name'=>'custom_vendor','id'=>'custom_vendor')); ?>
                                    <span class="formNote">Type the vendor name here and the vendor will be added to the system.</span>
                                </div>
		                    </div>
		                    <div class="fix"></div>
		                </div>
						<div class="rowElem noborder">
							<label>URL</label>
							<div class="formRight">
								<?= form_input(array('name'=>'url','id'=>'url','value'=>((isset($website->URL)) ? $website->URL : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>UA Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'ua_code','id'=>'google_ua_code','value'=>((isset($website->GoogleUACode)) ? $website->GoogleUACode : ''))); ?>
                                <span class="formNote">Google Analytics</span>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Meta Code Number</label>
							<div class="formRight">
								<?= form_input(array('name'=>'meta_code_number','id'=>'meta_code_number','value'=>((isset($website->GoogleWebToolsMetaCode)) ? $website->GoogleWebToolsMetaCode : ''))); ?>
                                <span class="formNote">Google Webmaster Tools</span>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Google+ Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'gplus_code','id'=>'gplus_code','value'=>((isset($website->GooglePlusCode)) ? $website->GooglePlusCode : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Bing Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'bing_code','id'=>'bing_code','value'=>((isset($website->BingCode)) ? $website->BingCode : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Yahoo Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'yahoo_code','id'=>'yahoo_code','value'=>((isset($website->YahooCode)) ? $website->YahooCode : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Global JS Script</label>
							<div class="formRight">
								<?= form_textarea(array('name'=>'global_code','id'=>'global_code','value'=>((isset($website->GlobalScript)) ? $website->GlobalScript : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Notes</label>
							<div class="formRight">
								<?= form_textarea(array('name'=>'notes','id'=>'web_notes','value'=>((isset($website->Description))?$website->Description:''))); ?>
							</div>
							<div class="fix"></div>
						</div>
		                <div class="submitForm">
		                	<?php if(isset($website->ID)) { ?>
		                		<input type="hidden" name="web_id" value="<?= $website->ID; ?>" />
		                	<?php } ?>
		               		<input type="hidden" name="ClientID" value="<?php echo  $client->ID; ?>" />
		                    <input type="submit" value="submit" class="redBtn" />
		                </div> 
					</fieldset>
				<?= form_close(); ?>
				<div class="fix"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery('#web').validationEngine({promptPosition : "top", scroll: true});
	jQuery('#vendors').live('change',function() {
		var selectBox = jQuery(this);
		if(selectBox.val() == '') {
			alert('Vendors are required');	
		}
		if(selectBox.val() == 'custom') {
			jQuery('#CustomVendor').slideDown('fast');
			jQuery('#CustomVendor input').addClass('validate[required]');
			jQuery('#vendors').removeClass('validate[required]');
		}else {
			jQuery("#vendors").addClass('validate[required]');
			jQuery("#CustomVendor input").removeClass('validate[required]');
			jQuery('#CustomVendor').slideUp('fast');	
		}
	});

	jQuery('#web').submit(function(e) {
		e.preventDefault();
		var formData = jQuery(this).serialize();
		var cUrl = '';
		var type = 0;
		var msg = 'New website added successfully.';
		var title = 'Success';
		<?php if(isset($website)) { ?>
			cUrl = '<?= base_url(); ?>admin/websites/edit?cid=<?= $client->ID; ?>&wid=<?= $website->ID ?>';
			type = 1;
			msg = 'Website edited successfully.';
		<?php }else { ?>
			type = 2;
			cUrl = '<?= base_url(); ?>admin/websites/add?cid=<?=$client->ID; ?>';
		<?php } ?>
		
		if(type == 0) {
			msg = 'There was a problem with processing your change. Please try again';
			title = 'Error';
		}
		
		jQuery.ajax({
			type:'POST',
			url:cUrl,
			data:formData,
			success:function(data) {
				if(data == '1') {
					//alert(data);
					jAlert(msg,title,function() {
						reloadWebsites('<?= $client->ID; ?>');	
					});
				}else {
					jAlert('There was a problem with processing your change. Please try again.','Error',function() {
						reloadWebsites('<?= $client->ID; ?>');
					});	
				}
			}
		});
	});
	
	function reloadWebsites(cid) {
		jQuery('#addWebsiteForm').dialog('close');	
		jQuery('#websites').slideUp('fast',function() {
			jQuery('#websites').empty();
			jQuery('#loader').fadeIn('fast',function() {
				jQuery('#loader').fadeIn('fast',function() {
					jQuery.ajax({
						type:'GET',
						url:'<?= base_url(); ?>admin/websites/load_table?cid='+cid,
						success:function(data) {
							jQuery('#websites').html(data);
							jQuery('#loader').delay(2000).fadeOut('fast',function() {
								jQuery('#websites').slideDown('fast');
							});
						}
					});
				});
			});
		});					
	}
	
	jQuery(".chzn-select").chosen();
	jQuery("#addWebsiteForm").dialog({
		minWidth:600,
		height:500,
		autoOpen: true,
		modal: false,
		buttons: {
			Close:function() {
				jQuery('#addWebsiteForm').dialog('close');
				jQuery('#addWebsiteForm').empty();
			},
		}
	});

</script>
