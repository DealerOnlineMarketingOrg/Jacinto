<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editClient" title="Edit <?= $client->Name; ?>">
        <div class="uiForm">
			<style type="text/css">
				label{margin-top:5px;float:left;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Client Information</a></li>
            		<li><a href="javascript:void(0);" rel="websites">Websites</a></li>
            		<!-- <li><a href="#contacts">Contacts</a></li>
            		<li><a href="#users">Users</a></li>
            		<li><a href="#vendors">Vendors</a></li> -->
            	</ul>
            	<div class="tab_container">
            		<div id="clientInfo" class="tab_content">
		            	<?php
					        $form = array(
					            'name' => 'addClient',
					            'id' => 'valid',
					            'class' => 'mainForm editClient',
					            'style'=>'text-align:left !important;'
					            
					        );
			
			        		echo form_open('/admin/clients/form_processor/clients/edit',$form);
			    		?>
        			<!-- Input text fields -->
        				<fieldset>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span>Client Name</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('class'=>'required validate[required]','name'=>'ClientName','id'=>'name','value'=>$client->Name)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'street','id'=>'address','value'=> ((isset($client->Address['street'])) ? $client->Address['street'] : ''))); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>City</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'city','id'=>'city','value' => ((isset($client->Address['city'])) ? $client->Address['city'] : ''))); ?>
			
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label>State</label>
			                    <div class="formRight searchDrop noSearch" style="text-align:left;">
			                        <?php echo  showStates(((isset($client->Address['state'])) ? $client->Address['state'] : false)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Zip Code</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : ''))); ?>
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Phone</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value' => ((isset($client->Phone['main'])) ? $client->Phone['main'] : ''))); ?>
			                        <span class="formNote">(999) 999-9999</span>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
			                        <?php echo  form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>$client->Description)); ?>
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Client Code</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>$client->Code)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Google Review</label>
			                    <div class="formRight">
			                    	<?php echo  form_input(array('class'=>'validate[custum[url]]','name'=>'GoogleReviewURL','id'=>'GoogleReview','value'=>$client->Reviews['Google'])); ?>
			                        <?php echo  form_hidden('GoogleID',($client->Reviews['GoogleID']) ? $client->Reviews['GoogleID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Google Review Page</p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                
			                <div class="rowElem noborder">
			                	<label>Yelp Review</label>
			                    <div class="formRight">
			                    	<?php echo  form_input(array('class'=>'validate[custom[url]]','name'=>'YelpReviewURL','id'=>'YelpReview','value'=>$client->Reviews['Yelp'])); ?>
			                        <?php echo  form_hidden('YelpID',($client->Reviews['YelpID']) ? $client->Reviews['YelpID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Yelp Review Page</p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                
			                <div class="rowElem noborder">
			                	<label>Yahoo Review</label>
			                    <div class="formRight">
			                    	<?php echo  form_input(array('class'=>'validate[custom[url]]','name'=>'YahooReviewURL','id'=>'YahooReview','value'=>$client->Reviews['Yahoo'])); ?>
			                        <?php echo  form_hidden('YahooID',($client->Reviews['YahooID']) ? $client->Reviews['YahooID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Yahoo Review Page</p>
			                    </div>
			                </div>
			                
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Tags</label>
			                    <div class="formRight searchDrop noSearch" style="text-align:left;">
			                        <select style="width:200px;" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="9">
			                            <option value=""></option>
			                            <?php foreach($tags as $tag) : ?>
			                            	<option <?php echo  (($tag->ID == $client->Tag) ? 'selected="selected"' : ''); ?> value="<?php echo  $tag->ID; ?>"><?php echo  $tag->Name; ?></option>
			                            <?php endforeach; ?>
			                        </select>
			                    </div>
			                    <div class="fix"></div>
			                </div>		                
			                 
			                <div class="submitForm">
			               		<input type="hidden" name="ClientID" value="<?php echo  $client->ClientID; ?>" />
			                    <input type="submit" value="submit" class="redBtn" />
			                    
			                </div> 
			                <div class="fix"></div>
			           </fieldset>
    				<?= form_close(); ?>
    				</div>
    				<div id="websites" class="tab_content">
    					<?php if(isset($websites)) {
    						echo $websites;} ?>
    				</div>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">
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
		height:700,
		autoOpen: true,
		modal: false,
		buttons: {
			Close:function() {
				jQuery('#editClient').dialog('close');
				jQuery('#editClientInfo').empty();
			},
		}
	});
</script>
