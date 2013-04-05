<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends DOM_Controller {

	public $agency_id;
	public $contact_id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->client_id = $this->user['DropdownDefault']->SelectedClient;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

	private function formatContactInfo(&$contact) {
		$contact->Name = $contact->FirstName . ' ' . $contact->LastName;
		$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
		$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone) : false;
		$contact->Email = mod_parser($contact->Email);
		$contact->Parent = $this->administration->getClient(substr($contact->Type,4))->Name;
	}
	
    public function index() {
		switch ($this->level) {
			case 'a':
				$contacts = $this->administration->getAllContactsInAgency($this->agency_id);
				break;
			case 'g':
				$contacts = $this->administration->getAllContactsInGroup($this->group_id);
				break;
			case 'c':
				$contacts = $this->administration->getContacts($this->client_id);
				break;
		}
		foreach ($contacts as &$contact) {
			$this->formatContactInfo($contact);
		}
		$data = array(
			'contacts' => $contacts
		);
		$this->LoadTemplate('pages/contact_listing',$data);
    }
	
	public function load_table() {
		$contacts = $this->administration->getContacts($this->agency_id);
		$data = array(
			'contacts'=>$contacts
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/contact_listing_table',$data);
	}
	
	public function View() {
		if(isset($_GET['gid'])) {
			$contact_id = $_GET['gid'];	
		}else {
			$contact_id = $this->user['DropdownDefault']->SelectedContact;	
		}
		
		$contact = $this->administration->getContact($contact_id);
		$this->formatContactInfo($contact);

		if($contact) {
			$data = array(
				'contact' => $contact,
				'level' => $this->user['DropdownDefault']->LevelType
			);
			$this->load->view($this->theme_settings['ThemeDir'] . '/pages/view_contact',$data);
		}
	}
	
	public function Form() {
		$contact_data = $this->security->xss_clean($this->input->post());
		if(isset($_GET['cid'])) {
			$contact_id = $_GET['cid'];
			$contact_data = $this->security->xss_clean($this->input->post());
			
			$email     = 'home:' . $contact_data['PersonalEmailAddress'] . (($contact_data['WorkEmailAddress']) ? ',work:' . $contact_data['WorkEmailAddress'] : '');
			$phone     = 'main:' . $contact_data['DirectPhone'] . (($contact_data['MobilePhone']) ? ',mobile:' . $contact_data['MobilePhone'] : '') . (($contact_data['FaxPhone']) ? ',fax:' . $contact_data['FaxPhone'] : '');
			$type      = $contact_data['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
			$firstname = $contact_data['firstname'];
			$lastname  =  $contact_data['lastname'];
			$address   = 'street:' . $contact_data['street'] . ',city:' . $contact_data['city'] . ',state:' . $contact_data['state'] . ',zipcode:' . $contact_data['zip'];
			$notes     = $contact_data['notes'];

			//prepare the update
			$edit_data = array(
				'TITLE_ID' => 12,
				'DIRECTORY_Type' => $type,
				'DIRECTORY_FirstName' => $firstname,
				'DIRECTORY_LastName' => $lastname,
				'DIRECTORY_Address' => $address,
				'DIRECTORY_Email' => $email,
				'DIRECTORY_Phone' => $phone,
				'DIRECTORY_Notes' => $notes,
				'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME),
				'JobTitle'=>$contact_data['JobTitle'],
				'CLIENT_Owner'=> $contact_data['company']
			);
			
			$update = $this->administration->updateContact($contact_id,$edit_data);
			
			if($update) {
				echo '1';	
			}else {
				echo '0';
			}
			
		}else {
			$type = $contact_data['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
			$firstname = $contact_data['firstname'];
			$lastname =  $contact_data['lastname'];
			$address = 'street:' . $contact_data['street'] . ',city:' . $contact_data['city'] . ',state:' . $contact_data['state'] . ',zipcode:' . $contact_data['zip'];
			$notes = $contact_data['notes'];
			
			$email  = 'home:' . $contact_data['PersonalEmailAddress'] . 
			  (($contact_data['WorkEmailAddress']) ? 
				',work:' . $contact_data['WorkEmailAddress'] : 
			  '');
			  
			$phone  = 'main:' . $contact_data['DirectPhone'] . (($contact_data['MobilePhone']) ? ',mobile:' . $contact_data['MobilePhone'] : '') . (($contact_data['FaxPhone']) ? ',fax:' . $contact_data['FaxPhone'] : '');
			
			//prepare the add
			$add_data = array(
				'TITLE_ID' => 12,
				'DIRECTORY_Type' => $type,
				'DIRECTORY_FirstName' => $firstname,
				'DIRECTORY_LastName' => $lastname,
				'DIRECTORY_Address' => $address,
				'DIRECTORY_Email' => $email,
				'DIRECTORY_Phone' => $phone,
				'DIRECTORY_Notes' => $notes,
				'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME),
				'JobTitle'=>$contact_data['JobTitle'],
				'CLIENT_Owner'=>$this->user['DropdownDefault']->SelectedClient
			);
			
			$add = $this->administration->addContact($add_data);
			
			if($add) {
				echo '1';	
			}else {
				echo '0';	
			}
			
		}
	}
	
	public function Add() {		
		$clients = $this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$data = array(
			'clients'=>$clients
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddcontact',$data);
	}
	
	public function Edit() {
		if(isset($_GET['cid'])) {
			$contact_id = $_GET['cid'];
		}
		
		$contact = $this->administration->getContact($contact_id);
		$this->formatContactInfo($contact);
		$clients = $this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency);

		if($contact) {
			$data = array(
				'contact'=>$contact,
				'clients'=>$clients
			);	
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddcontact',$data);
		}else {
			echo '0';	
		}
	}

}
