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

function PasswordlistTable() { ?>
	<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/passwords_popups.js"></script>
    <?php
	
		$ci =& get_instance();
		$userPermissionLevel = $ci->user['AccessLevel'];
		$addPriv = GateKeeper('Passwords_Add',$userPermissionLevel);
		$editPriv = GateKeeper('Passwords_Edit',$userPermissionLevel);
		$disablePriv = GateKeeper('Passwords_Disable_Enable',$userPermissionLevel);
		$listingPriv = GateKeeper('Passwords_List',$userPermissionLevel);
		
		//load the queries
		$ci->load->model('administration');
		$passwords = $ci->administration->getPasswords($ci->user['DropdownDefault']->SelectedClient);
		$counter=0;
		
		if($addPriv) : ?>
        	<a href="javascript:addPasswords('<?= base_url(); ?>')" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px">Add New Password</a>
        <?php endif; 
        
        if($passwords AND $listingPriv) :  ?>
        	<style type="text/css">
				table.passwordsListTable tr td{text-align:left;}
				.align-cell-left{text-align:left;}
				.no-text-wrap{white-space:nowrap}
				.boldTheText{font-weight:bold;}
				.clipData{width:22px;height:22px;float:left;cursor:pointer;background:url(<?=base_url() . THEMEIMGS; ?>icons/dark/clipboard.png) no-repeat;}
				.passwordNotes{overflow:hidden; max-height:22px;float:left;width:80%;}
				.passwordNotesMore{cursor:pointer;color:blue;top:0;}
				.notesCol{width:25%;}
				.actionsCol{width:32px !important;text-align:center !important;}
			</style>
        	<table cellpadding="0" cellspacing="0" border="0" class="display passwordsListTable" id="example" width="100%">
            	<thead>
                	<tr>
                    	<th>Team</th>
                        <th>Type</th>
                        <th>Vendor</th>
                        <th>Login Address</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Notes</th>
                        <?php if($editPriv) { ?>
                        	<th class="noSort">Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach($passwords as $password) : ?>
                    	<?php $clipData = 'Login Address,' . $password->LoginAddress . ',User Name,' . $password->Username . ',Password,' . $password->Password; ?>
                		<tr class="tagElement <?= $password->Tag; ?>">
                        	<td class="tags"><div class="<?= $password->Tag; ?>">&nbsp;<span style="display:none;"><?= $password->Tag;?></span></div></td>
                            <td class="align-cell-left"><?= $password->Type; ?></td>
                            <td class="align-cell-left"><?= $password->Vendor; ?></td>
                            <td><a target="_blank" href="<?= $password->LoginAddress; ?>"><?= $password->LoginAddress; ?></a></td>
                            <td class="no-text-wrap">
                            	<span class="boldTheText">
                                	<div id="username<?=$counter;?>" class="clipBoard clipData" clipBoardData="<?=$clipData; ?>"></div>
                                    <div style="float:left;"><a href="mailto:<?=$password->Username;?>"><?=$password->Username;?></div>
                                </span>
                            </td>
                            <td class="no-text-wrap">
                            	<div id="password<?=$counter;?>" class="clipBoard clipData" clipBoardData="<?=$clipData;?>"></div>
                                <div style="float:left;"><?=$password->Password;?></div>
                            </td>
                            <td class="notesCol">
                            	<?php if($password->Notes) { ?>
                            	<div class="passwordNotes">
									<?=$password->Notes;?>
                                </div>
								<a href="javascript:openMore('<?=$password->Notes;?>')" class="passwordNotesMore">...more</a>  
                                <?php }else { ?>
                                <span>...</span>
                                <?php } ?>                          
                            </td>
                            <?php if($editPriv) { ?>
                            	<td class="actionsCol">
                                	<a title="Edit Password" href="javascript:editPasswords('<?=$password->ID;?>');" class="actions_link">
                                    	<img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" />
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
						<?php $counter++; 
					endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
        	<p style="padding-left:10px;padding-bottom:10px;">Sorry, no passwords were found for this client. Please add a password or select a different Client.</p>
        <?php endif; 
        
        if($addPriv) : ?>
        	<a href="javascript:addPasswords('<?= base_url(); ?>')" class="greenBtn floatRight button" style="margin-top:10px;">Add New Password</a>
        <?php endif; ?>
        
        <?php if($passwords) { ?>
            <script type="text/javascript">
			
				var $ = jQuery;
			
				$(".clipBoard").click(function() {
					// Arguments need to be in the order of:
					//  Login Address Label, Login Address data,
					//  User Name label, User Name data,
					//  Password label, Password data
					var data = $(this).attr("clipBoardData");
					clipboardCopyDialog(data);
				});
				
				// Creates a clipboard-copy dialog.
				// The textList is an associative array:
				//   textLabel = textDescriptionToCopy
				function clipboardCopyDialog(textList) {
					var htmlHead = '<div class="uDialog" title="Copy" style="text-align:left;"><div class="dialog-message" id="copy" title="Copy"><div class="uiForm"><style type="text/css">label{margin-top:5px;float:left;}</style><div class="widget" style="margin-top:0;padding-top:0;"><fieldset>';
					
					var htmlBody = '';
					var args = textList.split(",");
					for (var i = 0; i < args.length; i = i + 2) {
						htmlBody += '<div class="rowElem noborder"><label style="white-space:nowrap">' + args[i] + '</label><div class="formRight"><input type="text" class="clipBoard" value="' + args[i+1] + '" readonly><label style="font-size:75%;color:grey;margin:0">Click on box and press control+c to copy</label></div></div>';
					}
					
					var htmlFoot = '</fieldset></div></div></div></div>';
					
					// We use <\/script> because some browsers have issues parsing this, even in a static string.
					var scripts = $(".clipBoard").click(function() {$(this).select();});
					
					var dialogHtml = $(htmlHead + htmlBody + htmlFoot + scripts);
					// This syntax manually appends the script to the dialog window.
					// On some versions of dialog, there is a bug which will open a seperate window for
					//   the script fragment.
					dialogHtml.filter("div").dialog({width:500}).end().filter("script").appendTo("body");
				}
				
				function openMore(text) {
					var dialogHtml = $('<div id="notesDialog" title="Note"><p>' + text + '</p></div>');
					dialogHtml.dialog();
				}
			</script>
		<?php } ?>
<?php }

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
                        <th class="doclist noSort">DOC</th>
                        <th class="excellist noSort">XSL</th>
                        <?php if($editPriv) { ?>
                            <th class="noSort" style="width:30px;">Actions</th>
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
                                <td class="actionsCol noSort" style="text-align:center !important;">
                                    <a title="Edit Client" href="javascript:editEntry('<?= $client->ClientID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php   }else { ?>
            <p style="padding-left:10px;padding-bottom:10px;">You don't have access to view this data. Sorry.</p>
<?php   }
        if($addPriv) { ?>
            <!-- <a href="javascript:addMasterlist();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Entry</a> -->
<?php 	}
}

function AgencyListingTable() { 
	$ci =& get_instance();
	$ci->load->model('administration');
	$agency_id = $ci->user['DropdownDefault']->SelectedAgency;
	$agencies = $ci->administration->getAgencies();	
?>
	<?php if($agencies) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/masterlist_popups.js"></script>
    <?php 
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
                    <th class="noSort">Actions</th>
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
                            <a title="Edit Agency" href="javascript:editAgency('<?= $agency->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addAgency();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Agency</a><?php } ?>
    <?php else : ?>
    <p style="padding-left:10px;padding-bottom:10px;">No agencies found.</p>
    <?php endif; ?>
<?php }

function GroupsListingTable() { 
		$ci =& get_instance();
		$ci->load->model('administration');
		
		//the dropdown level, agency, groups or clients
		$level = $ci->user['DropdownDefault']->LevelType;
		//the selected agency id
		$agency_id = $ci->user['DropdownDefault']->SelectedAgency;
		
		//if we are on agency level, get all groups in the agency
		if($level == 1 OR $level == 'a') {
			$groups = $ci->administration->getGroups($agency_id);
		}else {
			//else, we just need to get the selected group
			$groups = $ci->administration->getGroups($agency_id,$ci->user['DropdownDefault']->SelectedGroup);
		}

?>
	<?php if($groups) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/group_popups.js"></script>
    <?php 
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
                    <th class="noSort">Actions</th>
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
    <p style="padding-left:10px;padding-bottom:10px;">No groups found.</p>
    <?php endif; ?>
<?php }

function VendorListingTable($hide_actions=false,$hide_add=false) { ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/vendor_popups.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/websites_popups.js"></script>
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
                                    <th class="noSort" style="white-space:nowrap;">Actions</th>
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
                <p style="padding-left:10px;padding-bottom:10px;">No Vendors found.</p>
			<?php } ?>
    <?php }else { ?>
    	<p style="padding-left:10px;padding-bottom:10px;">You dont have access to view vendors.</p>
    <?php } ?>
    <?php if($addPriv) { ?><?php if(!$hide_add OR !$hide_actions) { ?><a href="javascript:addVendor();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Vendor</a><?php } ?><?php } ?>
<?php }


function ContactsListingTable($id = false,$hide_add = false,$hide_actions = false,$from_tab = false,$type = false) { ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/contact_popups.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/websites_popups.js"></script>
    <?php 
		$ci =& get_instance();
		$level				 = $ci->user['DropdownDefault']->LevelType;
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Contact_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Contact_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Contact_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Contact_List',$userPermissionLevel);
		
		// $type with $id, $id refers to $type
		if ($type) {
			if ($id)
				$contacts = $ci->administration->getContactsByTypeID($type,$id);
			else
				$contacts = $ci->administration->getContactsByType($type);
		// $id without type, $id refers to directory ID.
		} elseif ($id) {
			$contacts = $ci->administration->getContact($id);
		} else {
			// We need to get listing from DropDown selection.
			switch ($level) {
				case 'a': $contacts = $ci->administration->getAllContactsInAgency($ci->user['DropdownDefault']->SelectedAgency);
				break;
				case 'g': $contacts = $ci->administration->getAllContactsInGroup($ci->user['DropdownDefault']->SelectedGroup);
				break;
				case 'c': $contacts = $ci->administration->getAllContactsInClient($ci->user['DropdownDefault']->SelectedClient);
				break;
			}
		}

    ?>
    <?php if($addPriv) { ?><a href="javascript:addContact();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Contact</a><?php } ?>
    <?php if($listingPriv) { ?>
    	<?php if($contacts) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="<?php echo ($from_tab) ? 'tableStatic' : 'display contacts'; ?>" id="<?php echo ($from_tab) ? 'contacts' : 'example'; ?>" width="100%;">
            <thead>
                <tr>
                    <?php if(!$from_tab) { ?><th style="text-align:left;padding-left:1em;">Team</th><?php } ?>
                    <?php if(!$from_tab) { ?><th style="text-align:left;padding-left:1em;">Type</th><?php } ?>
                    <?php if($level == 'g' || $level == 'a') { ?>
                    <?php if(!$from_tab) { ?><th style="text-align:left;padding-left:1em;">Client/Vendor Name</th><?php } ?>
                    <?php } ?>
                    <th style="text-align:left;padding-left:1em;">Title Name</th>
                    <th style="text-align:left;padding-left:1em;">Contact Name</th>
                    <th style="text-align:left;padding-left:1em;"><?php if($from_tab) { echo ''; } ?> Primary Email</th>
                    <th style="text-align:left;padding-left:1em;"><?php if($from_tab) { echo ''; } ?> Primary Phone</th>
                    <?php if($editPriv) { ?>
                    	<?php if(!$hide_actions) { ?>
                    		<th class="noSort" style="text-align:left;padding-left:1em;">Actions</th>
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
						$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone,false,true) : new stdClass();
						// Locate primary.
						$contact->PrimaryPhone = '';
						foreach ($contact->Phone as $contactPhone) foreach ($contactPhone as $type => $phone) {
							if ($phone == $contact->PrimaryPhoneType) {
								$contact->PrimaryPhone = $phone;
								break;
							}
						}
						$contact->Email = (isset($contact->Email)) ? mod_parser($contact->Email,false,true) : new stdClass();
						// Locate primary.
						$contact->PrimaryEmail = '';
						foreach ($contact->Email as $contactEmail) foreach ($contactEmail as $type => $email) {
							if ($email == $contact->PrimaryEmailType) {
								$contact->PrimaryEmail = $email;
								break;
							}
						}
						$contact->Parent = $contact->Dealership;
						$contact->TypeCode = substr($contact->Type,0,3);
						$contact->TypeID = substr($contact->Type,4);
					?>

                
                    <tr class="tagElement <?php echo $contact->Tag; ?>" >
                    	<?php if(!$from_tab) { ?><td class="tags"><div class="<?php echo $contact->Tag; ?>">&nbsp;</div></td><?php } ?>
                        <?php if(!$from_tab) { ?><td><?php switch($contact->TypeCode) {
							case 'CID': echo 'Client'; break;
							case 'VID': echo 'Vendor'; break;
							case 'GID': echo 'General'; break;
							case 'UID': echo 'User';
						} ?></td><?php } ?>
                        <?php if($level == 'g' || $level == 'a') { ?>
                        <?php if(!$from_tab) { ?><td style="width:auto;white-space:no-wrap;text-align:left;white-space:nowrap;"><?php echo $contact->Parent; ?></td><?php } ?>
                        <?php } ?>
                        <td style="text-align:left;white-space:nowrap;"><?= $contact->JobTitle; ?></td>
                        <td><?= $contact->Name; ?></td>
                        <td>
                        	<?php
								echo '<span style="white-space:nowrap;"><a href="mailto:'.$contact->PrimaryEmail.'">'.$contact->PrimaryEmail.'</a></span>';
							?>
                        </td>
                        <td>
							<?php
								echo '<span style="white-space:nowrap;"><a href="tel:'.$contact->PrimaryPhone.'">'.$contact->PrimaryPhone.'</a></span>';
                            ?>
                        </td>
                        <?php if($editPriv) { ?>
                        	<?php if(!$hide_actions) { ?>
                                <td class="actionsCol" style=" <?php if(!$from_tab) { ?>width:75px;<?php }else {?>width:30px;<?php } ?>text-align:center;">
                                    <a title="Edit Contact" href="javascript:editContact('<?= $contact->TypeID; ?>','<?= $contact->TypeCode; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                    <?php if(!$from_tab) { ?><a title="View Contact" href="javascript:viewContact('<?= $contact->TypeID; ?>','<?= $contact->TypeCode; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a><?php } ?>
                                </td>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php }else { ?>
       <p style="padding-left:10px;padding-bottom:10px;">No Contacts have been added.</p>
	<? }?>
    <?php if($addPriv) { ?>
    	<?php if(!$hide_add) { ?>
    	<a href="javascript:addContact();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Contact</a>
        <?php } ?>
	<?php } ?>
    <?php }else { ?>
        <p style="padding-left:10px;padding-bottom:10px;">No contacts found.</p>
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
	<p style="padding-left:10px;padding-bottom:10px;">No clients have been added to this group.</p>
<? } }

function ClientsListingTable() { 
	$ci =& get_instance();
	$ci->load->model('administration');
	$clients = $ci->administration->getClientsByDDLevel();
?>
	<?php if($clients) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/client_popups.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/websites_popups.js"></script>
    <?php 
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
                    <th class="noSort">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $client) { ?>
                    <tr class="tagElement <?= $client->ClassName; ?> ">
                    	<td class="tags"><div class="<?= $client->ClassName; ?>">&nbsp;</div><span style="display:none;"><?= $client->ClassName; ?></span></td>
                        <td><?= $client->ClientCode; ?></td>
                        <td style="text-align:left;"><?= $client->Name; ?></td>
                        <td><?= $client->GroupName; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($client->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol noSort" style="width:60px;text-align:center;">
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
    <p style="padding-left:10px;padding-bottom:10px;">No clients found.</p>
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
		
    ?>
    <?php if($addPriv) { ?><a href="javascript:addUser();" class="greenBtn floatRight button" style="margin-top:-74px;margin-right:3px;">Add New User</a><?php } ?>
    <?php if($users AND $listingPriv) { ?>
    	<?php if(isset($error_msg)) { ?>
			<div class="nNote nError hideit">
				<p><strong>Error</strong><?= $error_msg; ?></p>
			</div>
		<?php } ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center;">Team</th>
                    <th style="text-align:center;width:50px;">Avatar</th>
                    <th style="text-align:left;width:30%;">Email Address</th>
                    <th style="text-align:left;">Name</th>
                    <th>Status</th>
                    <?php if($editPriv) { ?>
                    	<th class="noSort">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user) { $avatar = $ci->members->get_user_avatar($user->ID); ?>
                    <tr class="tagElement <?= $user->ClassName; ?>">
                    	<td class="tags" style="vertical-align: middle;"><div class="<?= $user->ClassName; ?>">&nbsp;</div></td>
                        <td style="text-align:center;vertical-align: middle;"><div style="text-align:center;margin-top:5px;"><img src="<?= $avatar; ?>" style="width:30px;" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" /></div></td>
                        <td style="text-align:left;vertical-align: middle;"><a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></td>
                        <td style="vertical-align:middle;"><?= $user->FirstName . ' ' . $user->LastName; ?></td>
                        <td style="width:30px;text-align:center;vertical-align: middle;"><?= (($user->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol noSort" style="width:60px;text-align:center;vertical-align: middle;">
                            <a title="Edit Client" href="javascript:editUser('<?= $user->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Client" href="javascript:viewUser('<?= $user->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if(isset($user_id)) { echo $user_id; ?>
			<script type="text/javascript">
            	editUser('<?= $user_id; ?>');
            </script>
        <? } ?>
    <?php }else { ?>
    	<p style="padding-left:10px;padding-bottom:10px;">No Users found. Please select a different Client or add a user by selecting the "Add New User" button below.</p>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addUser();" class="greenBtn floatRight button" style="margin-top:10px;">Add New User</a><?php } ?>
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

// Converts an array into a table, with num_columns.
function ArrayToTable($array, $num_columns) {
	$table = '<table class="tableStatic" cellspacing="0" cellpadding="0"><tr>';
	
	$col = 1;
	foreach ($array as $cell) {
		$table .= '<td><a href="'.$cell.'" target="_blank">'.$cell.'</a></td>';
		$col++;
		if ($col > $num_columns) {
			$table .= '</tr><tr>';
			$col = 1;
		}
	}
	$table .= '</table>';
	
	return $table;
}

/* removeEmpty removes any empty-string key=>value from array */
/* deepArray is for arrays where the key->value pair is set one array level */
/*   deeper, for purposes of allowing duplicate keys. */
function OrderArrayForTableDisplay($array, $removeEmpty = FALSE, $deepArray = FALSE) {
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
		if (!$deepArray) {
			foreach($array as $key => $value) {
				if (!($removeEmpty && $value == '')) {
					array_push($headers,$key);
					array_push($contents,$value);
				}
			}
		} else {
			foreach($array as $arrayItem)
			foreach($arrayItem as $key => $value) {
				if (!($removeEmpty && $value == '')) {
					array_push($headers,$key);
					array_push($contents,$value);
				}
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
	$table = '';
	$cols = 4;
	$i = 0;
	$m = 1;
			
	foreach($mods as $module) {
		
		if($i >= $cols) {
			$i = 0;	
		}
		
		if($i == 0) {
			$table .= '<ul class="modulesTable">';
		}
		
		if($i <= $cols) {
			$table .= '<li><span class="label">' . $module->MODULE_Title . '</span></li>';
		}
		// increment the count so we know when to start a new row
		$m++;
		$i++;
		if($i == $cols) {
			$table .= '</ul>';	
		}
		
		
	} 
	echo $table;
}

function ModulesToEvenlyDesignedTableWithForm($mods,$user_id,$allMods) {
	$table = '';
	$cols = 4;
	$i = 0;
	$m = 1;
			
	foreach($allMods as $module) {
		
		if($i >= $cols) {
			$i = 0;	
		}
		
		if($i == 0) {
			$table .= '<ul class="modulesTable">';
		}
		
		if($i <= $cols) {
			$table .= '<li><span class="label">' . $module->MODULE_Title . '</span><span class="check"><input value="' . ((isset($mods[$m])) ? (($mods[$m]->MODULE_Active==1) ? '1' : '0') : '0') . '" type="hidden" name="modules[' . $module->MODULE_ID . ']" class="ms" /><input class="mod" name="mods[' . $module->MODULE_ID . ']" type="checkbox" value="' . ((isset($mods[$m])) ? (($mods[$m]->MODULE_Active==1) ? '1' : '0') : '0') . '" ' . ((isset($mods[$m])) ? (($mods[$m]->MODULE_Active==1) ? 'checked' : '') : '') . '/></span></li>';
		}
		// increment the count so we know when to start a new row
		$m++;
		$i++;
		if($i == $cols) {
			$table .= '</ul>';	
		}
		
		
	} 
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

function WebsiteListingTable($id = false, $type = false, $actions = true, $isVendor = false) {
	$ci =& get_instance();
	$ci->load->model('administration');
	if(!$id) {$id = $ci->user['DropdownDefault']->SelectedClient;}
		
	if ($type == 'CID') {
		$websites = $ci->administration->getClientWebsites($id);
		$name = 'client';
	}
	if ($type == 'GID') {
		$websites = $ci->administration->getContactWebsites($id);
		$name = 'contact';
	}
	if ($type == 'VID' || $isVendor) {
		$websites = $ci->administration->getVendorWebsites($id);
		$name = 'vendor';
	}
	if ($type == 'UID') {
		$websites = $ci->administration->getUserWebsites($id);
		$name = 'user';
	}
	
	$html = '';
	$table = '';
	if($websites) {
		$table .= '<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" id="example" width="100%" style="border:1px solid #d5d5d5">';
		$table .= '<thead><tr><td style="text-align:left;padding-left:1em;">Vendor</td><td style="text-align:left;padding-left:1em;">URL</td><td style="text-align:left;padding-left:1em;">Notes</td>' . (($actions) ? '<td class="noSort" style="text-align:left;padding-left:1em;">Actions</td>' : '') . '</tr></thead>';
		$table .= '<tbody>';
		foreach($websites as $website) :
			$edit_img = '<a href="javascript:editWebsiteForm(\'' . $id . '\',\'' . $type . '\',\'' . $website->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/color/pencil.png" alt="Edit Website" /></a>';
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
		$html .= '<p>No websites found for this '.$name.'.' . (($actions) ? ' You can add one by clicking the add website button below.' : '') . '</p>';
	}
	return $html;
}

function load_client_contacts($cid) {
	$ci =& get_instance();
	$ci->load->helper('string_parser');
	$ci->load->model('administration');
	$contacts = $ci->administration->getContactsByType($cid, 'CID');
	$html = '';
	$table = '';
	if($contacts) {
		$table .= '<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" id="example" width="100%" style="border:1px solid #d5d5d5">';
		$table .= '<thead><tr><td>Title</td><td>Name</td><td>Email Address</td><td>Phone</td></tr></thead>';
		$table .= '<tbody>';
		foreach($contacts as $contact) {
			$contact->Email = mod_parser($contact->Email, 'home,work', true);
			$contact->Address 	= mod_parser($contact->Address);
			$contact->Phone 	= mod_parser($contact->Phone, 'main,mobile,fax', true);
			$table .= '<tr>';
				$table .= '<td>' . $contact->JobTitle . '</td>';
				$table .= '<td>' . $contact->FirstName . ' ' . $contact->LastName . '</td>';
				$table .= '<td>';
				// Locate primary.
				foreach ($contact->Email as $contactEmail) foreach ($contactEmail as $type => $email) {
					if ($type == $contact->PrimaryEmailType) {
						$table .= '<span style="font-weight:bold;">Personal Email</span><br /><a href="mailto:' . $email . '">' . $email . '</a></span>';
						break;
					}
				}
				// Locate others.
				foreach ($contact->Email as $contactEmail) foreach ($contactEmail as $type => $email) {
					if ($type != $contact->PrimaryEmailType) {
						$table .= '<br /><span style="font-weight:bold;">'.$type.'</span><br /><a href="mailto:' . $email . '">' . $email . '</a></span>';
					}
				}
				$table .= '</td><td>';
				// Locate primary.
				foreach ($contact->Phone as $contactPhone) foreach ($contactPhone as $type => $phone) {
					if ($type == $contact->PrimaryPhoneType) {
						$table .= '<span style="font-weight:bold;">Direct</span><br /><a href="tel:' . $phone . '">' . $phone . '</a></span>';
						break;
					}
				}
				// Locate others.
				foreach ($contact->Phone as $contactPhone) foreach ($contactPhone as $type => $phone) {
					if ($type != $contact->PrimaryPhoneType) {
						$table .= '<br /><span style="font-weight:bold;">'.$type.'</span><br /><a href="tel:' . $phone . '">' . $phone . '</a></span>';
					}
				}
				$table .= '</td>';

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

function ContactInfoListingTable($contact, $type, $edit = false) {
	$ci =& get_instance();
	
	if ($edit) {
		return ContactInfoListingTable_EditAdd($contact, $type);
	} else {
		$fragment =
		'<style type="text/css">
			#contactInfo div.head {background:none;border:none;width:14em;margin:0 auto;}
		</style>
		<div style="margin-top:10px;margin-bottom:60px;">
			<div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
				<thead>
					<tr>
						<td width="10%" style="text-align:left;padding-left:10px;">Type</td>
						<td width="90%" colspan="2" style="text-align:left;padding-left:10px;">Phone Number</td>
					</tr>
				</thead>
				<tbody>';
					foreach ($contact->Phone as $contactPhone) foreach ($contactPhone as $type => $phone) {
					$fragment .= '<tr>
						<td width="10%">'.ucwords($type).'</td>
						<td width="80%">'.$phone.'</td>
						<td width="10%">'.((($contact->PrimaryPhoneType) == $phone) ? 'Primary' : '').'</td>
					</tr>';
					}
				$fragment .= '</tbody>
			</table>
		</div>';
	
		if (isset($contact->Email)) {
		$fragment .= '<div style="margin-top:10px;">
			<div class="head"><h5 class="iMail">Email Addresses</h5></div>
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
				<thead>
					<tr>
						<td width="10%" style="text-align:left;padding-left:10px;">Type</td>
						<td width="90%" colspan="2" style="text-align:left;padding-left:10px;">Email Address</td>
					</tr>
				</thead>
				<tbody>';
					foreach ($contact->Email as $contactEmail) foreach ($contactEmail as $type => $email) {
					$fragment .= '<tr>
						<td width="10%">'.ucwords($type).'</td>
						<td width="80%">'.$email.'</td>
						<td width="10%">'.((($contact->PrimaryEmailType) == $email) ? 'Primary' : '').'</td>
					</tr>';
					}
				$fragment .= '</tbody>
			</table>
		</div>';
		}
		
		return $fragment;
	}
}

function ContactInfoListingTable_EditAdd($contact, $type) {
	$ci =& get_instance();

	$fragment =
	'<style type="text/css">
		#contactInfo div.head {background:none;border:none;width:14em;margin:0 auto;}
	</style>';
	
	if (isset($contact->Phone)) {	
	$fragment .= '<div style="margin-top:10px;margin-bottom:60px;">
    	<div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
			<thead>
				<tr>
					<td width="10%" style="text-align:left;padding-left:10px;">Primary</td>
					<td width="80%" style="text-align:left;padding-left:10px;">Phone Number</td>
					<td width="10%" style="text-align:left;padding-left:10px;">Actions</td>
				</tr>
			</thead>
			<tbody>';
				if ($contact) foreach ($contact->Phone as $contactPhone) foreach ($contactPhone as $type => $phone) {
				$fragment .= '<tr>
					<td width="10%"><div style="width:20px;margin:0 auto;"><input type="radio" class="phonePrimary" name="phonePrimary" value="'.$phone.'" '.(($phone == $contact->PrimaryPhoneType) ? 'checked' : '').' /></div></td>
					<td width="80%">'.(($contact) ? $phone : '').'</td>
					<td width="10%"><div style="width:20px;margin:0 auto;"><a title="Edit Phone Number" href="javascript:editPhone(\''.$contact->ContactID.'\',\''.$type.'\',\''.$phone.'\');" class="actions_link"><img src="'.base_url().THEMEIMGS.'icons/color/pencil.png" alt="" /></a></div></td>
				</tr>';
				}
			$fragment .= '</tbody>
		</table>
		<a href="javascript:addPhone(\''.$contact->ContactID.'\',\''.$type.'\');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Phone</a>
	</div>';
	}
	
	if (isset($contact->Email)) {
	$fragment .= '<div style="margin-top:10px;">
		<div class="head"><h5 class="iMail">Email Addresses</h5></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
			<thead>
				<tr>
					<td width="10%" style="text-align:left;padding-left:10px;">Primary</td>
					<td width="80%" style="text-align:left;padding-left:10px;">Email Addresses</td>
					<td width="10%" style="text-align:left;padding-left:10px;">Actions</td>
				</tr>
			</thead>
			<tbody>';
				if ($contact) foreach ($contact->Email as $contactEmail) foreach ($contactEmail as $type => $email) {
				$fragment .= '<tr>
					<td width="10%"><div style="width:20px;margin:0 auto;"><input type="radio" class="emailPrimary" name="emailPrimary" value="'.$email.'" '.(($email == $contact->PrimaryEmailType) ? 'checked' : '').' /></div></td>
					<td width="80%">'.(($contact) ? $email : '').'</td>
					<td width="10%"><div style="width:20px;margin:0 auto;"><a title="Edit Email" href="javascript:editEmail(\''.$contact->ContactID.'\',\''.$type.'\',\''.$email.'\');" class="actions_link"><img src="'.base_url().THEMEIMGS.'icons/color/pencil.png" alt="" /></a></div></td>
				</tr>';
				}
			$fragment .= '</tbody>
		</table>
		<a href="javascript:addEmail(\''.$contact->ContactID.'\',\''.$type.'\');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Email</a>
	</div>';
	}

	return $fragment;
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
		
    
    //print_object($uri);
    if(isset($uri[1]) AND $uri[0] == 'profile') {
        
        unset($uri[1]);
        
    }
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
					$link .= '<li style="text-transform:capitalize"><a href="' . base_url() . '">' . $section . '</a></li>';
				}elseif($section == 'clients') {
					$link .= '<li style="text-transform:capitalize"><a href="' . base_url() . 'admin">Admin</a></li><li style="text-transform:capitalize"><a href="' . base_url() . 'clients">Clients</a></li>';	
				}else {
                    
					$link .= '<li style="text-transform:capitalize" class="lastB">User Preferences</li>';
                    
				}
			}
		}
		$i++;
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