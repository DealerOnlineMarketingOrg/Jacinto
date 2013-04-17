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
		$contact->PrimaryPhone = (isset($contact->Phone[$contact->PrimaryPhoneType])) ? $contact->Phone[$contact->PrimaryPhoneType] : false;
		$contact->Email = mod_parser($contact->Email);
		$contact->PrimaryEmail = (isset($contact->Email[$contact->PrimaryEmailType])) ? $contact->Email[$contact->PrimaryEmailType] : false;
		$contact->Parent = $this->administration->getClient(substr($contact->Type,4))->Name;
		$contact->TypeCode = substr($contact->Type,0,3);
		$contact->TypeID = substr($contact->Type,4);
	}
	
    public function index() {
		$this->LoadTemplate('pages/contact_listing');
    }
	
	public function load_table() {
		$contacts = $this->administration->getAllContactsInAgency($this->agency_id);
		$data = array(
			'contacts'=>$contacts
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/contact_listing_table',$data);
	}
	
	public function View() {
		if(isset($_GET['uid'])) {
			$contact_id = $_GET['uid'];	
		}else {
			$contact_id = $this->user['DropdownDefault']->SelectedContact;
		}
		
		$contact = $this->administration->getContact($contact_id);
		$this->formatContactInfo($contact);
		
		if($contact) {
			$data = array(
				'contact' => $contact,
				'level' => $this->user['DropdownDefault']->LevelType,
				'websites'=>load_contact_websites($contact_id),
			);
			$this->load->view($this->theme_settings['ThemeDir'] . '/pages/view_contact',$data);
		}
	}
	
	public function Form() {
		$contact_data = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['uid'])) {
			$contact_id = $_GET['uid'];
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
				'TITLE_ID' => $contact_data['jobTitleType'],
				'JobTitle' => $contact_data['JobTitle'],
				'DIRECTORY_Type' => $type,
				'CLIENT_Owner' => ($type == 'CID') ? $contact_data['parentClient'] : ($type == 'VID') ? $contact_data['ParentVendor'] : NULL,
				'DIRECTORY_FirstName' => $firstname,
				'DIRECTORY_LastName' => $lastname,
				'DIRECTORY_Address' => $address,
				'DIRECTORY_Email' => $email,
				'DIRECTORY_Phone' => $phone,
				'DIRECTORY_Notes' => $notes,
				'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME),
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
				'TITLE_ID' => $contact_data['jobTitleType'],
				'JobTitle' => $contact_data['JobTitle'],
				'DIRECTORY_Type' => $type,
				'CLIENT_Owner' => ($type == 'CID') ? $contact_data['parentClient'] : ($type == 'VID') ? $contact_data['ParentVendor'] : NULL,
				'DIRECTORY_FirstName' => $firstname,
				'DIRECTORY_LastName' => $lastname,
				'DIRECTORY_Address' => $address,
				'DIRECTORY_Email' => $email,
				'DIRECTORY_Phone' => $phone,
				'DIRECTORY_Notes' => $notes,
				'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME),
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
		$vendors = $this->administration->getAllVendors();
		$types = $this->administration->getTypeList();
		$tags = $this->administration->getAllTags();  

		$data = array(
			'page'=>'add',
			'contact'=>false,
			'clients'=>$clients,
			'vendors'=>$vendors,
			'types'=>$types,
			'tags'=>$tags,
			'websites'=>'',
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddcontact',$data);
	}
	
	public function Edit() {
		if(isset($_GET['uid'])) {
			$contact_id = $_GET['uid'];
		}
		
		$contact = $this->administration->getContact($contact_id);
		$this->formatContactInfo($contact);
		$clients = $this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$vendors = $this->administration->getAllVendors();
		$types = $this->administration->getTypeList();
		$tags = $this->administration->getAllTags();  

		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'clients'=>$clients,
				'vendors'=>$vendors,
				'types'=>$types,
				'tags'=>$tags,
				'websites'=>load_contact_websites($contact_id),
			);	
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddcontact',$data);
		}else {
			echo '0';	
		}
	}
	
	public function PhoneAdd() {

		$data = array(
			'page'=>'add',
			'contact'=>false,
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddphone',$data);
	}
	
	public function PhoneEdit() {
		if(isset($_GET['uid'])) {
			$contact_id = $_GET['uid'];
			$type = $_GET['type'];
		}
		
		$contact = $this->administration->getContact($contact_id);
		$this->formatContactInfo($contact);

		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'type'=>$type,
			);	
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddphone',$data);
		}else {
			echo '0';	
		}		
	}
	
	public function EmailAdd() {

		$data = array(
			'page'=>'add',
			'contact'=>false,
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddemail',$data);
	}
	
	public function EmailEdit() {
		if(isset($_GET['uid'])) {
			$contact_id = $_GET['uid'];
			$type = $_GET['type'];
		}
		
		$contact = $this->administration->getContact($contact_id);
		$this->formatContactInfo($contact);

		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'type'=>$type,
			);
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddemail',$data);
		}else {
			echo '0';	
		}		
	}

}
