<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="viewContact" title="View Contact Details">
        <div class="uiForm">
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="ContactDetails">Contact Details</a></li>
            		<li class=""><a href="javascript:void(0);" rel="Website">Website</a></li>
            		<li class=""><a href="javascript:void(0);" rel="ContactInfo">Contact Info</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="ContactDetails" class="tab_content" style="padding:0;">
                        <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                            <thead>
                                <tr>
                                    <td width="1%"></td>
                                    <td width="99%"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Name: <?= $contact->Name; ?></td>
                                </tr>
                                    <td></td>
                                    <td>Title: <?= $contact->JobTitle; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Address: <?= $contact->Address['street'].'  '.$contact->Address['city'].', '.$contact->Address['state'].' '.$contact->Address['zipcode']; ?></td>
                                </tr>
                                <tr>
                                	<td></td>
                                    <td>Primary Email: <?= $contact->Email['work']; ?></td>
                                <tr>
                                    <td></td>
                                    <td>Primary Phone: <?= $contact->Phone['main']; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Member of: <?= $contact->Dealership; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Notes: <?= $contact->Notes; ?></td>
                                </tr>
                            </tbody>
                        </table>
    				</div>
    				<div class="fix"></div>
            		<div id="Website" class="tab_content" style="display:none;padding:0;">
                        <?= $websites; ?>
    				</div>
            		<div id="ContactInfo" class="tab_content" style="display:none;padding:0;">
                    	<div style="text-align:center"><label>Phone Numbers</label></div>
                        <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                            <thead>
                                <tr>
                                    <td width="10%">Type</td>
                                    <td width="80%">Phone Number</td>
                                    <td width="10%"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cell</td>
                                    <td><?= $contact->Phone['main']; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Home</td>
                                    <td><?= isset($contact->Phone['mobile']) ? $contact->Phone['mobile'] : ''; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Office</td>
                                    <td><?= isset($contact->Phone['fax']) ? $contact->Phone['fax'] : ''; ?></td>
                                    <td></td>
                                </tr>                                
                            </tbody>
                        </table>
                        
                        <p></p>
                        <p></p>
                        
						<div style="text-align:center"><label>Email Addresses</label></div>
                        <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                            <thead>
                                <tr>
                                    <td width="10%">Type</td>
                                    <td width="80%">Email Address</td>
                                    <td width="10%"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Personal</td>
                                    <td><?= $contact->Email['home']; ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Work</td>
                                    <td><?= isset($contact->Email['work']) ? $contact->Email['work'] : ''; ?></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
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

