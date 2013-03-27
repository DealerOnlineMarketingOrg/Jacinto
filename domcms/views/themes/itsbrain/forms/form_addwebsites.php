<div class="uDialog">
    <div class="dialog-message" id="addWebsiteForm" title="Add New Website to <?= $client->Name; ?>">
        <div class="uiForm">
        	 <div class="widget" style="margin-top:-10px;padding-top:0;">
				<?= form_open(base_url() . 'admin/clients/add_website',array('id'=>'client_' . $client->ID,'class'=>'valid','style'=>'text-align:left;')); ?>
					<fieldset>
						<div class="rowElem noborder noSearch">
		                    <label><span class="req">*</span> Vendor</label>
		                    <div class="formRight">
		                        <select id="vendors" name="vendor" class="chzn-select">
		                        	<? foreach($vendors as $vendor) : ?>
		                        		<option value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
		                        	<? endforeach; ?>
		                        </select>
		                    </div>
		                    <div class="fix"></div>
		                </div>
						<div class="rowElem noborder">
							<label><span class="req">*</span> URL</label>
							<div class="formRight">
								<?= form_input(array('name'=>'url','id'=>'web_url','class'=>'validate[required,custom[url]]')); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Google Analytics UA Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'ua_code','id'=>'google_ua_code')); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Google Webmaster Tools<br />Meta Code Number</label>
							<div class="formRight">
								<?= form_input(array('name'=>'meta_code_number','id'=>'meta_code_number')); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Google+ Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'gplus_code','id'=>'gplus_code')); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Bing Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'bing_code','id'=>'bing_code')); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Yahoo Code</label>
							<div class="formRight">
								<?= form_input(array('name'=>'yahoo_code','id'=>'yahoo_code')); ?>
							</div>
							<div class="fix"></div>
						</div>
						<div class="rowElem noborder">
							<label>Global JS Script</label>
							<div class="formRight">
								<?= form_textarea(array('name'=>'global_code','id'=>'global_code')); ?>
							</div>
							<div class="fix"></div>
						</div>
		                <div class="submitForm">
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
