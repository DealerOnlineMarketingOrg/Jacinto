<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editMasterList" title="Client Assets">
        <div class="uiForm">
            <div class="widget masterlistPop" style="margin-top:5px;">
            	<div class="head"><h5 class="iCompanies"><?= $client->Dealership; ?></h5></div>
            	<?php echo form_open('/admin/masterlist/form',array('id'=>'editMasterlistForm','class'=>'validate mainForm formPop','style'=>'text-align:left;'));	?>
                    <fieldset>
                        <div class="rowElem noborder">
                        <table width="100%">
                        	<tr>
                            	<td style="width:33%;">DOC</td>
                                <td style="width:33%;">XSL</td>
                                <td style="width:33%;">CRM</td>
                            </tr>
                            <tr>
                            	<td><input type="text" value="http://" name="doc" id="Doc" /></td>
                                <td><input type="text" value="http://" name="xsl" id="xsl" /></td>
                                <td>
                                    <select class="chzn-select" name="crm" id="crm_name" style="margin:12px 0;width:50%;">
                                        <option value="">Choose Crazy Egg</option>
                                    </select>
								</td>
                            </tr>
                         </table>
                        </div>
                        <div class="fix"></div>
                    	<?php foreach($client->Websites as $website) { ?>
                            <div class="rowElem">
                                <h5 class="website"><a href="<?= $website->href;?>" target="_blank"><?= str_replace('http://','',$website->href); ?></a></h5>
                                <table width="100%">
                                    <tr>
                                        <td style="width:50%;">CMS</td>
                                        <td style="width:50%;">Crazy Egg</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select class="chzn-select" style="float:left;width:48%" name="cms" id="web_<?= $website->ID; ?>_cms">
                                                <option value="">Choose a CMS</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="chzn-select" style="float:left;width:48%" name="crazyEgg" id="web_<?= $website->ID; ?>_crazyegg">
                                                <option value="">Choose Crazy Egg</option>
                                            </select>
                                        </td>
                                    </tr>
                                 </table>
                                <div class="fix"></div>
                            </div>
                        <?php } ?>
                        <div class="submitForm">
                            <input type="hidden" name="client_id" value="<?= $client->ClientID; ?>" />
                            <input type="submit" value="Save" class="redBtn" />
                        </div>
                    </fieldset>
               	<?= form_close(); ?>
                <div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<style type="text/css">
.rowElem > label {padding:0;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery;
	
	$('#editMasterlistForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/masterlist/form',
			success:function(code) {
				alert(code);
			}
		});
	});
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		$('#editMasterList div.tab_container div.tab_content').hide();
		$('#editMasterList div.tab_container').find(content).css({'display':'block'});
	});
	
	$("#editMasterList").dialog({
		minWidth:750,
		height:500,
		autoOpen: true,
		modal: true,
	});
</script>
