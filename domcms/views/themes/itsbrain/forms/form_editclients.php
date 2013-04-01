<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editClient" title="<?= ($client) ? 'Edit ' . $client->Name : 'Add New Client'; ?>">
        <div class="uiForm">
			<style type="text/css">
				label{margin-top:5px;float:left;}
				div.formError{z-index:2000 !important;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Client Information</a></li>
            		<?php if(isset($websites)) { ?>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <?php } ?>
            		<!-- <li><a href="#contacts">Contacts</a></li>
            		<li><a href="#users">Users</a></li>
            		<li><a href="#vendors">Vendors</a></li> -->
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
							
			        		echo (!$client) ? form_open('/admin/clients/form_processor/clients/add',$form) : form_open('/admin/clients/form_processor/clients/edit',$form);
			    		?>
        				<!-- Input text fields -->
        				<fieldset>
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
			                        <?= form_input(array('name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : ''))); ?>
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
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<?= form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>($client) ? $client->Description : '')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Client Code</label>
			                    <div class="formRight">
			                        <?= form_input(array('class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>($client) ? $client->Code : '')); ?>
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
			                    <label><span class="req">*</span> Tags</label>
			                    <div class="formRight searchDrop noSearch" style="text-align:left;">
			                        <select style="width:200px;" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="9">
			                            <option value=""></option>
			                            <?php foreach($tags as $tag) : ?>
			                            	<option <?= ($client) ? (($tag->ID == $client->Tag) ? 'selected="selected"' : '') : ''; ?> value="<?= $tag->ID; ?>"><?= $tag->Name; ?></option>
			                            <?php endforeach; ?>
			                        </select>
			                    </div>
			                    <div class="fix"></div>
			                </div>		                
			                 
			                <div class="submitForm">
                            	<?php if($client) { ?>
			               			<input type="hidden" name="ClientID" value="<?= $client->ClientID; ?>" />
                                <?php } ?>
			                    <input type="submit" value="submit" class="redBtn" />
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
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery.mask.definitions['~'] = "[+-]";
	jQuery(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	jQuery(".maskPhone").mask("(999) 999-9999");
	jQuery(".maskPhoneExt").mask("(999) 999-9999? x99999");
	jQuery(".maskIntPhone").mask("+33 999 999 999");
	jQuery(".maskTin").mask("99-9999999");
	jQuery(".maskSsn").mask("999-99-9999");
	jQuery(".maskProd").mask("a*-999-a999", { placeholder: " " });
	jQuery(".maskEye").mask("~9.99 ~9.99 999");
	jQuery(".maskPo").mask("PO: aaa-999-***");
	jQuery(".maskPct").mask("99%");

	//reinitialize the validation plugin
	jQuery("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});
	
	jQuery('form.editClient,form.addClient').submit(function(e) {
		e.preventDefault();
		var formData = jQuery(this).serialize();
		var type = '<?= ($client) ? 'edit' : 'add'; ?>';
		jQuery.ajax({
			type:'POST',
			data:formData,
			url:'<?php echo (($client) ? '/admin/clients/form?cid=' . $client->ClientID : '/admin/clients/form?gid=' . $this->user['DropdownDefault']->SelectedGroup); ?>',
			success:function(resp) {
				if(resp == '1') {
					if(type != 'add') {
						jAlert('The client was edited successfully','Success',function() {
							jQuery('#editClient').dialog('close');
							clientListTable();
						});
					}else {
						jAlert('The client was added successfully','Success',function() {
							jQuery('#editClient').dialog('close');
							clientListTable();
						});
					}
				}else {
					if(type != 'add') {
						jAlert('The edited changes could not be processed. Please contact support or try again','Error',function() {
							jQuery('#editClient').dialog('close');
							clientListTable();
						});
					}else {
						jAlert('The client you are trying to add could not be added. Please try again','Error',function() {
							jQuery('#editClient').dialog('close');
							clientListTable();
						});
					}
				}
			}
		});
	});

	jQuery('ul.tabs li a').live('click',function() {
		//remove all activetabs
		jQuery('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		jQuery(this).parent().addClass('activeTab');
		var content = 'div#' + jQuery(this).attr('rel');
		//alert(content);
		jQuery('#editClient div.tab_container div.tab_content').hide();
		jQuery('#editClient div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery(".chzn-select").chosen();
	jQuery("#editClient").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: false
	});
</script>
