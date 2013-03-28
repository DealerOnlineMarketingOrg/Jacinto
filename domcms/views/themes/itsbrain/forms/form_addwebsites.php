<div class="uDialog">
    <div class="dialog-message" id="addWebsiteForm" title="<?= ((isset($website)) ? 'Edit ' . $client->Name . ' Website' : 'Add New Website To ' . $client->Name); ?>">
        <div class="uiForm">
        	 <div class="widget" style="margin-top:-10px;padding-top:0;">
				<?= form_open(base_url() . 'admin/clients/add_website',array('id'=>'web','class'=>'valid','style'=>'text-align:left;')); ?>
					<fieldset>
						<div class="rowElem noborder noSearch">
		                    <label><span class="req">*</span> Vendor</label>
		                    <div class="formRight">
		                        <select id="vendors" name="vendor" class="chzn-select">
		                        	<? foreach($vendors as $vendor) : ?>
		                        		<?php if(isset($website)) { ?>
		                        			<option <?= ($website->Vendor == $vendor->ID) ? 'selected="selected"' : ''; ?> value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
		                        		<?php }else { ?>
		                        			<option value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
		                        		<?php } ?>
		                        	<? endforeach; ?>
		                        </select>
		                    </div>
		                    <div class="fix"></div>
		                </div>
						<div class="rowElem noborder">
							<label><span class="req">*</span> URL</label>
							<div class="formRight">
								<?= form_input(array('name'=>'url','id'=>'web_url','class'=>'validate[required,custom[url]]','value'=>((isset($website->URL)) ? $website->URL : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Google Analytics UA Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'ua_code','id'=>'google_ua_code','value'=>((isset($website->GoogleUACode)) ? $website->GoogleUACode : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Google Webmaster Tools<br />Meta Code Number</label>
							<div class="formRight">
								<?= form_input(array('name'=>'meta_code_number','id'=>'meta_code_number','value'=>((isset($website->GoogleWebToolsMetaCode)) ? $website->GoogleWebToolsMetaCode : ''))); ?>
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
	jQuery('#web').submit(function(e) {
		e.preventDefault();
		<? if(isset($website)) { ?>
			var formData = jQuery(this).serialize();
			jQuery.post('/admin/clients/add_website',formData,function(data) {
				if(data) {
					jAlert('New website added successfully.','Success');
				}else {
					jAlert('Error adding new website.','Error');
				}
			});
		<?php }else { ?>
			var formData = jQuery(this).serialize();
			jQuery.post('/admin/clients/edit_website',formData,function(data) {
				if(data) {
					jAlert('Website edited successfully.','Success');
				}else {
					jAlert('Error editing website.','Error');
				}
			});
		<?php } ?>
			
	});
	
	jQuery(".chzn-select").chosen();
	jQuery("#addWebsiteForm").dialog({
		minWidth:500,
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
