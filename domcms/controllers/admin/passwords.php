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
		
		$counter = 0;
		if($contacts) :
			$table .= '<thead><tr><th>Team</th><th>Type</th><th>Vendor</th><th>Login Address</th><th>Username</th><th>Password</th><th>Notes</th><th style="text-align:center">Action</th></thead>';
			$table .= '<tbody>';
			foreach($contacts as $contact) {
				$edit_button = '';
				/*
				$edit_button .= form_open('/contacts/edit',array('name'=>'EditForm','id'=>'contact_' . $contact->ContactID));
				$edit_button .= form_hidden('contact_id',$contact->ContactID);
				$edit_button .= form_submit('editContact','Edit','class="redBtn" ');
				$edit_button .= form_close();
				*/
				
				$view_button = '';
				/*
				$view_button .= form_open('/contacts/view',array('name'=>'ViewContact','id'=>'view_' . $contact->ContactID));
				$view_button .= form_hidden('view_id',$contact->ContactID);
				$view_button .= form_submit('viewContact','View','class="blueBtn"');
				$view_button .= form_close();
				*/
				
				$table .= '<tr class="tagElement ' . $contact->Tag . '">';
				$table .= '<td class="tags"><div class="' . $contact->Tag . '">&nbsp;</div></td>';
				$table .= '<td style="text-align:left;">' . $contact->Type . '</td>';
				$table .= '<td style="text-align:left;">' . $contact->Vendor . '</td>';
				$table .= '<td><a href="' . $contact->LoginAddress . '">' . $contact->LoginAddress . '</a></td>';
				
				$table .= '<td><span style="font-weight:bold;"><div id="username'.$counter . '" class="clipBoard" clipBoardData="' . $contact->Username . '" style="width:22px; height:22px; float:left; cursor:pointer; background: url(' .  base_url() . 'assets/icons/dark/clipboard.png) no-repeat"></div><div style="float:left"><a href="mailto:' . $contact->Username . '">' . $contact->Username . '</a></div></span></td>';
				$table .= '<td><div id="password'.$counter . '" class="clipBoard" clipBoardData="' . $contact->Password . '" style="width:22px; height:22px; float:left; cursor:pointer; background: url(' .  base_url() . 'assets/icons/dark/clipboard.png) no-repeat"></div><div style="float:left">' . $contact->Password . '</div></td>';
				$table .= '<td style="width:25%"><div style="overflow:hidden; max-height:37px"><div style="float:left;width:80%">' . $contact->Notes . '</div><div onclick="javascript: openMore(\'' . $contact->Notes . '\');" style="cursor:pointer;float:right;color:blue;bottom:0">...more</div></div></td>';
				$table .= '<td style="text-align:center;vertical-align:middle;border-right:none;">' . $edit_button . $view_button . '</td>';
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
		$html = $btn . $html . $btn;

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
			$html .= success_msg('The Client was added successfully!');
		} elseif($msg AND $msg != 'success') {
			//The error message after a group was added, or edited.
			$html .= error_msg();
		}
		$data = array(
			'html' => $html
		);
		$this->LoadTemplate('forms/form_addcontacts',$data);
	}
	
	public function Edit($msg=false) {
		$html = '';
		
		if($msg AND $msg != 'error') {
			$html .= success_msg('Your client was successfully edited!');
		}elseif($msg AND $msg == 'error') {
			$html .= error_msg();
		}
	
		 //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		$contact_id = $this->input->post('contact_id');
		$this->load->model('administration');
		$contact = $this->administration->getContact($contact_id);
		
		if($contact) {
			$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
			$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone) : false;
			$contact->Type = substr($contact->Type,0,3);
			$contact->Email = mod_parser($contact->Email);
		}
		
		//PREPARE THE VIEW FOR THE FORM
		$data = array(
			'contact' => $contact,
			'html' => $html
		);
		//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
		
		$this->LoadTemplate('forms/form_editcontacts',$data);
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
