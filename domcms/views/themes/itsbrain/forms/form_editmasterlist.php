<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editMasterList" title="Client Assets">
        <div class="uiForm">
            <div class="widget masterlistPop" style="margin-top:5px;">
            	<div class="head"><h5 class="iCompanies"><?= $client->Dealership; ?></h5></div>
            	<?php echo form_open('/admin/masterlist/form',array('id'=>'editMasterlistForm','class'=>'validate mainForm formPop','style'=>'text-align:left;'));	?>
                	<? //print_object($client); ?>
                    <fieldset>
                        <div class="rowElem noborder noSearch">
                        <table width="100%">
                        	<tr>
                            	<td style="width:33%;">DOC</td>
                                <td style="width:33%;">XSL</td>
                                <td style="width:33%;">CRM Name and Link</td>
                            </tr>
                            <tr>
                            	<td><input class="enableCopy" type="text" value="<?= (isset($client->Docs[0]->href)) ? $client->Docs[0]->href : ''; ?>" name="doc" id="Doc" /><p class="formNote hidden">Ctrl/Command C to copy</p></td>
                                <td><input class="enableCopy" type="text" value="<?= (isset($client->Spreadsheets[0]->href)) ? $client->Spreadsheets[0]->href : ''; ?>" name="xsl" id="xsl" /><p class="formNote hidden">Ctrl/Command C to copy</p></td>
                                <td>
                                    <select class="chzn-select" name="crm" id="crm_name" style="margin:12px 0;width:50%;">
                                        <option value="">Choose A CRM</option>
                                        <?php foreach($vendorOptions as $option) { ?>
                                        	<option <?= ((isset($client->CRM[0]->title)) ? (($client->CRM[0]->title == $option->Name) ? 'selected="selected"':'') : ''); ?> value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <input id="crm_link" style="margin-top:5px;" class="enableCopy" type="text" value="<?= (isset($client->CRM[0]->href)) ? $client->CRM[0]->href : ''; ?>" name="crm_link" />
                                    <p class="formNote hidden">Ctrl/Command C to copy</p>
                                    <input type="hidden" name="assets_id" value="<?= ((isset($client->CRM[0]->AssetsID)) ? $client->CRM[0]->AssetsID : '') ; ?>" />
								</td>
                            </tr>
                         </table>
                        </div>
                        <div class="fix"></div>
                        <?php if(isset($client->Websites)) { ?>
							<?php $i = 0; foreach($client->Websites as $website) { ?>
                                <div class="rowElem noSearch">
                                    
                                    <table style="margin:0 auto;width:99%" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td colspan="2"><h5 class="website"><a href="<?= $website->href;?>" target="_blank"><?= str_replace('http://','',$website->href); ?></a></h5></td>
                                        </tr>
                                        <tr>
                                            <td style="width:50%;">CMS</td>
                                            <td style="width:50%;">Crazy Egg</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="chzn-select" style="float:left;width:38%" name="cms[<?= $client->CMS[$i]->MID; ?>]" id="web_<?= $website->ID; ?>_cms">
                                                    <option value="">Choose a CMS</option>
                                                    <?php foreach($vendorOptions as $option) { ?>
                                                        <option <?= ((isset($client->CMS[$i]->title)) ? (($client->CMS[$i]->title == $option->Name) ? 'selected="selected"' : '') : ''); ?> value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="master_id[<?= $client->CMS[$i]->MID; ?>]" value="<?= $client->CMS[$i]->MID; ?>" />
                                                <input type="hidden" name="website_id[<?= $website->ID; ?>]" value="<?= $website->ID; ?>" />
                                            </td>
                                            <td>
                                                <select class="chzn-select" style="float:left;width:38%" name="crazyegg[<?= $website->ID; ?>]" id="web_<?= $website->ID; ?>_crazyegg">
                                                    <option value="">Choose Crazy Egg</option>
                                                    <?php foreach($crazyEggOptions as $option) { ?>
                                                        <option <?= (($option->ID == $website->CrazyEggLabelID) ? 'selected="selected"' : ''); ?> value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                     </table>
                                    <div class="fix"></div>
                                </div>
                            <?php $i++;} ?>
                        <?php }else { ?>
                        	<div class="rowElem">
                            	<p>No websites added for this client.</p>
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
	
	var crm_name_width = $('#crm_name_chzn').width() + 10;
	$('div#editMasterList .mainForm input#crm_link').css({'width':crm_name_width + 'px'});
	
	$('div.rowElem:odd').addClass('odd');
	$('div.rowElem:last').addClass('last');
	
	$('input.enableCopy').click(function() {
		$(this).select();
		$(this).next().slideDown('fast');
	});
	
	$('input.enableCopy').blur(function() {
		$(this).next().slideUp('fast');
	});
	
	$('#editMasterlistForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/masterlist/form',
			success:function(code) {
				if(code == '1') {
					jAlert('The clients data was successfully updated.','Success',function() {
						refreshTable();
					});	
				}else {
					alert(code);	
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
