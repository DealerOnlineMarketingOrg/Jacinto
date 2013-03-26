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
            <div class="widget first" style="padding:5px">
                <?php if ($this->user['DropdownDefault']->SelectedClient == 0) { ?>
					<div class="head"><h5 class="iList">Add DPR Lead: <span style="color:red">No dealer is selected. Please select a dealer to input DPR leads.</span></h5></div>
                
				<?php } else { ?>
                    <div class="head"><h5 class="iList">Add DPR Lead</h5></div>
                    <div class="noborder noSearch" style="position:relative;float:left;margin:5px">
                        <label style="position:relative;float:left"><span class="req">*</span>Provider</label>
                        <select id="providers" name="providers" class="input msSelect chzn-select required validate[required]" style="position:relative;float:left" placeholder="Select A Lead Provider...">
                            <option value="">Select A Lead Provider</option>
                            <?php echo $providers; ?>
                            <option value="AddCustom">Other</option>
                        </select>
                        <div class="fix"></div>
                        <div style="position:relative;float:left">
	                        <label data-binding="providers" style="display:none;position:relative;float:left">New provider:</label>
	                        <div class="fix"></div>
	                        <label data-binding="providers" style="display:none;position:relative;float:left">New provider URL:</label>
                        </div>
                        <div style="position:relative;float:left">
	                        <input id="customProvider" name="customProvider" class="input" data-binding="providers" placeholder="Enter new provider" type="text" style="width:200px;position:relative;float:left;display:none" />
    	                    <div class="fix"></div>
        	                <input id="customProviderURL" name="customProviderURL" class="input" data-binding="providers" placeholder="Enter new provider URL" type="text" style="width:200px;display:none;position:relative;float:left" />
						</div>
					</div>
                    <div class="noborder noSearch" style="position:relative;float:left;margin:5px">
                    	<div style="position:relative;float:left">
                            <label>Month</label>
	                        <div class="fix"></div>
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
                    	<div style="position:relative;float:left;margin-left:5px">                        
                            <label>Year</label>
							<div class="fix"></div>
                            <select id="year" name="year" class="input chzn-select required validate[required]" placeholder="Select a year...">
                                <option value="">Select a Year</option>
                                <option value="2013">2013</option>
                                <option value="2012">2012</option>
                                <option value="2011">2011</option>
                                <option value="2010">2010</option>
                                <option value="2009">2009</option>
                            </select>
                        </div>
                        <div class="fix"></div>
                        <label style="position:relative;float:left">Cost</label>
						<input type="text" class="input required validate[required]" style="width:150px;position:relative;float:left" id="cost" name="cost" placeholder="The monthly cost for leads" max-length="10" />
					</div>
                    <div class="fix"></div>
                    <!-- We were using noSearch for the class for the select that isn't working below. Needed for chzn-select. -->
                    <div id="sourceBlock" class="" style="position:relative;border:1px dotted #d5d5d5;margin:5px;padding:5px">
                    	<div style="position:relative;float:left;margin:5px">
                            <label style="position:relative;float:left">Source</label>
                            <!-- This isn't working at the moment. Get it working eventually. -->
                            <!-- <select id="sources" name="sources" class="input msSelect chzn-select required validate[required]" style="position:relative;float:left" placeholder="Select A Lead Source..."> -->
                            <select id="sources" name="sources" style="position:relative;float:left" placeholder="Select A Lead Source...">
                                <option value="">Select A Lead Source</option>
                                    <?php echo $services; ?>
                                <option value="AddCustom">Other</option>
                            </select>
		                    <div class="fix"></div>
                            <label data-binding="sources" style="display:none;position:relative;float:left">New source:</label>
                            <input id="customSource" name="customSource" class="input" data-binding="sources" placeholder="Enter new source" type="text" style="width:200px;display:none;position:relative;float:left" />
                        </div>
                        <div style="position:relative;float:left;margin:5px">
							<label style="position:relative;float:left">Total</label>
							<input type="text" class="input required validate[required]" style="width:150px;position:relative;float:left" id="total" name="total" placeholder="The total lead count" max-length="8" />
                      	</div>
		                <div class="fix"></div>
                    </div>
                    <div class="fix"></div>
                    <div id="sourceCopy" style="margin:5px"><input type="button" id="addSource" value="+ Source" class="greenBtn" /></div>
                    <input id="sourceCount" name="sourceCount" type="hidden" value="1" />
				<?php } ?>
                <div class="fix"></div>
            </div>
            <div class="submitForm">
                <input type="reset" id="reset" value="Reset" class="basicBtn" />
                <input type="submit" value="submit" class="redBtn" />
            </div>
		<script type="text/javascript">
			//$(document).ready(function() {
				var sourceNum = 0;
            	jQuery(window).load(function() {
					// Don't turn off template sourceBlock before document is loaded, otherwise
					//  drop down fields may not get populated.
					//jQuery('#sourceBlock').css('display','none');
					newSource();
				});
				
				jQuery('#addSource').click(function(e) {
					newSource();
				});
				
				// Clones with outer element (outer html).
				// To get the html, use .html().
				// Use of the clone will include the temp wrapper <div>.
				function cloneWithOuter(selector, withDataAndEvents = false, deepWithDataAndEvents = false) {
					return $('<div>').append($(selector).clone(withDataAndEvents, deepWithDataAndEvents));
				}
				
				// Salts a full html tree segment by appending a salt value to the attributes
				//   in attrList.
				// Can be used with 'id' and 'name' to create distinct cloned elements.
				// Returns the salted segment.
				function saltSegment(segment, attrList, salt) {
					var attrVal = '';
					var newAttr = '';
					jQuery('*', segment).each(function(e) {
						elementThis = $(this);
						$.each(attrList, function(index, attrName) {
							attrVal = elementThis.attr(attrName);
							// Check to make sure attr exists.
							// Attr can be undefined or false, depending on browser.
							if (typeof(attrVal) !== 'undefined' && attrVal !== false) {
								newAttr = attrVal + salt;
								elementThis.attr(attrName, newAttr);
							}
						});
					});
					return segment;
				}
				
				function newSource() {
					// Increment source counters.
					sourceNum++;
					jQuery('#sourceCount').val(sourceNum);
					
					var newBlock = cloneWithOuter('#sourceBlock',true, true);
					newBlock = saltSegment(newBlock, [ 'id', 'name', 'data-binding' ], '_' + sourceNum);
					// Turn new source visible.
					jQuery("#sourceBlock_"+sourceNum, newBlock).css('display','');
					// We need to re-attach drop-down functionality to the new source.
					// This function is loaded for drop-down boxes on DOM-load in custom.js
					//jQuery("#sources_"+sourceNum).chosen();
					// Use .before for now. Replace with .replaceWith and .after(buttonCode) due to:
					// Replace out add button/placeholder. If we prepend before it instead,
					//   it could cause layout problems with some layouts or browsers.
					jQuery('#sourceCopy').before(newBlock.html());
					// Add a new add button/placeholder.
					//jQuery(sourceBlock).after('<div id="sourceCopy"><input type="button" id="addSource" value="addSource" class="basicBtn" style="clear:all" /></div>');
					// Replacing, then remaking, the button unbinds the click event. Rebind.
					//jQuery('#addSource').bind('click', function(e) {newSource();});
				}
				
				function validateBlank()
				{
					if (jQuery('input#providers').val() == '' ||
                         (jQuery('input#providers').val() == 'AddCustom' &&
						   (jQuery('input#customProvider').val() == '' ||
						    jQuery('input#customProviderURL').val() == '')) ||
                        jQuery('input#sources').val() == '' ||
                         (jQuery('input#sources').val() == 'AddCustom' &&
						  jQuery('input#customSource').val() == '') ||
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
					if ($(this).attr('name') == 'providers' ||
						$(this).attr('name') == 'month' ||
						$(this).attr('name') == 'year' ||
						($(this).attr('name')).indexOf('sources') == 0) {
						if (jQuery("#providers").val() != '' &&
							jQuery("#sources").val() != '' &&
							jQuery("#month").val() != '' &&
							jQuery("#year").val() != '') {
							// Check values of provider and source and see if there's a cost associated with them.
							$.ajax({type:"POST",
									url:"<?= base_url(); ?>dpr/ajaxGetCost",
									data:{source:jQuery("#providers").val(),
										  source:jQuery("#sources").val(),
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
				
				jQuery('#reset').click(function(e) {
					jQuery('#providers').selectedIndex = 0;
					jQuery('#cost').val("");
					jQuery('#sources').selectedIndex = 0;
					jQuery('#month').selectedIndex = 0;
					jQuery('#year').selectedIndex = 0;
					jQuery('#total').val("");					
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