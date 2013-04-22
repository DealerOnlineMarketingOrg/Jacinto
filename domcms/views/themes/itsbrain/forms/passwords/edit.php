<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editPasswords" title="Edit Password (<?= $password->Username; ?>)">
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
			                    <div class="formRight noSearch">
                                   	<input type="radio" name="radioType" value="old" style="float:left;margin-right:5px;margin-top:18px;" checked>
                                    <div style="float:left;margin-left:5px;margin-top:10px;width:125px;">
                                        <select name="types" class="chzn-select">
                                            <option value="">Select Type</option>
                                            <?php foreach ($types as $type) { ?>
                                                <option value="<?php echo $type->ID; ?>"<?php if ($type->ID == $password->ID) echo ' selected'; ?>><?php echo $type->Name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                   	<input type="radio" name="radioType" value="new" style="margin-top:5px;margin-left:15px;margin-right:5px;">
                                    <input type="text" name="newType" style="width:15em !important">
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Vendor</label>
			                    <div class="formRight noSearch">
                                    <input type="radio" name="radioVendor" style="float:left;margin-right:5px;margin-top:18px;" value="old" checked>
                                    <div style="float:left;margin-left:5px;margin-top:10px;width:125px;">
                                        <select name="vendors" class="chzn-select" style="margin-top:15px !important;">
                                            <option value="">Select Vendor</option>
                                            <?php foreach ($vendors as $vendor) { ?>
                                                <option value="<?php echo $vendor->ID; ?>"<?php if ($vendor->ID == $password->VendorID) echo ' selected'; ?>><?php echo $vendor->Name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                   	<input type="radio" name="radioVendor" value="new" style="margin-top:5px;margin-left:15px;margin-right:5px;">
                                    <input type="text" name="newVendor" style="width:15em !important">
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>URL</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'login_address','id'=>'login_address','value' => $password->LoginAddress)); ?>
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span>Username</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('class'=>'required validate[required]','name'=>'username','id'=>'username','value'=>$password->Username)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Password</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'password','id'=>'password','value'=>$password->Password)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
			                        <?php echo  form_input(array('name'=>'notes','id'=>'notes','value'=>$password->Notes)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>	                
			                 
			                <div class="submitForm">
			               		<input type="hidden" name="PasswordsID" value="<?php echo  $password->ID; ?>" />
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
		buttons: [
			{
				class:'redBtn',
				text:'Save',
				click:function() {jQuery('#valid').submit()}	
			},
		]
	});
</script>
