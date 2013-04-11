<div id="wizardPop" class="uDialog">
    <div class="dialog-message popper" id="import" title="Import DPR Lead metric Metrics">
        <div class="uiForm">
            <div class="widget" style="border-top-width:1px !important;margin-top:10px;padding-top:0;margin-bottom:10px;">
            <?php
       			$startDate = '1/1/2000';
                $endDate = '12/1/'.dateToYear(date('m/j/Y'));
                $lowerStart = '1/1/2000';
                $upperStart = $endDate;
                $lowerEnd = $startDate;
                $upperEnd = '12/1/' . date('Y');
            ?>
                <fieldset id="wizardPopForm" name="wizardPopForm">
                <div>
                    <span style="white-space:nowrap;font-weight:bold">Third step - Enter Date Range</span><br /><br />
                    
                    <div style="float:left">
                        <label style="margin-right:0">Starting Date:</label>
                        <div class="fix"></div>
                        <?php dateButtons($lowerStart,$upperStart,$startDate,'metricsStart'); ?>
                    </div>
                    <div class="fix">
                        <label style="margin-right:0">Ending Date:</label>
                        <div class="fix"></div>
                        <?php dateButtons($lowerEnd,$upperEnd,$endDate,'metricsEnd'); ?>
                    </div>
                    <div class="fix"></div>
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
	
	function loadInit() {};
	
	jQuery('[id^="addmetric"]').click(function(e) {
		// Set next metric to visible.
		next = parseInt(jQuery("#metricCount").val())+1;
		jQuery("#metricCount").val(next);
		// Make last add-metric button invisible.
		jQuery("#metricCopy_" + (next-2)).css('display','none');
		jQuery("#metricBlock_" + (next-1)).css('display','');
		
		// If we're at the last metric, turn off addmetric button.
		if (next == 10)
			jQuery("#metricCopy_9").css('display','none');

	});
		
	$(".chzn-select").chosen();
	
	$("#next").click(function() {
		var persistent = "<?= (isset($persistent)) ? http_build_query($persistent) : ''; ?>";
		var returnData = JSON.stringify({state:"success", data:$("fieldset#wizardPopForm").serialize() + "&" + persistent});
		$("#dprImportPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#cancel").click(function() {
		var persistent = "<?= (isset($persistent)) ? http_build_query($persistent) : ''; ?>";
		var returnData = JSON.stringify({state:"error", data:persistent});
		$("#dprImportPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#wizardPop").dialog({
		width:450,
		height:300,
		autoOpen: true,
		modal: true,
		title: "Import DPR Lead Source Metrics"
	});
</script>