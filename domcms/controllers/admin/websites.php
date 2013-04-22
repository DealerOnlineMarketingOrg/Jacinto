<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends DOM_Controller {
	
	//should always have this
	public $client_id;
	
	//when working with a vendor, we fill this
	public $vendor_id;
	
	//if we're editing we should always have this
	public $website_id;
	
	//when working with a contact, we use this
	public $contact_id;
	
	//when working with a user
	public $user_id;
	
	protected $type = '';

    public function __construct() {
        parent::__construct();
		//load the admin model
		$this->load->model('administration');
		
		if(isset($_POST['cid'])) {
			$this->client_id = $_POST['cid'];
			$this->type = 'cid';
		}elseif(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
			$this->type = 'cid';
		}
		if(isset($_POST['wid'])) {
			$this->website_id = $_POST['wid'];
			$this->type = 'wid';
		}elseif(isset($_GET['wid'])) {
			$this->website_id = $_GET['wid'];
			$this->type = 'wid';
		}
		if(isset($_POST['gid'])) {
			$this->contact_id = $_POST['gid'];
			$this->type = 'gid';
		}elseif(isset($_GET['gid'])) {
			$this->contact_id = $_GET['gid'];
			$this->type = 'gid';
		}
		if(isset($_POST['uid'])) {
			$this->user_id = $_POST['uid'];
			$this->type = 'uid';
		}elseif(isset($_GET['uid'])) {
			$this->user_id = $_GET['uid'];
			$this->type = 'uid';
		}
	}
	
	public function Load_table() {
		$this->load->helper('template');
		if (isset($this->client_id))
			$table = load_websites($this->client_id,$this->type);
		if (isset($this->contact_id))
			$table = load_websites($this->contact_id,$this->type);
		if (isset($this->user_id))
			$table = load_websites($this->user_id,$this->type);
		print $table;
	}
	
	public function add() {
		$form = $this->input->post();
		if(isset($_GET['cid'])) {
			//var_dump($form);
			$add = $this->administration->addWebsiteInfo($form,$this->type);
			if($add) {
				print 1;
			}else {
				print 0;
			}
		}elseif(isset($_GET['vid'])) {
			$add = $this->administration->addKnownVendorWebsite($form,$_GET['vid']);
			if($add) {
				print 1;	
			}else {
				print 0;
			}
		}elseif(isset($_GET['gid'])) {
			$add = $this->administration->addWebsiteInfo($form,$this->type);
			if($add) {
				print 1;	
			}else {
				print 0;
			}
		}elseif(isset($_GET['uid'])) {
			$add = $this->administration->addWebsiteInfo($form,$this->type);
			if($add) {
				print 1;	
			}else {
				print 0;
			}
		}else {
			print 0;
		}
	}
	
	public function edit() {
		$form = $this->input->post();
		if(isset($_GET['wid'])) {
			$add = $this->administration->editWebsiteInfo($form,$this->type);
			if($add) {
				print 1;
			}else {
				print 0;
			}
		}elseif(isset($_GET['vid'])) {
		
		}else {
			print 0;
		}
	}
	
	public function disable() {
		if(isset($_GET['wid'])) {
			$disable = $this->administration->disableWebsite($this->website_id);
			if($disable) {
				print $disable;	
			}else {
				print 0;	
			}
		}else {
			print 0;	
		}
	}
	
	public function enable() {
		if(isset($_GET['wid'])) {
			$enable = $this->administration->enableWebsite($this->website_id);
			if($enable) {
				print $enable;	
			}else {
				print 0;	
			}
		}else {
			print 0;	
		}
	}
	
	public function form() {
		if(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
			//get the right client info
			$client = $this->administration->getClient($_GET['cid']);
			//vendors are not associated per client
			$vendors = $this->administration->getAllVendors();
			//prepare the array
			$data = array(
				'caller'=>$client,
				'type'=>$this->type,
				'vendors'=>$vendors,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE),
			);
			//print_object($this->administration->getWebsite($_GET['wid']));
			if ($data['website']) {
				$data['page'] = 'edit';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/websites/add_edit',$data);
			} else {
				$data['page'] = 'add';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/websites/add_edit',$data);
			}
		}elseif(isset($_GET['vid'])) {
			$this->vendor_id = $_GET['vid'];
			$vendor = $this->administration->getVendor($_GET['vid']);
			$data = array(
				'selectedVendor'=>$vendor->ID,
				'type'=>$this->type,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE)
			);
		}elseif(isset($_GET['gid'])) {
			$this->contact_id = $_GET['gid'];
			$contact = $this->administration->getContact($_GET['gid']);
			// ID property on add websites page.
			$contact->ID = $contact->ContactID;
			//vendors are not associated per contact
			$vendors = $this->administration->getAllVendors();
			$data = array(
				'caller'=>$contact,
				'type'=>$this->type,
				'vendors'=>$vendors,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE)
			);
			if ($data['website']) {
				$data['page'] = 'edit';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addwebsites',$data);
			} else {
				$data['page'] = 'add';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addwebsites',$data);
			}
		}elseif(isset($_GET['uid'])) {
			$this->contact_id = $_GET['uid'];
			$contact = $this->administration->getContact($_GET['uid']);
			// ID property on add websites page.
			$contact->ID = $contact->ContactID;
			//vendors are not associated per contact
			$vendors = $this->administration->getAllVendors();
			$data = array(
				'caller'=>$contact,
				'type'=>$this->type,
				'vendors'=>$vendors,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE)
			);
			if ($data['website']) {
				$data['page'] = 'edit';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/websites/add_edit',$data);
			} else {
				$data['page'] = 'add';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/websites/add_edit',$data);
			}
		}
	}
	
}
	