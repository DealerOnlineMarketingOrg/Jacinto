<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="viewContact" title="View Contact Details">
        <div class="uiForm">
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="ContactInfo">Contact Details</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="ContactInfo" class="tab_content">
        				<fieldset>
                            <?php if($level == 'g' || $level == 'a') { ?>
			                <div class="rowElem noborder">
			                    <label>Parent Client</label>
			                    <div class="formRight">
                                	<?= form_input(array('disabled'=>'disabled','value'=>$contact->Parent,'style'=>'margin:0')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
                            <?php } ?>
                            <div class="rowElem noborder">
			                    <label>Contact Type</label>
                                <?php if ($contact->TypeCode == 'CID') $type = 'Client';
									elseif ($contact->TypeCode == 'VID') $type = 'Vendor';
									elseif ($contact->TypeCode == 'GID') $type = 'General';
								?>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$type,'style'=>'margin:0')); ?></div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Job Title Type</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->TitleName,'style'=>'margin:0')); ?></div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Job Title</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->JobTitle,'style'=>'margin:0')); ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Name</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Name,'style'=>'margin:0')); ?></div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Email</label>
			                    <div class="formRight"><label style="margin:0;color:red">Home:</label><?= form_input(array('disabled'=>'disabled','value'=>$contact->Email['home'],'style'=>'margin:0')); ?></div>
                                <?php if (isset($contact->Email['work'])) { ?>
			                    <div class="formRight"><label style="margin:0;color:red">Work:</label><?= form_input(array('disabled'=>'disabled','value'=>$contact->Email['work'],'style'=>'margin:0')); ?></div>
                                <?php } ?>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Address['street'].'  '.$contact->Address['city'].', '.$contact->Address['state'].' '.$contact->Address['zipcode'],'style'=>'margin:0')); ?></div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Phone</label>
			                    <div class="formRight"><label style="margin:0;color:red">Main/Direct:</label><?= form_input(array('disabled'=>'disabled','value'=>$contact->Phone['main'],'style'=>'margin:0')); ?></div>
			                    <?php if (isset($contact->Phone['mobile'])) { ?>
			                    <div class="formRight"><label style="margin:0;color:red">Mobile:</label><?= form_input(array('disabled'=>'disabled','value'=>$contact->Phone['mobile'],'style'=>'margin:0')); ?></div>
                                <?php } ?>
			                    <?php if (isset($contact->Phone['fax'])) { ?>
			                    <div class="formRight"><label style="margin:0;color:red">Fax:</label><?= form_input(array('disabled'=>'disabled','value'=>$contact->Phone['fax'],'style'=>'margin:0')); ?></div>
                                <?php } ?>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<textarea name="textarea" cols="80" rows="8" disabled><?= $contact->Notes; ?></textarea>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			           </fieldset>
    				</div>
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
		jQuery('#viewContact div.tab_container div.tab_content').hide();
		jQuery('#viewContact div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery("#viewContact").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true
	});
</script>

