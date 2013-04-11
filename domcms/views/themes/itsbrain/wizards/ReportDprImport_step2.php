<div id="wizardPop" class="uDialog">
    <div class="dialog-message popper" id="import" title="Import DPR Lead metric Metrics">
        <div class="uiForm">
            <div class="widget" style="border-top-width:1px !important;margin-top:10px;padding-top:0;margin-bottom:10px;">
                <fieldset id="wizardPopForm" name="wizardPopForm">
                <div>
                    <span style="white-space:nowrap;font-weight:bold">Second step - Enter <?= $source->Name; ?> Metrics</span><br /><br />
                    
					<?php
                    $metricBlock = '
                    <div class="fix"></div>
                    <div id="metricBlock_%c%" class="noSearch" style="position:relative;float:left;width=1px;border:0px dotted #d5d5d5;margin:5px;margin-bottom:0;padding:0px">
                        <div style="position:relative;float:left;margin:5px">
                            <label style="position:relative;float:left">Metric</label>
                            <select id="metrics_%c%" name="metrics_%c%" class="msSelect chzn-select" style="position:relative;float:left;" placeholder="Select A Metric...">
                            	' . $metrics . '
                            </select>
                            <div class="fix"></div>
                        </div>
                        <div id="metricAdd_%c%" style="float:left;margin:5px">
							<input type="button" id="addmetric" value="+" class="greenBtn" style="font-weight:bold;font-size:120%;padding-top:2px;padding-bottom:2px;padding-left:10px;padding-right:10px" />
						</div>
                        <div id="metricRemove_%c%" style="float:left;margin:5px">
							<input type="button" id="removemetric" value="-" class="redBtn" style="font-weight:bold;font-size:120%;padding-top:2px;padding-bottom:2px;padding-left:10px;padding-right:10px" />
							</div>
                        <div class="fix"></div>
                    </div>
                    ';
                    
                    for ($i = 0; $i < 10; $i++)
                        echo str_replace('%c%', $i, $metricBlock);
                    ?>
                    <input id="metricCount" name="metricCount" type="hidden" value="0" />
                </div>
                
                <div class="fix"></div>
                </fieldset>
            </div>
			<div style="float:right">
                <input id="cancel" type="button" class="greyishBtn" value="Cancel" />
                <input id="next" type="button" class="redBtn" value="Next" />
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	function loadInit() {
		// Don't turn off template metricBlock before document is loaded, otherwise
		//  drop down fields may not get populated.
		for (i = 0; i < 10; i++)
			jQuery('#metricBlock_' + i).css('display','none');

		// Set first metric to visible by default.
		next = parseInt(jQuery("#metricCount").val())+1;
		jQuery("#metricCount").val(next);
		jQuery("#metricBlock_0").css('display','');
		// Set first remove button to invisible.
		jQuery("#metricRemove_0").css('display','none');
	};
	
	$('[id^="addmetric"]').click(function(e) {
		// Set next metric to visible.
		next = parseInt(jQuery("#metricCount").val())+1;
		jQuery("#metricCount").val(next);
		// Make last metric buttons invisible.
		jQuery("#metricAdd_" + (next-2)).css('display','none');
		jQuery("#metricRemove_" + (next-2)).css('display','none');
		// Make next metric block visible.
		jQuery("#metricBlock_" + (next-1)).css('display','');
		
		// If we're at the last metric, turn off addmetric button.
		if (next == 10)
			jQuery("#metricAdd_9").css('display','none');
	});
	
	$('[id^="removemetric"]').click(function(e) {
		// Set last metric to invisible.
		last = parseInt(jQuery("#metricCount").val());
		jQuery("#metricCount").val(last-1);
		// Make last metric buttons visible.
		jQuery("#metricAdd_" + (last-2)).css('display','');
		jQuery("#metricRemove_" + (last-2)).css('display','');
		// Make next metric block visible.
		jQuery("#metricBlock_" + (last-1)).css('display','none');
		
		// If we're at the first metric, turn off removemetric button.
		if (last-2 == 0)
			jQuery("#metricRemove_0").css('display','none');
	});
	
	$(".chzn-select").chosen();
	
	$("#next").click(function() {
		var persistent = {};
		<?php if (isset($persistent)) { ?>
			<?php foreach ($persistent as $key => $val) ?>
				<?php print_object($key); echo '|'; print_object($val); ?>
				persistent['<?= $key ?>'] = '<?= $val ?>';
		<?php } ?>
		var returnData = JSON.stringify({state:'success', data:$("fieldset#wizardPopForm").serialize() + '&' + $.param(persistent)});
		$("#dprImportPop").attr("return",returnData);
		$("#wizardPop").dialog('destroy').remove();
	});
	
	$("#cancel").click(function() {
		var persistent = {};
		<?php if (isset($persistent)) { ?>
			<?php foreach ($persistent as $key => $val) ?>
				persistent['<?= $key ?>'] = '<?= $val ?>';
		<?php } ?>
		var returnData = JSON.stringify({state:'error', data:$.param(persistent)});
		$("#dprImportPop").attr("return",returnData);
		$("#wizardPop").dialog('destroy').remove();
	});
	
	$("#wizardPop").dialog({
		width:450,
		height:300,
		autoOpen: true,
		modal: true,
		title: "Import DPR Lead metric Metrics"
	});
</script>