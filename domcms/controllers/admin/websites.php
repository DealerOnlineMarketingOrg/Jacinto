<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends DOM_Controller {
	
	//should always have this
	public $id;
	
	//when working with a vendor, we fill this
	public $vendor_id;
	
	//if we're editing we should always have this
	public $website_id;
	
	protected $type = '';

    public function __construct() {
        parent::__construct();
		//load the admin model
		$this->load->model('administration');
		
		if(isset($_POST['cid'])) {
			$this->id = $_POST['cid'];
			$this->type = 'cid';
		}elseif(isset($_GET['cid'])) {
			$this->id = $_GET['cid'];
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
			$this->id = $_POST['gid'];
			$this->type = 'gid';
		}elseif(isset($_GET['gid'])) {
			$this->contact_id = $_GET['gid'];
			$this->type = 'gid';
		}
		if(isset($_POST['uid'])) {
			$this->id = $_POST['uid'];
			$this->type = 'uid';
		}elseif(isset($_GET['uid'])) {
			$this->id = $_GET['uid'];
			$this->type = 'uid';
		}
	}
	
	public function Load_table() {
		$this->load->helper('template');
		if (isset($this->id))
			$table = load_websites($this->id,$this->type);
		print $table;
	}
	
	public function add() {
		$form = $this->input->post();
		if(isset($_GET['vid'])) {
			$add = $this->administration->addKnownVendorWebsite($form,$_GET['vid']);
			if($add) {
				print 1;	
			}else {
				print 0;
			}
		}elseif(isset($_GET['cid'])) {
			//var_dump($form);
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
		if(isset($_GET['vid'])) {
			$this->vendor_id = $_GET['vid'];
			$vendor = $this->administration->getVendor($_GET['vid']);
			$data = array(
				'selectedVendor'=>$vendor->ID,
				'type'=>$this->type,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE)
			);
		}elseif(isset($_GET[$this->type])) {
			$this->id = $_GET[$this->type];
			//get the right client info
			switch ($this->type) {
				case 'cid': $caller = $this->administration->getClient($this->id); break;
				case 'gid': $caller = $this->administration->getContact($this->id); $contact->ID = $contact->ContactID; break;
				case 'uid': $caller = $this->administration->getContact($this->id); break;
			}
			//vendors are not associated per client
			$vendors = $this->administration->getAllVendors();
			//prepare the array
			$data = array(
				'caller'=>$caller,
				'type'=>$this->type,
				'vendors'=>$vendors,
				'websites'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : false),
			);
			//print_object($this->administration->getWebsite($_GET['wid']));
			if ($data['website']) {
				$data['page'] = 'edit';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addwebsites',$data);
			} else {
				$data['page'] = 'add';
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addwebsites',$data);
			}
		}
	}
	
}
	