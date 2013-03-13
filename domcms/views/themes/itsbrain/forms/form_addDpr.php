<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Reports</h5></div>
    <?php notifyError(); ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
        $form = array(
            'name' => 'addDpr',
            'class' => 'mainForm valid'
        );
		
        echo form_open('/dpr/form_processor/dpr/add',$form);
    ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Add DPR Report
                    </h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Provider</label>
                    <div class="formRight noSearch">
                        <select id="providers" name="providers" class="msSelect chzn-select required validate[required]" placeholder="Select A Lead Provider...">
                            <option value="">Select A Lead Provider</option>
                            <?php echo $providers; ?>
                            <option value="AddCustom">Other</option>
                        </select><br />
                        <div>
                        <label data-binding="providers" style="display:none">New provider:</label>
                        <input id="customProvider" name="customProvider" data-binding="providers" placeholder="Enter new provider" type="text" style="width:200px;display:none" />
                        </div><br />
                        <div>
                        <label data-binding="providers" style="display:none">New provider URL:</label>
                        <input id="customProviderURL" name="customProviderURL" data-binding="providers" placeholder="Enter new provider URL" type="text" style="width:200px;display:none" />
                        </div>
				  </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Agency Description</label>
                    <div class="formRight noSearch">
                        <select id="agencies" name="agencies" class="msSelect chzn-select required validate[required]" placeholder="Select A Lead Type...">
                            <option value="">Select A Lead Type</option>
                            <?php echo $agencies; ?>
                            <option value="AddCustom">Other</option>
                        </select><br />
                        <div>
                        <label data-binding="agencies" style="display:none">New agency:</label>
                        <input id="customAgency" name="customAgency" data-binding="agencies" placeholder="Enter new agency" type="text" style="width:200px;display:none" />
                        </div>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Date</label>
                    <div class="formRight onlyAlpha">
                    	<input type="text" class="required validate[required] datepicker" id="date" name="date" placeholder="" max-length="12" />
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Total</label>
                    <div class="formRight">
                    	<input type="text" class="required validate[required]" id="total" name="total" placeholder="The total lead count" max-length="10" />
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="submitForm">
                	<input type="reset" value="Reset" class="basicBtn" />
                    <input type="submit" value="submit" class="redBtn" />
                </div>
                <div class="fix"></div>
            </div>
		<script type="text/javascript">
			//$(document).ready(function() {
            //jQuery(window).load(function() {
				function validateBlank()
				{
					if (jQuery('input#provider').val() == '' ||
                        (jQuery('input#provider').val() == 'AddCustom' &&
						 (jQuery('input#customProvider').val() == '' || jQuery('input#customProviderURL').val() == '')) ||
                        jQuery('input#agency').val() == '' ||
                        (jQuery('input#agency').val() == 'AddCustom' &&
						 jQuery('input#customAgency').val() == '') ||
                        jQuery('input#date').val() == '' ||
                        jQuery('input#total').val() == '')
						return true;
					else
						return false;
				}
				
				function validateData()
				{
					if (jQuery('input#total').val().match(/^[0-9]+|([0-9]+)?\.[0-9]+$/))
						return false;
					else
						return true;
				}
				
                jQuery('form.valid').submit(function(e) {
                    if (validateBlank()) {
						alert('All fields are required. Please fill in all fields.');
						return false;
                    } else if (validateData()) {
						alert('Total field must be numeric.');
						return false;
                    } else {
                    }
                });
            //});
        </script>
        </fieldset>
    <?php echo  form_close(); ?>
    <script type="text/javascript">
		$( ".datepicker" ).datepicker({ // onClick date picker
			defaultDate: +7,
			autoSize: false,
			appendText: '(yyyy-mm-dd)',
			dateFormat: 'yy-mm-dd',
		});
	</script>
</div>
<div class="fix"></div>