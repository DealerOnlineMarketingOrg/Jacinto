<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactInfo extends DOM_Controller {

	public $agency_id;
	public $id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->client_id = $this->user['DropdownDefault']->SelectedClient;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }
	
    public function index() { }
	
	public function FormPhone() {
		$form = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$page = $_GET['page'];
		} else
			$id = '';
			
		$type = $form['type'];
		$phone = $form['phone'];
		$new = $type.':'.$phone;
		$old = $form['old'];
		
		if ($page == 'edit')
			$ret = $this->administration->editContactInfoPhone($id, $old, $new);
		else
			$ret = $this->administration->addContactInfoPhone($id, $new);
		
		if($id && $ret) {
			echo '1';
		}else {
			echo '0';
		}
	}
	
	public function FormEmail() {
		$form = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$page = $_GET['page'];
		} else
			$id = '';
			
		$type = $form['type'];
		$email = $form['email'];
		$new = $type.':'.$email;
		$old = $form['old'];
		
		if ($page == 'edit')
			$ret = $this->administration->editContactInfoEmail($id, $old, $new);
		else
			$ret = $this->administration->addContactInfoEmail($id, $new);
		
		if($id && $ret) {
			echo '1';	
		}else {
			echo '0';
		}

	}

	public function PhoneAdd() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		
		$contact = $this->administration->getContact($id);

		$data = array(
			'page'=>'add',
			'contact'=>$contact,
			'type'=>'',
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/contactInfo/edit_add_phone',$data);
	}
	
	public function PhoneEdit() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$type = $_GET['type'];
			$value = $_GET['value'];
		}
		
		$contact = $this->administration->getContact($id);
		
		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'type'=>$type,
				'value'=>$value,
			);	
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/contactInfo/edit_add_phone',$data);
		}else {
			echo '0';	
		}		
	}
	
	public function EmailAdd() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		
		$contact = $this->administration->getContact($id);
		
		$data = array(
			'page'=>'add',
			'contact'=>$contact,
			'type'=>'',
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/contactInfo/edit_add_email',$data);
	}
	
	public function EmailEdit() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$type = $_GET['type'];
			$value = $_GET['value'];
		}
		
		$contact = $this->administration->getContact($id);

		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'type'=>$type,
				'value'=>$value,
			);
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/contactInfo/edit_add_email',$data);
		}else {
			echo '0';	
		}		
	}

	public function FormPrimary() {
		
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$phonePrimary = $_GET['phone'];
			$emailPrimary = $_GET['email'];
		} else
			$id = '';
			
		$ret = $this->administration->editPrimaryPhoneEmail($id, $phonePrimary, $emailPrimary);
		
		if($id && $ret) {
			echo '1';	
		}else {
			echo '0';
		}
	}
}
