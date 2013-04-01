<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="viewClient" title="View">
        <div class="uiForm">
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Client Information</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="clientInfo" class="tab_content">
        				<fieldset>
			                <div class="rowElem noborder">
			                    <label>Client Name</label>
			                    <div class="formRight"><?= $client->Name; ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Client Code</label>
			                    <div class="formRight">
                                	<?= $client->Code; ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight"><?= $client->Address['street'] . ' ' . $client->Address['city'] . ', ' . $client->Address['state'] . ' ' . $client->Address['zipcode']; ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Phone</label>
			                    <div class="formRight">
									<?= $client->Phone['main']; ?>
                                    <span class="formNote">Primary Number</span>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<p><?= $client->Description; ?></p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Review Sites</label>
			                    <div class="formRight">
                                	<a href="<?= $client->Reviews['Google']; ?>" target="_blank">Google Review Page</a><br />
                                    <a href="<?= $client->Reviews['Yelp']; ?>" target="_blank">Yelp Review Page</a><br />
                                    <a href="<?= $client->Reviews['Yahoo'];?>" target="_blank">Yahoo Review Page</a>
			                        <p class="formNote">Click links to open a new tab</p>
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
		jQuery('#editClient div.tab_container div.tab_content').hide();
		jQuery('#editClient div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery("#viewClient").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: false
	});
</script>
