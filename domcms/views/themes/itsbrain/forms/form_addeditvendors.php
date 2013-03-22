 <div class="uDialog" id="AddEditVendors">
    <div id="addeditvendorsform" class="dialog-message" title="<?= isset($vendor) ? 'Edit Vendor' : 'Add Vendor'; ?>">
        <div class="uiForm" style="text-align:left;">
		    <!-- Form begins -->
		    <?= form_open('/admin/vendors/form_processor/vendors/' . ((isset($vendor)) ? 'edit' : 'add'),array('name'=>'add','class'=>'mainForm','id'=>'usualValidate')); ?>
		        <!-- Input text fields -->
		        <fieldset>
		            <div class="widget first">
		                <div class="head"><h5 class="iList"><?= ((isset($vendor)) ? 'Edit' : 'Add'); ?> Agency</h5></div>
		                <div class="rowElem noborder">
		                    <label>Name<span class="req">*</span></label>
		                    <div class="formRight"><?= form_input(array('class','required validate[required]','name' => 'name','id'=>'name', 'value'=>((isset($vendor) ? $vendor->Name : '')))); ?></div><div class="fix"></div>
		                </div>
		                <div class="rowElem noborder">
		                	<label>Address</label>
		                	<div class="formRight">
		                		<?= form_input(array('class','required validate[required]','name'=>'street','id'=>'street','value'=>((isset($vendor)) ? $vendor->Address['street'] : ''))); ?>
		                		<span class="formNote">Street</span>
		                		<?= form_input(array('class','required validate[required]','name'=>'city','id'=>'city','value'=>((isset($vendor)) ? $vendor->Address['city'] : ''))); ?>
		                		<span class="formNote">City</span>
		                		<?= showStates(isset($vendor) ? $vendor->Address['state'] : FALSE); ?>
		                		<span class="formNote">State</span>
		                		<?= form_input(array('class','required validate[required]','name'=>'zip','id'=>'zip','value'=>((isset($vendor)) ? $vendor->Address['zipcode'] : ''))); ?>
		                		<span class="formNote">Zip Code</span>
		                	</div>
		                	<div class="fix"></div>
		                </div>
		                <div class="rowElem noborder">
		                	<label>Phone</label>
		                	<div class="formRight">
			                	<?= form_input(array('class','required validate[required,custom[phone]]','name'=>'primary','id'=>'primary_phone','value'=>((isset($vendor)) ? $vendor->Phone['primary'] : ''))); ?>
			                	<span class="formNote">Primary Phone</span>
			                	<?= form_input(array('class','validate[custom[phone]]','name'=>'secondary','id'=>'secondary_phone','value'=>((isset($vendor)) ? $vendor->Phone['secondary'] : ''))); ?>
			                	<span class="formNote">Secondary PHone</span>
			                </div>
			                <div class="fix"></div>
		                </div>
		                <div class="rowElem noborder">
		                    <label>Vendor Notes</label>
		                    <div class="formRight">
		                        <?= form_textarea(array('rows'=>'8','cols'=>'','name'=>'Notes','class'=>'auto','value'=>((isset($vendor)) ? $vendor->Notes : ''))); ?>                      
		                    </div>
		                    <div class="fix"></div>
		                </div>
		                <div class="submitForm">
		                    <input type="submit" value="submit" class="redBtn" />
		                </div>
		                <div class="fix"></div>
		            </div>
		        </fieldset>
		        <?php if(isset($vendor)) { ?>
		        	<input type="hidden" name="vendor_id" value="<?= $vendor->ID; ?>" />
		        <?php } ?> 
		    <?php echo  form_close(); ?>        
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery('#usualValidate').validationEngine({promptPosition : "right", scroll: true});
	
	jQuery('#usualValidate').submit(function(e) {
		e.preventDefault();
	});

	jQuery("#addeditvendorsform").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Save: function() {
				//ChangePassword();
			}
		}
	});
</script>
