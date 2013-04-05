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
                                	<?= form_input(array('disabled'=>'disabled','value'=>$contact->Parent)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
                            <?php } ?>
			                <div class="rowElem noborder">
			                    <label>Name</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Name)); ?></div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Emails</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Name)); ?></div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Email['home'])); ?></div>
                                <?php if (isset($contact->Email['work'])) { ?>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Email['work'])); ?></div>
                                <?php } ?>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
			                    <label>Phone</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Phone['main'])); ?></div>
			                    <?php if (isset($contact->Phone['mobile'])) { ?>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Phone['mobile'])); ?></div>
                                <?php } ?>
			                    <?php if (isset($contact->Phone['fax'])) { ?>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$contact->Phone['fax'])); ?></div>
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

