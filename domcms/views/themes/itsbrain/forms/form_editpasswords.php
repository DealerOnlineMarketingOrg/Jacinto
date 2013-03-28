<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editPasswords" title="Edit Password (<?= $contact->Username; ?>)">
        <div class="uiForm">
			<style type="text/css">
				label{margin-top:5px;float:left;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="passwordsInfo">Passwords Information</a></li>
            		<!-- <li><a href="#contacts">Contacts</a></li>
            		<li><a href="#users">Users</a></li>
            		<li><a href="#vendors">Vendors</a></li> -->
            	</ul>
            	<div class="tab_container">
            		<div id="passwordsInfo" class="tab_content">
		            	<?php
					        $form = array(
					            'name' => 'addPasswords',
					            'id' => 'valid',
					            'class' => 'mainForm editPasswords',
					            'style'=>'text-align:left !important;'
					            
					        );
			
			        		echo form_open('/admin/passwords/form_processor/passwords/edit',$form);
			    		?>
        			<!-- Input text fields -->
        				<fieldset>
                        	<div class="rowElem noborder">
			                    <label>Type</label>
			                    <div class="formRight">
                                   	<input type="radio" name="type" value="old" checked>
                                    <select name="type">
                                    	<option value="">Select Type</option>
                                        <?php foreach ($types as $type) { ?>
                                            <option value="<?php echo $type->ID; ?>"<?php if ($type->ID == $contact->ID) echo ' selected'; ?>><?php echo $type->Name; ?></option>
                                        <?php } ?>
                                    </select>
                                   	<input type="radio" name="type" value="new" style="margin-left:15px">
                                    <input type="text" name="typeNew" style="width:15em !important">
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Vendor</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'vendor','id'=>'vendor','value'=> $contact->Vendor)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>URL</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'login_address','id'=>'login_address','value' => $contact->LoginAddress)); ?>
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span>Username</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('class'=>'required validate[required]','name'=>'username','id'=>'username','value'=>$contact->Username)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Password</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'password','id'=>'password','value'=>$contact->Password)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'notes','id'=>'notes','value'=>$contact->Notes)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>	                
			                 
			                <div class="submitForm">
			               		<input type="hidden" name="PasswordsID" value="<?php echo  $contact->ID; ?>" />
			                    <input type="submit" value="submit" class="redBtn" />
			                </div>
			                <div class="fix"></div>
			           </fieldset>
    				<?= form_close(); ?>
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
		jQuery('#editPasswords div.tab_container div.tab_content').hide();
		jQuery('#editPasswords div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery(".chzn-select").chosen();
	jQuery("#editPasswords").dialog({
		minWidth:800,
		height:700,
		autoOpen: true,
		modal: false,
		buttons: {
			Close:function() {
				jQuery('#editPasswords').dialog('close');
				jQuery('#editPasswordsInfo').empty();
			},
		}
	});
</script>
