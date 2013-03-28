<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends DOM_Controller {
	
	//should always have this
	public $client_id;
	
	//if were editing we should always have this
	public $website_id;
    public function __construct() {
        parent::__construct();
		//load the admin model
		$this->load->model('administration');
		
		if(isset($_POST['cid'])) {
			$this->client_id = $_POST['cid'];
		}elseif(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
		}
		if(isset($_POST['wid'])) {
			$this->website_id = $_POST['wid'];
		}elseif(isset($_GET['wid'])) {
			$this->website_id = $_GET['wid'];
		}
	}
	
	public function Load_table() {
		$this->load->helper('template');
		$table = load_client_websites($this->client_id);
		echo $table;
	}
	
	public function add() {
		if(isset($_GET['cid'])) {
			$form = $this->input->post();
			//var_dump($form);
			$add = $this->administration->addWebsiteInfo($form);
			if($add) {
				echo 1;
			}else {
				echo 0;
			}
		}else {
			echo 0;
		}
	}
	
	public function edit() {
		if(isset($_GET['wid'])) {
			$form = $this->input->post();
			$add = $this->administration->editWebsiteInfo($form);
			if($add) {
				echo 1;
			}else {
				echo 0;
			}
		}else {
			echo 0;
		}
	}
	
	public function disable() {
		
	}
	
	public function form() {
		$client = $this->administration->getClient($this->client_id);
		$vendors = $this->administration->getAllVendors();
		$data = array(
			'client'=>$client,
			'vendors'=>$vendors,
			'website'=>((isset($this->website_id)) ? $this->administration->getWebsite($this->website_id) : FALSE),
		);
		if(isset($this->website_id)) {
			$site = $this->administration->getWebsite($this->website_id);
			array_push($data,array('website'=>$site));
			//array_push($data, array('website' => $this->administration->getWebsite($this->website_id)));}
		}
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addwebsites',$data);
	}
	
}
	