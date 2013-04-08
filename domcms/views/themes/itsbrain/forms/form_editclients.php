<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editClient" title="<?= ($client) ? 'Edit ' . $client->Name : 'Add New Client'; ?>">
        <div class="uiForm">
			<style type="text/css">
				#editClient label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editClient .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Client Details</a></li>
            		<?php if(isset($websites)) { ?>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <?php } ?>
                    <?php if(isset($contacts)) { ?>
            		<li><a href="javascript:void(0);" rel="contacts">Contacts</a></li>
                    <?php } ?>
            		<li><a href="#users">Users</a></li>
            		<!-- <li><a href="#vendors">Vendors</a></li> -->
            	</ul>
            	<div class="tab_container">
            		<div id="clientInfo" class="tab_content">
		            	<?php
					        $form = array(
					            'name' => (($client) ? 'editClient' : 'addClient'),
					            'id' => 'valid',
					            'class' => 'mainForm ' . (($client) ? 'editClient' : 'addClient'),
					            'style'=>'text-align:left !important;'
					            
					        );
							
			        		echo (!isset($client)) ? form_open('/admin/clients/form_processor/clients/add',$form) : form_open('/admin/clients/form_processor/clients/edit',$form);
			    		?>
        				<!-- Input text fields -->
        				<fieldset>
                        	<?php if(isset($client->Tag)) { ?>
                        	<div class="rowElem noborder">
                            	<label><span class="req">*</span> Tag</label>
                                <div class="formRight noSearch">
                                	<div style="width:25px;border:1px solid #d5d5d5;margin-right:5px;float:left;margin-top:12px;">
                                		<div id="tagThumb" class="<?= $client->ClassName; ?>" style="float:left;">&nbsp;</div>
                                    </div>
                                    <select id="tagChanger" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="1">
			                            <option value=""></option>
			                            <?php foreach($tags as $tag) : ?>
			                            	<option rel="<?= $tag->ClassName; ?>" <?= ($client) ? (($tag->ID == $client->Tag) ? 'selected="selected"' : '') : ''; ?> value="<?= $tag->ID; ?>"><?= $tag->Name; ?></option>
			                            <?php endforeach; ?>
			                        </select>
                                </div>
                            </div>
                            <?php } ?>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Client Code</label>
			                    <div class="formRight">
			                        <?= form_input(array('maxlength'=>'4','class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>($client) ? $client->Code : '')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span>Client Name</label>
			                    <div class="formRight">
                                	<?= form_input(array('class'=>'required validate[required]','name'=>'ClientName','id'=>'name','value'=>($client) ? $client->Name : '')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight">
                                	<?= form_input(array('name'=>'street','id'=>'address','value'=> ((isset($client->Address['street'])) ? $client->Address['street'] : ''))); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>City</label>
			                    <div class="formRight">
                                	<?= form_input(array('name'=>'city','id'=>'city','value' => ((isset($client->Address['city'])) ? $client->Address['city'] : ''))); ?>			
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>State</label>
			                    <div class="formRight searchDrop noSearch" style="text-align:left;">
                                	<?php echo showStates(((isset($client->Address['state'])) ? $client->Address['state'] : false));?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Zip Code</label>
			                    <div class="formRight">
			                        <?= form_input(array('maxlength'=>'6','name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : ''))); ?>
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Phone</label>
			                    <div class="formRight">
			                        <?= form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value' => ((isset($client->Phone['main'])) ? $client->Phone['main'] : ''))); ?>
			                        <span class="formNote">(999) 999-9999</span>
			                    </div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
                            	<label>Member Of</label>
                                <div class="formRight noSearch">
                                	<select class="chzn-select validate[required]" name="Group" <?= ($this->user['AccessLevel'] >= 600000) ? '' : 'disabled'; ?> style="width:200px;">
                                    	<option value=""></option>
                                        <?php foreach($groups as $group) { ?>
                                        	<?php 
												if(isset($client->GroupID)) {
													if($client->GroupID == $group->GroupID) { ?>
														<option selected="selected" value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
													<? }else { ?>
														<option value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
													<? }
												}else { ?>
                                                	<?php if(isset($this->user['DropdownDefault']->SelectedGroup) && $this->user['DropdownDefault']->SelectedGroup != '') { ?>
                                                    <option <?= ($this->user['DropdownDefault']->SelectedGroup == $group->GroupID) ? 'selected="selected"' : ''; ?> value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
                                                    <?php }else { ?>
													<option value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
                                                    <?php } ?>
												<? }?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<?= form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>($client) ? $client->Description : '')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Google Review</label>
			                    <div class="formRight">
			                    	<?= form_input(array('class'=>'validate[custum[url]]','name'=>'GoogleReviewURL','id'=>'GoogleReview','value'=>($client) ? ((isset($client->Reviews['Google'])) ? $client->Reviews['Google'] : '') : '')); ?>
			                        <?= form_hidden('GoogleID',(isset($client->Reviews['GoogleID'])) ? $client->Reviews['GoogleID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Google Review Page</p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                
			                <div class="rowElem noborder">
			                	<label>Yelp Review</label>
			                    <div class="formRight">
			                    	<?= form_input(array('class'=>'validate[custom[url]]','name'=>'YelpReviewURL','id'=>'YelpReview','value'=>($client) ? ((isset($client->Reviews['Yelp'])) ? $client->Reviews['Yelp'] : '') : '')); ?>
			                        <?= form_hidden('YelpID',(isset($client->Reviews['YelpID'])) ? $client->Reviews['YelpID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Yelp Review Page</p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Yahoo Review</label>
			                    <div class="formRight">
			                    	<?= form_input(array('class'=>'validate[custom[url]]','name'=>'YahooReviewURL','id'=>'YahooReview','value'=>($client) ? ((isset($client->Reviews['Yahoo'])) ? $client->Reviews['Yahoo'] : '') : '')); ?>
			                        <?= form_hidden('YahooID',(isset($client->Reviews['YahooID'])) ? $client->Reviews['YahooID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Yahoo Review Page</p>
			                    </div>
			                </div>
                            <div class="rowElem noborder">
                                <div class="formRight" style="text-align:left;padding-top:15px;">
                                	<?php if(isset($client->Status)) { ?>
                                    <input type="radio" id="radio1" name="status" value="1" <?= (($client->Status >= 1) ? 'checked="checked"' : ''); ?> />
                                    <label style="float:none;display:inline;" for="radio1">Enable</label>
                                    <input type="radio" id="radio2" name="status" value="0" <?= (($client->Status < 1) ? 'checked="checked"' : ''); ?>  />
                                    <label style="float:none;display:inline;" for="radio2">Disable</label>
                                    <?php }else { ?>
                                    <input type="radio" id="radio1" name="status" value="1" checked="checked" />
                                    <label style="float:none;display:inline;" for="radio1">Enable</label>
                                    <input type="radio" id="radio2" name="status" value="0" />
                                    <label style="float:none;display:inline;" for="radio2">Disable</label>
                                    <?php } ?>
                                </div>
                                <div class="fix"></div>
                            </div>
			                <div class="fix"></div>
			                <div class="submitForm">
                            	<?php if($client) { ?>
			               			<input type="hidden" name="ClientID" value="<?= $client->ClientID; ?>" />
                                <?php } ?>
			                    <input type="submit" value="<?= ((isset($client->Status)) ? 'Save' : 'Add'); ?>" class="<?= ((isset($client->Status)) ? 'redBtn' : 'greenBtn'); ?>" />
			                </div> 
			                <div class="fix"></div>
			           </fieldset>
    				<?= form_close(); ?>
    				</div>
                    <?php if(isset($websites)) { ?>
    				<div id="websites" class="tab_content" style="display:none;">
                    	<?= (isset($websites)) ? $websites : ''; ?>
    				</div>
                    <?php } ?>
                    <?php if(isset($contacts)) { ?>
                    <div id="contacts" class="tab_content" style="display:none;">
                    	<?= (isset($contacts)) ? $contacts : ''; ?>
                    </div>
                    <?php } ?>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;
	
	$('#tagChanger').change(function() {
		var ele = $(this).find('option:selected');
		var classname = ele.attr('rel');
		$('#tagThumb').attr('class',classname);
	});

	$.mask.definitions['~'] = "[+-]";
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	$(".maskIntPhone").mask("+33 999 999 999");
	$(".maskTin").mask("99-9999999");
	$(".maskSsn").mask("999-99-9999");
	$(".maskProd").mask("a*-999-a999", { placeholder: " " });
	$(".maskEye").mask("~9.99 ~9.99 999");
	$(".maskPo").mask("PO: aaa-999-***");
	$(".maskPct").mask("99%");

	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});
	
	$('form.editClient,form.addClient').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		var formType = '<?= ($client) ? 'edit' : 'add'; ?>';
		$.ajax({
			type:'POST',
			data:formData,
			url:'<?php echo (($client) ? '/admin/clients/form?cid=' . $client->ClientID : '/admin/clients/form?gid=' . $this->user['DropdownDefault']->SelectedGroup); ?>',
			success:function(resp) {
				if(resp == '1') {
					if(formType == 'edit') {
						jAlert('The client was edited successfully','Success',function() {
							clientListTable();
							writeDealerDropdown();
						});
					}else {
						jAlert('The client was added successfully','Success',function() {
							clientListTable();
							writeDealerDropdown();
						});
					}
				}else {
					if(formType == 'edit') {
						jAlert('Something went wrong while editing the client. Please try again.','Error');
					}else {
						jAlert('Something went wrong while adding the client. Please try again.','Error');
					}
				}
			}
		});
	});

	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editClient div.tab_container div.tab_content').hide();
		$('#editClient div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	$(".chzn-select").chosen();
	$("#editClient").dialog({
		minWidth:300,
		width:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:$(this).dialog('close')
			},
			<?php if(isset($client->Status)) { ?>
			{
				class:'greenBtn',
				text:"Add New Website",
				click:function() { addWebsiteForm('<?= $client->ClientID; ?>')}
			} <?php } ?>
		] 
	});
</script>
