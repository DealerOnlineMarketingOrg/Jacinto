<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
	 
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
		redirect('/','refresh');
	} else {
		if ($uPerm >= $perms->Level) {
			return TRUE;
		} else {
			redirect('/','refresh');
		}
	}
}

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

function load_client_websites($cid = false, $actions = true) {
	if(!$cid) {
		$cid = $ci->user['DropdownDefault']->SelectedClient;
	}
	$ci =& get_instance();
	$ci->load->model('administration');
	$websites = $ci->administration->getClientWebsites($cid);
	$html = '';
	$table = '';
	if($websites) {
		//print_object($websites);
		
		$table .= '<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" id="example" width="100%" style="border:1px solid #d5d5d5">';
		$table .= '<thead><tr><td>Vendor</td><td>Web URL</td><td>Notes</td>' . ($actions) ? '<td>Actions</td>' : '' . '</tr></thead>';
		$table .= '<tbody>';
		foreach($websites as $website) :
			$edit_img = '<a href="javascript:editWebsiteForm(\'' . $cid . '\',\'' . $website->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/dark/pencil.png" alt="Edit Website" /></a>';
			if($website->Status) {
				$status_img = '<a style="margin-left:5px;" title="Disable Website" href="javascript:disableWebsite(\'' . $website->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable Website" /></a>';	
			}else {
				$status_img = '<a style="margin-left:5px;" title="Enable Website" href="javascript:enableWebsite(\'' . $website->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/color/play.png" alt="Enable Website" /></a>';	
			}
			$table .= '<tr>';
			$table .= '<td><p>' . $website->VendorName . '</p></td>';
			$table .= '<td><a href="' . $website->URL . '" target="_blank">' . $website->URL . '</a></td>';
			$table .= '<td class="descCell"><p id="web_' . $website->ID . '">' . $website->Description . '</p></td>';
			if($actions) {
				$table .= '<td style="text-align:center;">' . $edit_img . $status_img . '</td>';
			}
			$table .= '</tr>';
		endforeach;
		$table .= '</tbody></table>';
		
		$html = $table;
		//print_object($html);
	}else {
		$html .= '<p>No websites found for this client. You can add one by clicking the add website button below.</p>';
	}
	
	if($ci->CheckModule('Website_Add') && $actions) {
		$html .= '<a href="javascript:addWebsiteForm(\'' . $cid . '\');" class="greenBtn floatRight button" style="margin-top:10px;margin-bottom:10px;">Add Website</a>';
		//$html .= anchor('javascript:addWebsite(\'' . $cid . '\')', 'Add Website', 'class="greenBtn floatRight button" style="margin-top:10px;margin-bottom:10px;" id="add_website_btn"');
	}
	
	$html .= '<script type="text/javascript">				
				jQuery("a.copyClipboard").click(function(e) {
    				e.preventDefault();
    				window.prompt("Press ctrl/cmd + c to copy text", jQuery(this).prev("p").text());
				});</script>';

	
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

function showStates($selected = false) {
    $ci =& get_instance();
    $ci->load->model('utilities');
    $states = $ci->utilities->getStates();

    $options = '<select data-placeholder="Choose a State..." class="chzn-select" style="width:350px;" name="state">';
	$options .= '<option value=""></option>';
    foreach ($states as $state) {
        $options .= '<option ' . (($selected AND $selected == $state->Abbrev) ? 'selected="selected"' : '') . ' value="' . $state->Abbrev . '">' . $state->Name . '</option>';
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