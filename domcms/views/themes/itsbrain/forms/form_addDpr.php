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
                <?php if ($this->user['DropdownDefault']->SelectedClient == 0) { ?>
					<div class="head"><h5 class="iList">Add DPR Lead: <span style="color:red">No dealer is selected. Please select a dealer to input DPR leads.</span></h5></div>
                
				<?php } else { ?>
                    <div class="head"><h5 class="iList">Add DPR Lead</h5></div>
                    <div class="rowElem noborder">
                        <label><span class="req">*</span>Source</label>
                        <div class="formRight noSearch">
                            <select id="source" name="source" class="input msSelect chzn-select required validate[required]" placeholder="Select A Lead Source...">
                                <option value="">Select A Lead Source</option>
                                <?php echo $providers; ?>
                                <option value="AddCustom">Other</option>
                            </select><br />
                            <div>
                                <label data-binding="source" style="display:none">New provider:</label>
                                <input id="customSource" name="customSource" class="input" data-binding="source" placeholder="Enter new source" type="text" style="width:200px;display:none" />
                            </div><br />
                            <div>
                                <label data-binding="source" style="display:none">New provider URL:</label>
                                <input id="customSourceURL" name="customSourceURL" class="input" data-binding="source" placeholder="Enter new source URL" type="text" style="width:200px;display:none" />
                            </div>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="rowElem noborder">
                        <label>Cost</label>
                        <div class="formRight">
                            <input type="text" class="input required validate[required]" style="width:150px" id="cost" name="cost" placeholder="The monthly cost for leads" max-length="10" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="rowElem noborder">
                        <label>Agency Description</label>
                        <div class="formRight noSearch">
                            <select id="agencies" name="agencies" class="input msSelect chzn-select required validate[required]" placeholder="Select A Lead Type...">
                                <option value="">Select A Lead Type</option>
                                <?php echo $agencies; ?>
                                <option value="AddCustom">Other</option>
                            </select><br />
                            <div>
                                <label data-binding="agencies" style="display:none">New agency:</label>
                                <input id="customAgency" name="customAgency" class="input" data-binding="agencies" placeholder="Enter new agency" type="text" style="width:200px;display:none" />
                            </div>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="rowElem noborder">                    
                        <label>Month</label>
                        <div class="formRight noSearch">
                        	<select id="month" name="month" class="input chzn-select required validate[required]" placeholder="Select a month...">
                            	<option value="">Select a Month</option>
                                <option value="1">January</option>
                                <option value="2">Febuary</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="rowElem noborder">
                        <label>Year</label>
                        <div class="formRight noSearch">
                        	<select id="year" name="year" class="input chzn-select required validate[required]" placeholder="Select a year...">
                            	<option value="">Select a Year</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>                                
                            </select>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="rowElem noborder">
                        <label>Total</label>
                        <div class="formRight">
                            <input type="text" class="input required validate[required]" style="width:150px" id="total" name="total" placeholder="The total lead count" max-length="10" />
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="submitForm">
                        <input type="reset" value="Reset" class="basicBtn" />
                        <input type="submit" value="submit" class="redBtn" />
                    </div>
				<?php } ?>
                <div class="fix"></div>
            </div>
		<script type="text/javascript">
			//$(document).ready(function() {
            //jQuery(window).load(function() {
				function validateBlank()
				{
					if (jQuery('input#source').val() == '' ||
                        (jQuery('input#source').val() == 'AddCustom' &&
						 (jQuery('input#customSource').val() == '' || jQuery('input#customSourceURL').val() == '')) ||
                        jQuery('input#agency').val() == '' ||
                        (jQuery('input#agency').val() == 'AddCustom' &&
						 jQuery('input#customAgency').val() == '') ||
                        jQuery('input#month').val() == '' ||
                        jQuery('input#year').val() == '' ||
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
				
				jQuery('.input').change(function() {
					if (this.name == 'source' || this.name == 'agencies' || this.name == 'month' || this.name == 'year') {
						if (jQuery("#source").val() != '' && jQuery("#agencies").val() != '' && jQuery("#month").val() != '' && jQuery("#year").val() != '') {
							// Check values of source and agency and see if there's a cost associated with them.
							$.ajax({type:"POST",
									url:"<?= base_url(); ?>dpr/ajaxGetCost",
									data:{source:jQuery("#source").val(),
										  agency:jQuery("#agencies").val(),
										  month:jQuery("#month").val(),
 										  year:jQuery("#year").val()
										  },
									success:(function(data) {
										cost = data;
										jQuery("#cost").val(cost);
									})
							});
						}
					}
				});
				
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