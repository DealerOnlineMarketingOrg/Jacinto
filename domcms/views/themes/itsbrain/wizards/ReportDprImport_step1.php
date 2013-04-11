<div id="wizardPop" class="uDialog">
    <div class="dialog-message popper" id="import" title="Import DPR Lead Source Metrics">
        <div class="uiForm">
            <div class="widget" style="border-top-width:1px !important;margin-top:10px;padding-top:0;margin-bottom:10px;">
                <fieldset id="wizardPopForm" name="wizardPopForm">
                <div>
                    <span style="white-space:nowrap;font-weight:bold">First step - Enter Lead Source</span><br /><br />
                    <label style="position:relative;float:left;white-space:nowrap"><span class="req">*</span> Source</label>
                    <select style="position:relative;float:left;margin-left:10px" name="source" id="source">
                        <?php echo $sources; ?>
                    </select>
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