<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Passwords extends DOM_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('administration');
        $this->load->helper('string_parser');
        $this->load->helper('msg');
		$this->activeNav = 'admin';
    }

    public function index($msg=false) {
			
		$table = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
	
		// Password page will only be for clients.
		$level = $this->user['DropdownDefault']->LevelType;
		$contacts = $this->administration->getPasswords($this->user['DropdownDefault']->SelectedClient);
		
		//table heading
		$html = '';
		$error_msg = '';
		
		//If there is a message to the user, present it as it should be.
		if ($msg AND $msg != 'error') {
			//The success message after a group was added or edited.
			$html .= success_msg('The Client was edited successfully!');
		} elseif($msg AND $msg != 'success') {
			//The error message after a group was added, or edited.
			$error_msg = error_msg();
		}
		
		$header = '<style type="text/css">a.actions_link{margin-right:5px;td.actionsCol{width:75px !important;text-align:center;}</style>';
		$header .= '<script type="text/javascript" src="' . base_url() . 'assets/themes/itsbrain/js/passwords_popups.js"></script>';

		$counter = 0;
		if($contacts) :
			$table .= '<thead><tr><th>Team</th><th>Type</th><th>Vendor</th><th>Login Address</th><th>Username</th><th>Password</th><th>Notes</th><th style="text-align:center">Action</th></thead>';
			$table .= '<tbody>';
			foreach($contacts as $contact) {
				//edit button
				$edit_img = '<img src="' . base_url() . THEMEIMGS . 'icons/dark/pencil.png" alt="Edit Passwords" />';
				$edit_a = '<a class="actions_link" href="javascript:editPasswords(\'' . $contact->ID . '\',\'' . base_url() . '\');" title="Edit Passwords">' . $edit_img . '</a>';
				//view button
				$view_img = '<img src="' . base_url() . THEMEIMGS . 'icons/color/application.png" alt="View Passwords Information" />';
				$view_a = '<a class="actions_link" href="javascript:viewPasswords(\'' . $contact->ID . '\');" title="View Passwords Information">' . $view_img . '</a>';
				//disable button
				$disable_img = '<img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable Passwords" />';
				$disable_a = '<a class="actions_link" href="javascript:disablePasswords(\'' . $contact->ID . '\');" title="Disable Passwords">' . $disable_img . '</a>';
				//enable button
				$enable_img = '<img src="' . base_url() . THEMEIMGS . 'icons/notifications/accept.png" alt="Enable Passwords" />';
				$enable_a = '<a class="actions_link" href="javasript:enablePasswords(\'' . $contact->ID . '\');" title="Enable Passwords">' . $enable_img . '</a>';
				
				$table .= '<tr class="tagElement ' . $contact->Tag . '">';
				$table .= '<td class="tags"><div class="' . $contact->Tag . '">&nbsp;</div></td>';
				$table .= '<td style="text-align:left;">' . $contact->Type . '</td>';
				$table .= '<td style="text-align:left;">' . $contact->Vendor . '</td>';
				$table .= '<td><a href="' . $contact->LoginAddress . '">' . $contact->LoginAddress . '</a></td>';
				
				$table .= '<td><span style="font-weight:bold;"><div id="username'.$counter . '" class="clipBoard" clipBoardData="' . $contact->Username . '" style="width:22px; height:22px; float:left; cursor:pointer; background: url(' .  base_url() . 'assets/icons/dark/clipboard.png) no-repeat"></div><div style="float:left"><a href="mailto:' . $contact->Username . '">' . $contact->Username . '</a></div></span></td>';
				$table .= '<td><div id="password'.$counter . '" class="clipBoard" clipBoardData="' . $contact->Password . '" style="width:22px; height:22px; float:left; cursor:pointer; background: url(' .  base_url() . 'assets/icons/dark/clipboard.png) no-repeat"></div><div style="float:left">' . $contact->Password . '</div></td>';
				$table .= '<td style="width:25%"><div style="overflow:hidden; max-height:37px"><div style="float:left;width:80%">' . $contact->Notes . '</div><div onclick="javascript: openMore(\'' . $contact->Notes . '\');" style="cursor:pointer;float:right;color:blue;bottom:0">...more</div></div></td>';
				$table .= '<td class="actionsCol" style="width:75px;text-align:center">';
				
				//put allowed action buttons in place
				//if($this->CheckModule('Passwords_Edit')) {
					$table .= $edit_a;
				//}
				$table .= $view_a;
				/*
				if($this->CheckModule('Client_Disable_Enable')) {
					if($client->Status) {
						$table .= $disable_a;
					}else {
						$table .= $enable_a;
					}
				}
				*/
				
				$table .= '</td>';
				$table .= '</tr>';
				
				$counter++;
			}
			$table .= '</tbody>';
		else :
		   $table .= '<thead><tr><th>Error</th></tr></thead>';
		   $table .= '<tbody><tr><td><p>Sorry, No contacts found for this client. Select another client from the dropdown selector or add a contact by clicking the add contact button below.</p></td></tr></tbody>';
		endif;
		$table .= '</table><div class="fix"></div>';
		//This builds the pages html out. We do this here so all our listing pages can have the same template view.
		$html .= ((count($contacts) > 0) ? $table . "\n":$error_msg);
		$html .= '<div class="fix"></div>';
		
		//If the user has permission to add a new group, then show a button to do so, top and bottom.
		$btn = '';
		if ($this->CheckModule('Client_Add') && $level == 'c') {
			$btn = anchor(base_url() . 'contacts/add', 'Add New Contact', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_contact_btn"');
		}else if ($this->CheckModule('Client_Add')) {
			$btn = '<p style="float:right;">To add new contacts, please select a client from the Dealer Dropdown</p>';	
		}
		
		$popups = '</tbody></table><div id="editPasswordsInfo"></div><div id="viewPasswordsInfo"></div></div>' . "\n";
		
		$html = $header . $btn . $html . $btn . $popups;

		$scripts = '
			var clip;
			$(window).load (function() {
				$(".clipBoard").zclip({
					path: "' . base_url() . 'assets/ZeroClipboard.swf",
					copy: $(this).attr("clipBoardData"),
					afterCopy: function() {alert("after");}
				});			
			});
			
			function initClipboard() {
				clip = new Array();
				
				// Create clipboard object for each copy region in the form.
				var zcPath = "' . base_url() . 'assets/ZeroClipboard.swf";
				ZeroClipboard.setDefaults({
						moviePath: zcPath
					});
				
				// Run through all the username divs and link up the clipboard dom-object for each.
				// ZeroClipboard works by having an invisible flash object on the dom-object,
				//  which runs when the user clicks the dom-object.
				// count contains the number of data rows. Rows are 0-indexed.
				var count = ' . $counter . ';
				for (i = 0; i < count; i++) {
					clip[i] = new Array();
					clip[i]["username"] = new ZeroClipboard($("#username"+i));
					clip[i]["username"].on("click", function(client) {clip.setText($(this).val())});
					clip[i]["password"] = new ZeroClipboard($("#password"+i));
					clip[i]["password"].on("click", function(client) {clip.setText($(this).val())});
				}
			}
			
			function openMore(text) {
				alert(text);
			}
		';
		
		$data = array(
			'page_id' => 'Passwords',
			'page_html' => $html,
			'page_scripts' => $scripts
		);
		//LOAD THE TEMPLATE
		$this->LoadTemplate('pages/listings', $data);
    }
	
	public function Add($msg=false) {
		$html = '';
		$titles = '';
		//If there is a message to the user, present it as it should be.
		if ($msg AND $msg != 'error') {
			//The success message after a group was added or edited.
			$html .= success_msg('The Passwords was added successfully!');
		} elseif($msg AND $msg != 'success') {
			//The error message after a group was added, or edited.
			$html .= error_msg();
		}
		$data = array(
			'html' => $html
		);
		$this->LoadTemplate('forms/form_addpasswords',$data);
	}
	
	public function Edit($msg=false) {
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
		/*
		if($level == 'g') {
			redirect('/groups/edit','refresh');	
		}
		
		if($level == 'a') {
			redirect('/agencies/edit','refresh');	
		}
		*/
		
		$html = '';
		
		$contact_id = FALSE;
		$pwd_id = FALSE;
		if(isset($_POST['pwd_id'])) {
			$pwd_id = $this->input->post('pwd_id');
		}elseif(isset($_GET['pwdid'])) {
			$pwd_id = $this->input->get('pwdid');
		}else {
			$contact_id = $this->user['DropdownDefault']->SelectedClient;
		}
	
		 //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		//$contact_id = ($this->input->post('contact_id'))?$this->input->post('contact_id'):$this->user['DropdownDefault']->SelectedClient;
		$this->load->model('administration');
		if ($contact_id)
			$contact = $this->administration->getPasswords($contact_id);
		else
			$contact = $this->administration->getPasswordsByID($pwd_id);
		$types = $this->administration->getAllTypes();
		if($contact) {
			$data = array(
				'contact' => $contact,
				'html' => $html,
				'types'=>$types
			);
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
		
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editpasswords',$data);
			
		}else {
			//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
			echo 0;
		}
	}
	
	public function Delete($msg=false) {
		
	}
	
	public function View($msg=false) {
		$contact = $this->administration->getContact($this->input->post('view_id'));
		$contact->Name = $contact->FirstName . ' ' . $contact->LastName;
		$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
		$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone) : false;
		$contact->Email = mod_parser($contact->Email);
		$data = array(
			'display'=>$contact
		);
		$this->LoadTemplate('pages/details',$data);
	}

}
