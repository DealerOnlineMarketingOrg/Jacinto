<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends DOM_Controller {

	public $agency_id;
	public $group_id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

    public function index() {
		$groups = $this->administration->getGroups($this->agency_id);
		$data = array(
			'groups' => $groups
		);
		$this->LoadTemplate('pages/group_listing',$data);
    }
	
	public function load_table() {
		$groups = $this->administration->getGroups($this->agency_id);
		$data = array(
			'groups'=>$groups
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/group_listing_table',$data);
	}
	
	public function View() {
		if(isset($_GET['gid'])) {
			$group_id = $_GET['gid'];	
		}else {
			$group_id = $this->user['DropdownDefault']->SelectedGroup;	
		}
		
		$group = $this->administration->getGroup($group_id);
		if($group) {
			$data = array(
				'group' => $group,
			);
			$this->load->view($this->theme_settings['ThemeDir'] . '/pages/view_group',$data);
		}
	}
	
	public function Add() {
	}
	
	public function Edit() {
		if(isset($_GET['gid'])) {
			$group_id = $_GET['gid'];	
		}else {
			$group_id = $this->user['DropdownDefault']->SelectedGroup;	
		}
		
		$group = $this->administration->getGroup($group_id);
		$agencies = $this->administration->getAgencies();
		if($group) {
			$myAgencies = array();
			foreach($agencies as $agency) {
				$myAgencies[$agency->ID] = $agency->Name;	
			}
			
			$data = array(
				'group'=>$group,
				'agencies'=>$myAgencies
			);	
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddgroup',$data);
		}else {
			echo '0';	
		}
	}

}
