 <div style="padding:5px;" class="uDialog" id="AddEditVendors">
    <div id="addeditvendorsform" class="dialog-message" title="<?= isset($vendor) ? 'Edit Vendor' : 'Add Vendor'; ?>">
        <div class="uiForm" style="text-align:left;">
		    <!-- Form begins -->
		    <?= form_open('/admin/vendors/form_processor/vendors/' . ((isset($vendor)) ? 'edit' : 'add'),array('name'=>'add','class'=>'mainForm','id'=>'usualValidate')); ?>
	            <div class="widget" style="margin-top:-10px;">		                
	                <div class="rowElem noborder" style="">
	                 	<span class="formNote required"><span style="color:red;">*</span> Name</span>
	                    <?= form_input(array('style'=>'margin:0 0 5px 0;','class'=>'required validate[required]','name' => 'name','id'=>'name', 'value'=>((isset($vendor) ? $vendor->Name : '')))); ?>
	                	<span class="formNote">* Street Address</span>
                		<?= form_input(array('style'=>'margin:0 0 5px 0;','class'=>'required validate[required]','name'=>'street','id'=>'street','value'=>((isset($vendor)) ? $vendor->Address['street'] : ''))); ?>
                		<span class="formNote">City</span>
                		<?= form_input(array('style'=>'margin:0 0 5px 0;','class'=>'required validate[required]','name'=>'city','id'=>'city','value'=>((isset($vendor)) ? $vendor->Address['city'] : ''))); ?>
                		<span class="formNote">State</span>
                		<?= popUpStates(isset($vendor) ? $vendor->Address['state'] : FALSE); ?>
                		<span class="formNote">Zip</span>
                		<?= form_input(array('style'=>'margin:0 0 5px 0;','class'=>'required validate[required]','name'=>'zip','id'=>'zip','value'=>((isset($vendor)) ? $vendor->Address['zipcode'] : ''))); ?>
	                	<span class="formNote"><span style="color:red;">*</span> Primary Phone</span>
	                	<?= form_input(array('style'=>'margin:0 0 5px 0;','class'=>'required validate[required,custom[phone]]','name'=>'primary','id'=>'primary_phone','value'=>((isset($vendor)) ? $vendor->Phone['primary'] : ''))); ?>
	                	<span class="formNote">Secondary Phone</span>
	                	<?= form_input(array('style'=>'margin:0 0 5px 0;','class'=>'validate[custom[phone]]','name'=>'secondary','id'=>'secondary_phone','value'=>((isset($vendor)) ? $vendor->Phone['secondary'] : ''))); ?>
	                    <span class="formNote">Notes</span>
	                    <?= form_textarea(array('style'=>'margin:0 0 5px 0;','rows'=>'8','cols'=>'','name'=>'Notes','class'=>'auto','value'=>((isset($vendor)) ? $vendor->Notes : ''))); ?>                      
	                </div>
	                <div class="submitForm">
	                    <input type="submit" value="submit" class="redBtn" />
	                </div>
	                <div class="fix"></div>
	            </div>
		        <?php if(isset($vendor)) { ?>
		        	<input type="hidden" name="vendor_id" value="<?= $vendor->ID; ?>" />
		        <?php } ?> 
		    <?= form_close(); ?>        
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(".chzn-select").chosen(); 
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
