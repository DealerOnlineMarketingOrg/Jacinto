<div id="wrapper">
    <div id="content_wrapper">
        <div id="main_content" class="border">
            <div id="demo">
                <div id="demoWrapper">
                <div class="uDialog">
                <div class="dialog-message popper" id="<?= (($page == 'edit') ? 'editContact' : 'addContact'); ?>" title="<?= (($page == 'edit') ? 'Edit' : 'Add'); ?> Contact">
    		    <div class="uiForm">
            	<div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
					<?php
                        $form = array(
                            'id' => 'addDpr',
                            'name' => 'addDpr',
                            'class' => 'mainForm valid',
							'style' => 'text-align:left'
                        );
                        
                        echo form_open('/dpr/form_processor/dpr/add',$form);
                    ?>
                        <div id="fieldWrapper">
                        <span id="step1" class="step">
	                        <div class="rowElem noborder">
                                <span style="white-space:nowrap">First step - Enter Lead Source</span><br /><br />
                                <label style="position:relative;float:left;white-space:nowrap"><span class="req">*</span> Source</label>
                                <select class="input_field_12em required" style="float:left" name="source" id="source">
                                    <?php echo $providers; ?>
                                </select>
                                <div class="fix"></div>
                                <div style="position:relative;float:left">
                                    <label>Month</label>
                                    <div class="fix"></div>
                                    <select id="month" name="month" class="input" placeholder="Select a month...">
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
                                    <select id="year" name="year" class="input" placeholder="Select a year...">
                                        <option value="2013">2013</option>
                                        <option value="2012">2012</option>
                                        <option value="2011">2011</option>
                                        <option value="2010">2010</option>
                                        <option value="2009">2009</option>
                                    </select>
                                </div>
                                <div class="fix"></div><br />
                                <label style="position:relative;float:left">Cost</label>
                                <input type="text" class="" style="width:10em !important;position:relative;float:left" id="cost" name="cost" placeholder="Monthly Cost" max-length="10" />
                                <input type="hidden" class="link" name="link1" id="link1" value="step2" />
							</div>
                        </span>
                        <span id="step2" class="step">
	                        <div class="rowElem noborder">
	                            <span style="white-space:nowrap">Step 2 - Chose Lead Source Metric</span><br /><br />
                                <label style="position:relative;float:left;white-space:nowrap"><span class="req">*</span> Metric</label>
                                <select class="input_field_12em required" style="float:left" name="metric" id="metric">
                                    <?php echo $services; ?>
                                </select>
							</div>
                        </span>
                        </div>
                        <div class="fix"></div><br />
                        <div id="demoNavigation">
                            <input type="reset" class="navigation_button" value="Reset Form" />
                            <input type="submit" class="navigation_button" value="Submit" />
                        </div>
                    </form>
                </div>
                </div>
                </div>
                </div>
                </div>
            </div>
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
	
	$("#addDpr").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		formOptions :{
			success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("You are now registered!").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#data").html("data sent to the server: " + $.param(data));},
			dataType: 'json',
			resetForm: true
		}	
	});
		
	$('.formPop').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/dpr/form_processor/dpr/add',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= ($page == 'edit') ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						contactListTable();
					});
				}else {
					msg = '<?= ($page == 'edit') ? 'There was a problem with editing the DPR. Please try again.':'There was a problem adding the DPR. Please try again.'; ?>';
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
		$('#viewDpr div.tab_container div.tab_content').hide();
		$('#viewDpr div.tab_container').find(content).css({'display':'block'});
	});
	
	<?php if($page == 'edit') { ?>
	$("#dprEditPop").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
	});
	<?php }else { ?>
	$("#dprAddPop").dialog({
		width:450,
		height:300,
		autoOpen: true,
		modal: true,
		title: "Add Source Metrics"
	});
	<?php } ?>
</script>