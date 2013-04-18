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
		// Locate primary.
		foreach ($contact->Phone as $type => $phone) {
			if ($phone == $contact->PrimaryPhoneType) {
				$contact->PrimaryPhone = $phone;
				break;
			}
		}
		$contact->Email = mod_parser($contact->Email);
		// Locate primary.
		foreach ($contact->Email as $type => $email) {
			if ($email == $contact->PrimaryEmailType) {
				$contact->PrimaryEmail = $email;
				break;
			}
		}
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
				'websites'=>load_contact_websites($contact_id, false),
			);
			$this->load->view($this->theme_settings['ThemeDir'] . '/pages/view_contact',$data);
		}
	}
	
	public function Form() {
		$contact_data = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['uid']))
			$contact_id = $_GET['uid'];
		else
			$contact_id = '';
		
		$type      = $contact_data['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
		$firstname = $contact_data['firstname'];
		$lastname  = $contact_data['lastname'];
		$address   = 'street:' . $contact_data['street'] . ',city:' . $contact_data['city'] . ',state:' . $contact_data['state'] . ',zipcode:' . $contact_data['zip'];
		$notes     = $contact_data['notes'];

		//prepare the add/update
		$data = array(
			'TITLE_ID' => $contact_data['jobTitleType'],
			'DIRECTORY_Type' => $type,
			'CLIENT_Owner' => ($type == 'CID') ? $contact_data['parentClient'] : ($type == 'VID') ? $contact_data['ParentVendor'] : NULL,
			'DIRECTORY_FirstName' => $firstname,
			'DIRECTORY_LastName' => $lastname,
			'DIRECTORY_Address' => $address,
			'DIRECTORY_Notes' => $notes,
		);
		if ($contact_id) {
			$contact = $this->administration->updateContact($contact_id,$data);
		} else {
			$primaryEmail = $contact_data['email'];
			$email     = 'work:' . $primaryEmail;
			$primaryPhone = $contact_data['phone'];
			$phone     = 'work:' . $primaryPhone;

			$data['DIRECTORY_Created'] = date(FULL_MILITARY_DATETIME);
			$data['DIRECTORY_Email'] = $email;
			$data['DIRECTORY_Primary_Email'] = $primaryEmail;
			$data['DIRECTORY_Phone'] = $phone;
			$data['DIRECTORY_Primary_Phone'] = $primaryPhone;
			$contact = $this->administration->addContact($data);
		}
		
		if($contact) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function FormPhone() {
		$form = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['uid']))
			$contact_id = $_GET['uid'];
		else
			$contact_id = '';
			
		$type = $form['type'];
		$phone = $form['phone'];
		$new = $type.':'.$phone;
		$old = $form['old'];
		
		echo 'msg:'.$contact_id.','.$old.','.$new;
		if ($contact_id)
			$this->administration->editContactPhone($contact_id, $old, $new);
		else
			$this->administration->addContactPhone($contact_id, $new);
		
		if($contact_id) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function FormEmail() {
		$form = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['uid']))
			$contact_id = $_GET['uid'];
		else
			$contact_id = '';
			
		$type = $form['type'];
		$email = $form['email'];
		$old = $form['old'];
		
		if ($contact_id)
			$this->administation->editContactEmail($contact_id, $old, $type.':'.$email);
		else
			$this->administation->addContactEmail($contact_id, $type.':'.$email);
			
		if($contact_id) {
			echo '1';	
		}else {
			echo '0';
		}

	}
	
	public function FormPrimary() {
		
		if(isset($_GET['uid'])) {
			$contact_id = $_GET['uid'];
			$phonePrimary = $_GET['phone'];
			$emailPrimary = $_GET['email'];
		} else
			$contact_id = '';
			
		if ($contact_id)
			$this->administation->editContactEmail($contact_id, $old, $type.':'.$email);
		else
			$this->administation->addContactEmail($contact_id, $type.':'.$email);
				
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
