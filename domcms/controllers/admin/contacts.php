<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends DOM_Controller {

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

    public function index() {
			
		$table = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
	
		$level = $this->user['DropdownDefault']->LevelType;
		if($level == 'g') {
			
			$clients = $this->administration->getClientIDSInGroup($this->user['DropdownDefault']->SelectedGroup);
			
			$contacts = array();
			foreach($clients as $client) {
				$Groupedcontacts = $this->administration->getContacts($client->ID);
				if($Groupedcontacts)
					foreach($Groupedcontacts as $newContact) {
						array_push($contacts,$newContact);
					}
				//array_push($contacts,$this->administration->getContacts($client->ID));	
			}
		}elseif($level == 'a') {
			$contacts = $this->administration->getAllContactsInAgency($this->user['DropdownDefault']->SelectedAgency);
		}else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;
			$contacts = $this->administration->getContacts($client_id);
		}
		
		//table heading
		$html = '';
		
		if($contacts) :
			$table .= '<thead><tr>' . '<th>Team</th>' . (($level == 'g' OR $level == 'a') ? '<th style="text-align:left;">Dealership</th>' : '' ) . '<th style="width:10%;">Title</th><th>Name</th><th>Email</th><th>Phone</th><th style="text-align:center">Edit</th><th style="text-align:center">View</tr></thead>';
			$table .= '<tbody>';
			foreach($contacts as $contact) {
				$edit_button = '';
				$edit_button .= form_open('/contacts/edit',array('name'=>'EditForm','id'=>'contact_' . $contact->ContactID));
				$edit_button .= form_hidden('contact_id',$contact->ContactID);
				$edit_button .= form_submit('editContact','Edit','class="redBtn" ');
				$edit_button .= form_close();
				$view_button = '';
				$view_button .= form_open('/contacts/view',array('name'=>'ViewContact','id'=>'view_' . $contact->ContactID));
				$view_button .= form_hidden('view_id',$contact->ContactID);
				$view_button .= form_submit('viewContact','View','class="blueBtn"');
				$view_button .= form_close();
				$contact->Name 		= $contact->FirstName . ' ' . $contact->LastName;
				$contact->Address 	= mod_parser($contact->Address);
				$contact->Email 	= mod_parser($contact->Email, 'home,work');
				$contact->Phone 	= mod_parser($contact->Phone, 'main,mobile,fax');
				$contact->Title 	= $this->administration->getContactTitle($contact->Title);
				$table .= '<tr class="tagElement ' . $contact->Tag . '">';
				$table .= '<td class="tags"><div class="' . $contact->Tag . '">&nbsp;</div></td>';
				if($level == 'g' OR $level == 'a') {
					$table .= '<td style="width:auto;white-space:no-wrap;text-align:left;">' . $this->administration->getClient(substr($contact->Type,4))->Name . '</td>';
				}
				$table .= '<td style="text-align:left;">' . $contact->JobTitle . '</td>';
				$table .= '<td>' . $contact->Name . '</td>';
				
				$table .= '<td>' . '<span style="font-weight:bold;">Personal Email</span><br /><a href="mailto:' . $contact->Email["home"] . '">' . $contact->Email['home'] . '</a></span>' . ((isset($contact->Email['work'])) ? '<br /><span style="font-weight:bold;">Work Email</span><br /><a href="mailto:' . $contact->Email["work"] . '">' . $contact->Email['work'] . '</a></span>' : '') . '</td>';
					
				$table .= '<td>' . '<span style="font-weight:bold;">Direct</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['main'] . '">' . $contact->Phone['main'] . '</a></span>' . ((isset($contact->Phone['mobile'])) ? '<br /><span style="font-weight:bold;">Mobile</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['mobile'] . '">' . $contact->Phone['mobile'] . '</a></span>' : '') . ((isset($contact->Phone['fax'])) ? '<br /><span style="font-weight:bold;">Fax</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['fax'] . '">' . $contact->Phone['fax'] . '</a></span>' : '') . '</td>';
				
				$table .= '<td style="text-align:center;vertical-align:middle;border-right:none;">' . $edit_button . '</td>' . '<td style="text-align:center;vertical-align:middle;">' . $view_button . '</td>';
				$table .= '</tr>';
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
		
		//If the user has permission to add a new group, then show a button to do so.
		if ($this->CheckModule('Client_Add') && $level == 'c') {
			$html .= anchor(base_url() . 'contacts/add', 'Add New Contact', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_contact_btn"');
		}else {
			$html .= '<p style="float:right;">To add new contacts, please select a client from the Dealer Dropdown</p>';	
		}

		$data = array(
			'page_id' => 'Contacts',
			'page_html' => $html,
		);
		//LOAD THE TEMPLATE
		$this->LoadTemplate('pages/listings', $data);
    }
	
	public function Add() {
		$html = '';
		$titles = '';
		
		$data = array(
			'html' => $html
		);
		$this->LoadTemplate('forms/form_addcontacts',$data);
	}
	
	public function Edit() {
		$html = '';
	
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
	
	public function Delete() {
		
	}
	
	public function View() {
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
