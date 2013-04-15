<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	 
//This function will check to see if the user has given module access, the name of the module and the user level is required.
//This is our Bouncer for the whole system, it should boot any users trying to view a module without access to the dashboard.
function GateKeeper($mod,$uPerm) {
	
	//We need to know where codeigniter is.
	$ci =& get_instance();
	$ci->load->model('mods');
	
	//load the model in the helper
	$perms = $ci->mods->getModLevelByName($mod);
	
	//check the permission levels
	if (!$perms) {
		return false;
	} else {
		if ($uPerm >= $perms->Level) {
			return TRUE;
		} else {
			return false;
		}
	}
}

function MasterlistTable() { ?>
	<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/masterlist_popups.js"></script>
    <?php
		$ci =& get_instance();
		$userPermissionLevel = $ci->user['AccessLevel'];
		$addPriv			 = GateKeeper('Masterlist_Add',$userPermissionLevel);
		$editPriv			 = GateKeeper('Masterlist_Edit',$userPermissionLevel);
		$disablePriv		 = GateKeeper('Masterlist_Disable_Enable',$userPermissionLevel);
		$listingPriv		 = GateKeeper('Masterlist_List',$userPermissionLevel);
		
		//load masterlist queries
		$ci->load->model('mlist');
		$clients = $ci->mlist->buildMasterList();
		
		if($addPriv) { ?>
    		<!-- <a href="javascript:addMasterlist();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Entry</a> -->
<?php 	}
		
		if($clients AND $listingPriv) { ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display masterlistTable" id="example" width="100%">
                <thead>
                    <tr>
                        <th class="tag">Tag</th>
                        <th class="code">Code</th>
                        <th class="dealerName">Dealership</th>
                        <th class="websiteLink">Website</th>
                        <th class="crazyEgg">Crazy Egg</th>
                        <th class="cmslist">CMS</th>
                        <th class="crmlist">CRM</th>
                        <th class="doclist actions">DOC</th>
                        <th class="excellist actions">XSL</th>
                        <?php if($editPriv) { ?>
                            <th class="actions">Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clients as $client) : ?>
                        <tr class="tagElement <?= $client->Class; ?>">
                            <td class="tag"><div class="<?= $client->Class; ?>">&nbsp;</div></td>
                            <td class="code"><?= $client->Code; ?></td>
                            <td class="dealerName"><?= $client->Dealership; ?></td>
                            <td class="websiteLink">
                                <?php if(isset($client->Websites) AND !empty($client->Websites)) { ?>
                                    <ul>
                                        <?php foreach($client->Websites as $website) { ?>
                                        	<?php if(isset($website->href)) { ?>
                                            	<li><a href="<?= $website->href; ?>" target="_blank"><?= str_replace('http://','',$website->href); ?></a></li>
                                            <?php }else { ?>
                                            	<li><span class="fillerString">...</span></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php }else { ?>
                                    <span class="fillerString">...</span>
                                <?php } ?>
                            </td>
                            <td class="crazyEgg">
                                <?php if(isset($client->Websites) AND !empty($client->Websites)) { ?>
                                    <ul>
										<?php foreach($client->Websites as $website) { ?>
                                            <?php if(isset($website->CrazyEggLabel)) { ?>
                                            	<li><a href="http://www.crazyegg.com/login" target="_blank"><?= $website->CrazyEggLabel; ?></a></li>
                                            <?php }else { ?>
												<li><span class="fillerString">...</span></li>
											<?php }?>
                                        <?php } ?>
                                    </ul>
                                <?php }else { ?>
                                    <span class="fillerString">...</span>
                                <?php } ?>        
                            </td>
                            <td class="cmslist">
                                <?php if(isset($client->Websites) AND !empty($client->Websites)) { ?>
                                    <ul>
                                        <?php foreach($client->Websites as $website) { ?>
                                        	<?php if(isset($website->CMSLink) AND isset($website->VendorName)) { ?>
                                            	<li><a href="<?= $website->CMSLink; ?>" target="_blank"><?= $website->VendorName; ?></a></li>
                                            <?php }else { ?>
                                            	<li><span class="fillerString">...</span></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php }else { ?>
                                    <span class="fillerString">...</span>
                                <?php } ?>
                            </td>
                            <td class="crmlist">
                                <?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                    <ul>
                                        <?php foreach($client->Assets as $asset) { ?>
                                        	<?php if(isset($asset->CRMLink) && isset($asset->VendorName)) { ?>
                                            	<li><a href="<?= $asset->CRMLink; ?>" target="_blank"><?= $asset->VendorName; ?></a></li>
                                            <?php }else { ?>
                                            	<li><span class="fillerString">...</span></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php }else { ?>
                                    <span class="fillerString">...</span>
                                <?php } ?>
                            </td>
                            <td class="doclist">
                                <?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                    <ul>
                                        <?php foreach($client->Assets as $asset) { ?>
                                        	<?php if(isset($asset->DOCLink)) { ?>
                                            	<li><a title="Google Doc" href="<?= $asset->DOCLink; ?>" target="_blank"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/document-word-text.png" alt="" /></a></li>
                                            <?php }else { ?>
                                            	<li><span class="fillerString">...</span></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php }else { ?>
                                    <span class="fillerString">...</span>
                                <?php } ?>
                            </td>
                            <td class="excellist">
                                <?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                    <ul>
                                        <?php foreach($client->Assets as $asset) { ?>
                                        	<?php if(isset($asset->ExcelLink)) { ?>
                                            	<li><a title="Google Doc" href="<?= $asset->ExcelLink; ?>" target="_blank"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/document-excel.png" alt="" /></a></li>
                                            <?php }else { ?>
                                            	<li><span class="fillerString">...</span></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php }else { ?>
                                    <span class="fillerString">...</span>
                                <?php } ?>
                            </td>
                            <?php //blue-document-excel.png; ?>
                            <?php if($editPriv) { ?>
                                <td class="actionsCol actions">
                                    <a title="Edit Client" href="javascript:editEntry('<?= $client->ClientID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php   }else { ?>
            <p>You don't have access to view this data. Sorry.</p>
<?php   }
        if($addPriv) { ?>
            <!-- <a href="javascript:addMasterlist();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Entry</a> -->
<?php 	}
}

function AgencyListingTable($agencies = false) { ?>
	<?php if($agencies) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/masterlist_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Agency_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Agency_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Agency_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Agency_List',$userPermissionLevel);
    ?>
    <?php if($addPriv) { ?><a href="javascript:addAgency();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Agency</a><?php } ?>
    <?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:30%;text-align:left;">Name</th>
                    <th style="text-align:left;">Description</th>
                    <th>Status</th>
                    <?php if($editPriv) { ?>
                    <th class="actions">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($agencies as $agency) { ?>
                    <tr>
                        <td style="text-align:left;"><?= $agency->Name; ?></td>
                        <td><?= $agency->Description; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($agency->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol" style="width:75px;text-align:center;">
                            <a title="Edit Agency" href="javascript:editEntry('<?= $agency->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addAgency();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Agency</a><?php } ?>
    <?php else : ?>
    <p>No agencies found.</p>
    <?php endif; ?>
<?php }

function GroupsListingTable($groups = false) { ?>
	<?php if($groups) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/group_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Group_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Group_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Group_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Group_List',$userPermissionLevel);
    ?>
    <?php if($addPriv) { ?><a href="javascript:addGroup();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Group</a><?php } ?>
    <?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:30%;text-align:left;">Name</th>
                    <th style="text-align:left;">Member Of</th>
                    <th class="status">Status</th>
                    <?php if($editPriv) { ?>
                    <th class="actions">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $group) { ?>
                    <tr>
                        <td style="text-align:left;"><?= $group->Name; ?></td>
                        <td><?= $group->AgencyName; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($group->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol" style="width:75px;text-align:center;">
                            <a title="Edit Group" href="javascript:editGroup('<?= $group->GroupId; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Group" href="javascript:viewGroup('<?= $group->GroupId; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addGroup();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Group</a><?php } ?>
    <?php else : ?>
    <p>No groups found.</p>
    <?php endif; ?>
<?php }

function VendorListingTable($hide_actions=false,$hide_add=false) { ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/vendor_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Vendor_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Vendor_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Vendor_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Vendor_List',$userPermissionLevel);
		$ci->load->model('administration');
		
		$vendors = $ci->administration->getVendors();
		
    ?>
    <?php if($addPriv) { ?><?php if(!$hide_add OR !$hide_actions) { ?><a href="javascript:addVendor();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Vendor</a><?php } ?><?php } ?>
    <?php if($listingPriv) { ?>
    		<?php if($vendors) { ?>
                <table cellpadding="0" cellspacing="0" border="0" class="display vendors" id="example" width="100%;">
                    <thead>
                        <tr>
                            <th style="text-align:left;white-space:nowrap;">Vendor Name</th>
                            <th style="text-align:left;white-space:nowrap;">Vendor Address</th>
                            <th style="white-space:nowrap;">Vendor Phone</th>
                            <?php if($editPriv) { ?>
                                <?php if(!$hide_actions) { ?>
                                    <th class="actions" style="white-space:nowrap;">Actions</th>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($vendors as $vendor) { $vendor->Address = ($vendor->Address != '') ? mod_parser($vendor->Address) : FALSE; ?>
                            <tr>
                                <td style="text-align:left;white-space:nowrap;"><?= $vendor->Name; ?></td>
                                <td style="white-space:nowrap;"><?= ($vendor->Address) ? $vendor->Address['street'] . ' ' . $vendor->Address['city'] . ', ' . $vendor->Address['state'] . ' ' . $vendor->Address['zipcode'] : '...'; ?></td>
                                <td style="text-align:left;white-space:nowrap;"><?= ($vendor->Phone != '') ? $vendor->Phone : '...'; ?></td>
                                <?php if($editPriv) { ?>
                                    <?php if(!$hide_actions) { ?>
                                        <td class="actionsCol" style="width:75px;text-align:center;white-space:nowrap;">
                                            <a title="Edit Group" href="javascript:editVendor('<?= $vendor->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                            <a title="View Group" href="javascript:viewVendor('<?= $vendor->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                                        </td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }else { ?>
                <p>No Vendors found.</p>
			<?php } ?>
    <?php }else { ?>
    	<p>You dont have access to view vendors.</p>
    <?php } ?>
    <?php if($addPriv) { ?><?php if(!$hide_add OR !$hide_actions) { ?><a href="javascript:addVendor();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Vendor</a><?php } ?><?php } ?>
<?php }


function ContactsListingTable($client_id = false,$hide_add = false,$hide_actions = false,$from_tab = false,$type = false) { ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/contact_popups.js"></script>
    <?php 
		$ci =& get_instance();
		$level				 = $ci->user['DropdownDefault']->LevelType;
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Contact_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Contact_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Contact_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Contact_List',$userPermissionLevel);
				
		switch ($level) {
			case 'a':
				if(!$client_id) {
					$contacts = $ci->administration->getAllContactsInAgency($ci->user['DropdownDefault']->SelectedAgency);
				}else {
					if($type) {
						$contacts = $ci->administration->getContacts($client_id,$type);
					}else {
						$contacts = $ci->administration->getContacts($client_id);	
					}
				}
			break;
			case 'g':
				if(!$client_id) {
					$contacts = $ci->administration->getAllContactsInGroup($ci->user['DropdownDefault']->SelectedGroup);
				}else {
					if($type) {
						$contacts = $ci->administration->getContacts($client_id,$type);
					}else {
						$contacts = $ci->administration->getContacts($client_id);	
					}
				}
			break;
			default:
				if(!$client_id) {
					$contacts = $ci->administration->getContacts($ci->user['DropdownDefault']->SelectedClient);
				}else {
					if($type) {
						$contacts = $ci->administration->getContacts($client_id,$type);
					}else {
						$contacts = $ci->administration->getContacts($client_id);	
					}
				}
			break;
		}

    ?>
    <?php if($addPriv) { ?><a href="javascript:addContact();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Contact</a><?php } ?>
    <?php if($listingPriv) { ?>
    	<?php if($contacts) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="<?php echo ($from_tab) ? 'tableStatic' : 'display contacts'; ?>" id="<?php echo ($from_tab) ? 'contacts' : 'example'; ?>" width="100%;">
            <thead>
                <tr>
                    <?php if(!$from_tab) { ?><th>Team</th><?php } ?>
                    <?php if($level == 'g' || $level == 'a') { ?>
                    <?php if(!$from_tab) { ?><th style="text-align:left;white-space:nowrap;">Dealership</th><?php } ?>
                    <?php } ?>
                    <th style="width:10%;white-space:nowrap;">Title Name</th>
                    <th style="white-space:nowrap;">Contact Name</th>
                    <th><?php if($from_tab) { echo 'Primary'; } ?> Email</th>
                    <th><?php if($from_tab) { echo 'Primary'; } ?> Phone</th>
                    <?php if($editPriv) { ?>
                    	<?php if(!$hide_actions) { ?>
                    		<th class="actions">Actions</th>
                    	<?php } ?>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($contacts as $contact) { ?>
                	
                    <?php
						/*
							FORMAT THE CONTACTS
						*/
						$contact->Name = $contact->FirstName . ' ' . $contact->LastName;
						$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
						$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone) : false;
						$contact->Email = mod_parser($contact->Email);
						$contact->Parent = $ci->administration->getClient(substr($contact->Type,4))->Name;
						$contact->TypeCode = substr($contact->Type,0,3);
						$contact->TypeID = substr($contact->Type,4);
					?>

                
                    <tr class="tagElement <?php echo $contact->Tag; ?>" >
                    	<?php if(!$from_tab) { ?><td class="tags"><div class="<?php echo $contact->Tag; ?>">&nbsp;</div></td><?php } ?>
                        <?php if($level == 'g' || $level == 'a') { ?>
                        <?php if(!$from_tab) { ?><td style="width:auto;white-space:no-wrap;text-align:left;white-space:nowrap;"><?php echo $contact->Parent; ?></td><?php } ?>
                        <?php } ?>
                        <td style="text-align:left;white-space:nowrap;"><?= $contact->JobTitle; ?></td>
                        <td><?= $contact->Name; ?></td>
                        <td>
                        <?php if(!$from_tab) { ?><span style="font-weight:bold;">Personal Email</span><br /><?php } ?><a href="mailto:'<?php echo $contact->Email["home"]; ?>'"><?php echo $contact->Email['home']; ?></a>
                        <?php if(!$from_tab) { ?>
							<?php if (isset($contact->Email['work'])) { ?>
                            <br /><span style="font-weight:bold;">Work Email</span><br /><a href="mailto:'<?php echo $contact->Email["work"]; ?>'"><?php echo $contact->Email['work']; ?></a>
                            <?php } ?>
                        <?php } ?>
                        </td>
                        <td> 
                                               
                        <?php if(!$from_tab) { ?><span style="font-weight:bold;">Direct</span><br /><?php } ?><span style="white-space:nowrap;"><a href="tel:'<?php echo $contact->Phone["main"]; ?>'"><?php echo $contact->Phone['main']; ?></a></span>
                        <?php if(!$from_tab) { ?>
							<?php if (isset($contact->Phone['mobile'])) { ?>
                            	<span style="font-weight:bold;">Mobile</span><br /><span style="white-space:nowrap;"><a href="tel:'<?php echo $contact->Phone["mobile"]; ?>'"><?php echo $contact->Phone['mobile']; ?></a></span>
                            <?php } ?>
                            <?php if (isset($contact->Phone['fax'])) { ?>
                            	<span style="font-weight:bold;">Fax</span><br /><span style="white-space:nowrap;"><a href="tel:'<?php echo $contact->Phone["fax"]; ?>'"><?php echo $contact->Phone['fax']; ?></a></span>
                            <?php } ?>
                        <?php } ?>
                        </td>
                        <?php if($editPriv) { ?>
                        	<?php if(!$hide_actions) { ?>
                                <td class="actionsCol" style=" <?php if(!$from_tab) { ?>width:75px;<?php }else {?>width:30px;<?php } ?>text-align:center;">
                                    <a title="Edit Contact" href="javascript:editContact('<?= $contact->ContactID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                    <?php if(!$from_tab) { ?><a title="View Contact" href="javascript:viewContact('<?= $contact->ContactID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a><?php } ?>
                                </td>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php }else { ?>
       <p>No Contacts have been added.</p>
	<? }?>
    <?php if($addPriv) { ?>
    	<?php if(!$hide_add) { ?>
    	<a href="javascript:addContact();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Contact</a>
        <?php } ?>
	<?php } ?>
    <?php }else { ?>
        <p>No contacts found.</p>
	<?php } ?>
<?php }

function GroupsClientTable($group_id) {
	$ci =& get_instance();
	//load the model
	$ci->load->model('administration');
	//get the clients in the group
	$clients = $ci->administration->getAllClientsInGroup($group_id); 
	
	if($clients) {
	?>
	
    <table cellpadding="0" cellspacing="0" class="tableStatic" id="clientExample" style="width:100%;">
    	<thead>
        	<tr>
            	<td style="width:31px;text-align:center;">Tag</td>
                <td style="width:51px;text-align:center;">Code</td>
                <td style="text-align:left;padding-left:5px;">Dealership Name</td>
                <td style="width:50px;text-align:center;">Status</td>
            </tr>
        </thead>
        <tbody>
        	<?php foreach($clients as $client) : ?>
            	<tr class="tagElement <?= $client->ClassName; ?>">
                	<td><div class="<?= $client->ClassName; ?>">&nbsp;</div></td>
                    <td><?= $client->ClientCode; ?></td>
                    <td><?= $client->Name; ?></td>
                    <td><?php if($client->Status) { ?><div class="iCheck">&nbsp;</div><?php }else { ?><div class="iBlock">&nbsp;</div><?php } ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
<?php }else { ?>
	<p>No clients have been added to this group.</p>
<? } }

function ClientsListingTable($clients = false) { ?>
	<?php if($clients) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/client_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Client_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Client_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Client_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Client_List',$userPermissionLevel);
    ?>
    <?php if($addPriv) { ?><a href="javascript:addClient();" class="greenBtn floatRight button" style="margin-top:-74px;margin-right:3px;">Add New Client</a><?php } ?>
    <?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center;">Tag</th>
                    <th style="text-align:left;width:50px;">Code</th>
                    <th style="text-align:left;width:30%;">Dealership Name</th>
                    <th style="text-align:left;">Group</th>
                    <th>Status</th>
                    <?php if($editPriv) { ?>
                    <th class="actions">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $client) { ?>
                    <tr class="tagElement <?= $client->ClassName; ?>">
                    	<td class="tags"><div class="<?= $client->ClassName; ?>">&nbsp;</div></td>
                        <td><?= $client->ClientCode; ?></td>
                        <td style="text-align:left;"><?= $client->Name; ?></td>
                        <td><?= $client->GroupName; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($client->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol actions" style="width:60px;text-align:center;">
                            <a title="Edit Client" href="javascript:editClient('<?= $client->ClientID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Client" href="javascript:viewClient('<?= $client->ClientID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addClient();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Client</a><?php } ?>
    <?php else : ?>
    <p>No clients found.</p>
    <?php endif; ?>
<?php }

function UserListingTable($client_id = false,$hide_actions = false) { ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/user_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
		$level				 = $ci->user['DropdownDefault']->LevelType;
        $addPriv     		 = GateKeeper('User_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('User_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('User_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('User_List',$userPermissionLevel);
		
		//collect users here
		$users = array();
		
		$agency_id = $ci->user['DropdownDefault']->SelectedAgency;
		$group_id = $ci->user['DropdownDefault']->SelectedGroup;
		
		switch($level) {
			case "a":
				//get all groups, all users are associated to client level. if were on agency level, we still dont know where all clients are coming from until we know the groups.
				$groups = $ci->administration->getAllGroupsInAgencyResults($agency_id);
				//loop through the groups to get the clients so we can get the users.
				foreach($groups as $group) {
					//now we know the group info
					$clients = $ci->administration->getAllClientsInGroup($group->GroupID);
					foreach($clients as $client) {
						//get the users in the clients, but we still dont have them in a single level array.
						$userGrouped = $ci->administration->getUsers(false,$client->ClientID);
						//check to see if the user group returned data, if so we need to loop through them and push the contents to our collection array
						if($userGrouped) {
							foreach($userGrouped as $usersGroup) {
								//push users to the array
								array_push($users,$usersGroup);	
							}
						}

					}
				}
			break;
			case 1:
				//get all groups, all users are associated to client level. if were on agency level, we still dont know where all clients are coming from until we know the groups.
				$groups = $ci->administration->getAllGroupsInAgencyResults($agency_id);
				//loop through the groups to get the clients so we can get the users.
				foreach($groups as $group) {
					//now we know the group info
					$clients = $ci->administration->getAllClientsInGroup($group->GroupID);
					foreach($clients as $client) {
						//get the users in the clients, but we still dont have them in a single level array.
						$userGrouped = $ci->administration->getUsers(false,$client->ClientID);
						//check to see if the user group returned data, if so we need to loop through them and push the contents to our collection array
						if($userGrouped) {
							foreach($userGrouped as $usersGroup) {
								//push users to the array
								array_push($users,$usersGroup);	
							}
						}

					}
				}
			break;
			case "g":
				//grab the clients in the group
				$clients = $ci->administration->getAllClientsInGroup($group_id);
				//loop through each client and grab its users, then reformat the return into a 1 level array and push to our collection array.
				if(count($clients) > 0) {
					foreach($clients as $client) {
						$userGrouped = $ci->administration->getUsers(false,$client->ClientID);
						if($userGrouped) {
							foreach($userGrouped as $usersGroup) {
								array_push($users,$usersGroup);	
							}
						}
					}
				}
			break;
			case 2:
				//grab the clients in the group
				$clients = $ci->administration->getAllClientsInGroup($group_id);
				//loop through each client and grab its users, then reformat the return into a 1 level array and push to our collection array.
				foreach($clients as $client) {
					$userGrouped = $ci->administration->getUsers(false,$client->ClientID);
					if($userGrouped) {
						foreach($userGrouped as $usersGroup) {
							array_push($users,$usersGroup);	
						}
					}
				}
			break;
			default:
				$client_id = (!$client_id) ? $ci->user['DropdownDefault']->SelectedClient : $client_id;	
				$users = $ci->administration->getUsers(false,$client_id);
			break;	
		}
		
		if(count($users) > 0) {
    ?>
    <?php if($addPriv) { ?><a href="javascript:addUser();" class="greenBtn floatRight button" style="margin-top:-74px;margin-right:3px;">Add New User</a><?php } ?>
    <?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center;">Team</th>
                    <th style="text-align:center;width:50px;">Avatar</th>
                    <th style="text-align:left;width:30%;">Email Address</th>
                    <th style="text-align:left;">Name</th>
                    <th>Status</th>
                    <?php if($editPriv) { ?>
                    	<th class="actions">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user) { $avatar = $ci->members->get_user_avatar($user->ID); ?>
                    <tr class="tagElement <?= $user->ClassName; ?>">
                    	<td class="tags" style="vertical-align: middle;"><div class="<?= $user->ClassName; ?>">&nbsp;</div></td>
                        <td style="text-align:center;vertical-align: middle;"><div style="text-align:center"><img src="<?= $avatar; ?>" style="width:30px;" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" /></div></td>
                        <td style="text-align:left;vertical-align: middle;"><a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></td>
                        <td style="vertical-align:middle;"><?= $user->FirstName . ' ' . $user->LastName; ?></td>
                        <td style="width:30px;text-align:center;vertical-align: middle;"><?= (($user->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol actions" style="width:60px;text-align:center;vertical-align: middle;">
                            <a title="Edit Client" href="javascript:editUser('<?= $user->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Client" href="javascript:viewUser('<?= $user->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addUser();" class="greenBtn floatRight button" style="margin-top:10px;">Add New User</a><?php } ?>
    <?php }else { ?>
    	<p>No Users found.</p>
    <?php } ?>
<?php }

function get_welcome_message() {
    $ci =& get_instance();
    return 'Welcome, ' . $ci->session->userdata['valid_user']['FirstName'] . ' ' . $ci->session->userdata['valid_user']['LastName'];
}

function strip_chars_from_phone($phone) {
	return preg_replace("/[^0-9]/", "", $phone);
}

function reset_users_password($email) {
	$ci =& get_instance();
	$ci->load->model('members');
	$ci->load->helper('pass');
	$password = createRandomString(10,'ALPHANUMSYM');
	
	if($password) {
		$passchange = $ci->members->reset_password($email,$password);
		if($passchange) {
			return $password;
		}else {
			return FALSE;
		}
	}else {
		return FALSE;
	}
}

function ArrayWithTextIndexToString($array, $type = false) {
	$myString = '';
	foreach($array as $key => $value) {
		$label = ((!$type) ? '<span class="inline_title">' . $key . ':</span>' : '');
		$val = ((!ValidateEmailAddress($value)) ? $value : '<a href="mailto:' . $value . '"><span>' . $value . '</span></a>');
		$myString .= $label . ' ' . $val;
	}
	return $myString;
}

/* removeEmpty removes any empty-string key=>value from array */
function OrderArrayForTableDisplay($array, $removeEmpty = FALSE) {
		/* 
		|  We need to reorder our content so we can lay it out in order to view it in a table correctly 
		|  Create three emty arrays to hold data. 
			@param => $headers = table headers
			@param => $contents = table cells
			@param => $tableorder = both the above arrays rejoined back into one array.
		*/
		
		// Create some empty arrays to hold our data in order.
		$headers = array();
		$contents = array();
		//this array collects the data in order
		$tableorder = array();
		
		//looped through the array passed and push the keys and values into seperate arrays.
		foreach($array as $key => $value) {
			if (!($removeEmpty && $value == '')) {
				array_push($headers,$key);
				array_push($contents,$value);
			}
		}
		
		//push the headers to the order bank.
		foreach($headers as $header) {
			array_push($tableorder,$header);
		}
		
		//push the contents to the order bank.
		foreach($contents as $content) {
			array_push($tableorder,$content);	
		}
		
		/* Create a table out of the array returned from the parser */
		$table = FALSE;
		$table .= '<table class="tableStatic" cellspacing="0" cellpadding="0">';
		$table .= '<thead>';
		$table .= '	<tr>';
		
		foreach($headers as $header) {
			$table .= '<td style="text-transform:capitalize;width:22%;text-align:left;padding-left:10px;font-weight:bold;color:#b55d5c">' . $header . '</td>';	
		}
		$table .= '<td>&nbsp;</td>';
		$table .= '	</tr>';
		$table .= '</thead>';
		$table .= '<tbody>';
		$table .= '<tr>';
		
		foreach($contents as $content) {
			$table .= '<td style="width:22%;">' . $content . '</td>';
		}
		$table .= '<td>&nbsp;</td>';
		$table .= '</tr>';
		$table .= '</tbody>';
		$table .= '</table>';
		
		return $table;
}

function ModulesToEvenlyDesignedTable($mods) {
	$table = '<table class="tableStatic" cellpadding="0" cellspacing="0" width="100%" style="margin-top:-1px;">';
			$cols = 7;
			$i = 1;
			$count = 0;
			foreach($mods as $module) {
				// start the table row;
				if($i == 1) {$table .= '<tr>';}
				//if the module is active show it, if not dont
				if($module->MODULE_Active == 1 AND strtotime($module->MODULE_Created) <= strtotime(date('Y-m-d H:i:s'))) {
					// the table will always have 7 columns, even if the modules only have one value
					$table .= '<td>' . $module->MODULE_Title . '</td>';
					// increment the count so we know when to start a new row
					$i++;
					$count++;
					if($count == count($mods)) {
						if($i < $cols) {
							$cellAdd = $cols - $i;
							for($c = 0; $c < $cellAdd; $c++) {
								$table .= '<td>&nbsp;</td>';	
							}
						}
					}
				}
				//end the row and start the count over to start a new row
				if($i == $cols) {
					$table .= '</tr>';
					$i = 1;
				}
				
			} 
	$table .= '</table>';
	echo $table;
}

function ValidateEmailAddress($str) {
	if(filter_var($str, FILTER_VALIDATE_EMAIL)) {
		return TRUE;
	}
	return FALSE;
}

function LinkPhoneNumber($num) {
	return '<a href="tel:' . $num . '">' . $num . '</a>';
}

function ValidatePhoneNumber($num) {
	if (preg_match('/^\(\d{3}\) \d{3}-\d{4}\$/', $num)) {	
		return TRUE;
	}
	
	return FALSE;
}

function ParseModulesInReadableArray($modString) {
	$ci =& get_instance();
	$ci->load->model('members');
	$mods = $ci->members->UserModules(mod_parser($modString));
	
	$modules = array();
		
	foreach($mods as $module) {
		if($module->MODULE_Active) {
			array_push($modules,$module);	
		}
	}
		
	return $modules;		

}

function getLiveChangesCount() {
	$ci =& get_instance();
	$ci->load->model('release_model','beta');
	$c = $ci->beta->get_changes_count();
	if($c) : 
		return count($c);
	else : 
		return FALSE;
	endif;
}

function WebsiteListingTable($cid = false,$actions=true,$isVendor=false) {
	if(!$cid) {$cid = $ci->user['DropdownDefault']->SelectedClient;}
	$ci =& get_instance();
	$ci->load->model('administration');
	
	$websites = (!$isVendor) ? $ci->administration->getClientWebsites($cid) : $ci->administration->getVendorWebsites($cid);
	
	if($websites) { ?>
    <table cellpadding="0" cellspacing="0" border="0" class="tableStatic" style="width:100%;border:1px solid #d5d5d5;" id="website_popup_example">
    	<thead>
        	<tr>
            	<td>Vendor</td>
                <td>Web URL</td>
                <td>Notes</td>
                <?php if($actions) { ?>
                <td>Actions</td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        	<?php foreach($websites as $website) : ?>
            	<tr>
                	<td style="white-space:nowrap;"><?= $website->VendorName; ?></td>
                    <td style="white-space:nowrap;"><a style="color:blue;" href="<?= $website->URL; ?>" target="_blank"><?= $website->URL; ?></a></td>
                    <td class="descCell"><p><?= $website->Description; ?></p></td>
                    <?php if($actions) { ?>
                    	<td style="text-align:center;"><a href="javascript:editWebsiteForm('<?= $cid ?>','<?= $website->ID; ?>');"><img src="<?= base_url() . THEMEIMGS;?>icons/color/pencil.png" alt="Edit Website" /></a></td>
                    <?php } ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<?php }else { ?>
		<p>No websites found for this client.</p>
	<?php }
}

function load_client_websites($cid = false, $actions = true,$isVendor = false) {
	if(!$cid) {$cid = $ci->user['DropdownDefault']->SelectedClient;}
	
	$ci =& get_instance();
	$ci->load->model('administration');
	
	$websites = (!$isVendor) ? $ci->administration->getClientWebsites($cid) : $ci->administration->getVendorWebsites($cid);
	$html = '';
	$table = '';
	if($websites) {
		$table .= '<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" id="example" width="100%" style="border:1px solid #d5d5d5">';
		$table .= '<thead><tr><td>Vendor</td><td>Web URL</td><td>Notes</td>' . (($actions) ? '<td class="actions">Actions</td>' : '') . '</tr></thead>';
		$table .= '<tbody>';
		foreach($websites as $website) :
			$edit_img = '<a href="javascript:editWebsiteForm(\'' . $cid . '\',\'' . $website->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/color/pencil.png" alt="Edit Website" /></a>';
			$table .= '<tr>';
			$table .= '<td><p>' . $website->VendorName . '</p></td>';
			$table .= '<td><a href="' . $website->URL . '" target="_blank">' . $website->URL . '</a></td>';
			$table .= '<td class="descCell"><p id="web_' . $website->ID . '">' . $website->Description . '</p></td>';
			if($actions) {
				$table .= '<td style="text-align:center;">' . $edit_img . '</td>';
			}
			$table .= '</tr>';
		endforeach;
		$table .= '</tbody></table>';
		                                    
		$html = $table;
	}else {
		$html .= '<p>No websites found for this client.' . (($actions) ? ' You can add one by clicking the add website button below.' : '') . '</p>';
	}
	return $html;
}

function load_client_contacts($cid) {
	$ci =& get_instance();
	$ci->load->helper('string_parser');
	$ci->load->model('administration');
	$contacts = $ci->administration->getContacts($cid);
	$html = '';
	$table = '';
	if($contacts) {
		$table .= '<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" id="example" width="100%" style="border:1px solid #d5d5d5">';
		$table .= '<thead><tr><td>Title</td><td>Name</td><td>Email Address</td><td>Phone</td></tr></thead>';
		$table .= '<tbody>';
		foreach($contacts as $contact) {
			$contact->Email = mod_parser($contact->Email, 'home,work');
			$contact->Address 	= mod_parser($contact->Address);
			$contact->Phone 	= mod_parser($contact->Phone, 'main,mobile,fax');
			$table .= '<tr>';
				$table .= '<td>' . $contact->JobTitle . '</td>';
				$table .= '<td>' . $contact->FirstName . ' ' . $contact->LastName . '</td>';
				$table .= '<td>' . '<span style="font-weight:bold;">Personal Email</span><br /><a href="mailto:' . $contact->Email["home"] . '">' . $contact->Email['home'] . '</a></span>' . ((isset($contact->Email['work'])) ? '<br /><span style="font-weight:bold;">Work Email</span><br /><a href="mailto:' . $contact->Email["work"] . '">' . $contact->Email['work'] . '</a></span>' : '') . '</td>';
				$table .= '<td>'  . '<span style="font-weight:bold;">Direct</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['main'] . '">' . $contact->Phone['main'] . '</a></span>' . ((isset($contact->Phone['mobile'])) ? '<br /><span style="font-weight:bold;">Mobile</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['mobile'] . '">' . $contact->Phone['mobile'] . '</a></span>' : '') . ((isset($contact->Phone['fax'])) ? '<br /><span style="font-weight:bold;">Fax</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['fax'] . '">' . $contact->Phone['fax'] . '</a></span>' : '') . '<td>';
			$table .= '</tr>';	
		}
		$table .= '</tbody></table>';
	}else {
		$html .= '<p>No contacts found for this client.</p>';	
	}
	
	$html .= $table;
	
	return $html;
}

function load_client_related_users($cid) {
	$ci =& get_instance();
	$ci->load->helper('string_parser');
	$ci->load->model('administration');
	$ci->load->model('members');
	$users = $ci->administration->getUsersOfClient($cid);
	$html = '';
	$table = '';
	if($users) {
		$table .= '<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" id="example" width="100%" style="border:1px solid #d5d5d5">';
		$table .= '<thead><tr><td>Avatar</td><td>Username</td><td>Name</td><td>Member Since</td></tr></thead>';
		$table .= '<tbody>';
		foreach($users as $user) {
			$avatar = $ci->members->get_user_avatar($user->ID);
			$table .= '<tr>';
			$table .= '<td>' . $avatar . '</td>';
			$table .= '<td>' . $user->Username . '</td>';
			$table .= '<td>' . $user->FirstName . ' ' . $user->LastName . '</td>';
			$table .= '<td>' . $user->MemberSince . '</td>';
			$table .= '</tr>';	
		}
		$table .= '</tbody></table>';
	}else {
		$html .= '<p>No users found for this client.</p>';	
	}
	
	$html .= $table;
	return $html;
}

function breadcrumb($replacement = false) {
	//create a empty var to hold the breakcrumb html
	$link = '';
	
	//get codeigniter instance so we can use its features
    $ci =& get_instance();
		
	//grab the url with the url helper
    $url = $ci->uri->uri_string();
	$uri = explode('/', $url);
	
	$i=0;
	$link .= '<li class="firstB"><a href="' . base_url() . '">Home</a></li>';
	$prep_link = '';
		
	//loop through array and create the breadcrumb elements
	foreach($uri as $section) {
		if($section == '') {
			unset($uri[$i]);	
		}
		
		if($i != 0) $prep_link .= $uri[$i] . '/';	

		if($section != '') {
			if($i >= count($uri)) {
				$link .= '<li class="lastB" style="text-transform:capitalize">'. $section . '</li>';
			}else {
				if($section != 'profile') {
					$link .= '<li style="text-transform:capitalize"><a href="' . $prep_link . '">' . $section . '</a></li>';
				}elseif($secion == 'clients') {
					$link .= '<li style="text-transform:capitalize"><a href="' . base_url() . 'admin">Admin</a></li><li style="text-transform:capitalize"><a href="' . base_url() . 'clients">Clients</a></li>';	
				}else {
					$link .= '<li style="text-transform:capitalize"><a href="' . base_url() . 'users">Users</a></li>';	
				}
			}
		}
		$i++;
		/*for($j = 0; $j <= $i; $j++) {
			$prep_link .= $uri[$j] . '/';	
		}
		
		if(count($uri - 1) > $i) {
			$link = '<li><a href="' . site_url($prep_link) . '">' . $section . '</a></li>';
		}else {
			$link = '<li class="lastB">' . ($replacement) ? $replacement : $section . '</li>';	
		}
		
		$i++;*/
	}
	
	return $link;
}

function showStates($selected = '',$disabled=false) {
    $ci =& get_instance();
    $ci->load->model('utilities');
    $states = $ci->utilities->getStates();
	if(!$disabled) {
    	$options = '<select data-placeholder="Choose a State..." class="chzn-select" style="width:350px;" name="state">';
	}else {
    	$options = '<select data-placeholder="Choose a State..." class="chzn-select" style="width:350px;" name="state" disabled>';
	}
	$options .= '<option value=""></option>';
    foreach ($states as $state) {
        $options .= '<option value="' . $state->Abbrev . '"' .(($selected == $state->Abbrev) ? ' selected' : '') . '>' . $state->Name . '</option>';
    }
    $options .= '</select>';

    return $options;
}
function popUpStates($selected = false) {
    $ci =& get_instance();
    $ci->load->model('utilities');
    $states = $ci->utilities->getStates();

    $options = '<div style="text-align:left;" class="noSearch"><select data-placeholder="Choose a State..." class="chzn-select" style="text-align:left;float:left;" name="state">';
	$options .= '<option value=""></option>';
    foreach ($states as $state) {
        $options .= '<option ' . (($selected AND $selected == $state->Abbrev) ? 'selected="selected"' : '') . ' value="' . $state->Abbrev . '">' . $state->Name . '</option>';
    }
    $options .= '</select></div>';

    return $options;
}


function dealer_selector() {
	$ci =& get_instance();
	$ci->load->model('utilities');
	$dropdown = $ci->utilities->clientList();
	return $dropdown;	
}

function levelSelect() {
    $ci =& get_instance();
    $ci->load->library('dropdowngen');
    $ci->load->helper('string_parser');
    $dropdown = $ci->dropdowngen->drivedrop();

    $options = '<select id="levelSelector" data-placeholder="Select a Level" class="chzn-select" style="width:300px;">' . dropdown_parser($dropdown) . '</select>';
    $options .= '<script type="text/javascript">
                    jQuery("#levelSelector option").each(function() {
                        var option = jQuery(this);
                        if(option.prev().attr("data-level") == option.attr("data-level")) {
                            if(!option.hasClass("agency")) {
                                option.prev().remove();
                                option.removeClass("double-indent").addClass("single-indent");
                            }
                        }
                    });
                </script>';
    return $options;

}

function permission_selector() {
    $ci =& get_instance();
    $ci->load->model('administration');
    $permissions = $ci->administration->getPermissionsList($ci->user['AccessLevel']);

    $options = '';
    $options .= '<select id="perms" placeholder="User Permissions" class="chzn-select validate[required]" style="width:30%;" name="user_level" tabindex="10">';
    foreach ($permissions as $permission) {
        $options .= '<option value="' . $permission->ID . '" data-access-level="' . $permission->Level . '" data-modules="' . $permission->Modules . '">' . $permission->Name . '</option>';
    }
    $options .= '</select>';
    return $options;
}

function member_of_selector() {
    $ci = & get_instance();
    $ci->load->library('dropdowngen');
    $ci->load->helper('string_parser');
    $dropdown = $ci->dropdowngen->drivedrop();

    $options = '<select id="member_of" data-placeholder="Member Of Dropdown" class="chzn-select" style="width:30%" name="member_of">' . dropdown_parser($dropdown) . '</select>';
    return $options;
}

function tag_selector() {
    $ci = & get_instance();
    $ci->load->library('tagdropgen');
    //print_r(client_tag_parser($ci->tagdropgen->drivetagdrop()));
    $ValidUser = $ci->session->userdata('valid_user');
    $DropdownDefault = $ValidUser['DropdownDefault'];
    $tag_id = $DropdownDefault->SelectedTag;
    return client_tag_parser($ci->tagdropgen->drivetagdrop(), $tag_id);
}

function get_client_type() {
    $ci = & get_instance();
    $level_type = $ci->session->userdata['valid_user']['DropdownDefault']->LevelType;
    //get client type from session
    if ($level_type == 'a' OR $level_type == 1) :
        $name = 'Agency Name:';
    elseif ($level_type == 'g' OR $level_type == 2) :
        $name = 'Group Name:';
    elseif ($level_type == 'c' OR $level_type == 3) :
        $name = 'Client Name:';
    else :
        $name = '';
    endif;

    return $name;
}

function get_client_name() {
    $ci = & get_instance();
    $ci->load->model('dropdown');
    $dropdown = $ci->session->userdata['valid_user']['DropdownDefault'];
    //print_object($dropdown);
    $level_type = $dropdown->LevelType;
    $level_id = $dropdown->LevelID;
    if ($level_type == 1 OR $level_type == 'a') :
        $results = $ci->dropdown->AgenciesQuery($level_id, true);
        $name = $results->AGENCY_Name;
    elseif ($level_type == 2 OR $level_type == 'g') :
        $results = $ci->dropdown->GroupsQuery($level_id, false, true);
        $name = $results->GROUP_Name;
    elseif ($level_type == 3 OR $level_type == 'c') :
        $results = $ci->dropdown->ClientQuery($level_id, false, true);
        $name = ((isset($results->CLIENT_Name)) ? $results->CLIENT_Name : '');
    else :
        $results = NULL;
        $name = '';
    endif;
    return $name;
}

function get_user_modules($level) {
   switch($level) {
       case 1 :
           return '1:1,2:1,3:1,4:1,5:1,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 2 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 3 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 4 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 5 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       default:
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   }
}