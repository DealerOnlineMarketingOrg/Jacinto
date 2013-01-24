<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends DOM_Controller {

    var $user;

    public function __construct() {
        parent::__construct();
        //loading the member model here makes it 
        //available for any member of the dashboard controller.
        $this->load->helper('url');
        $this->user = $this->session->userdata('valid_user');
        $this->load->library('security');
    }

    public function name_changer() {
        $this->load->model('administration');
        
        $name = '<span>' . $this->input->get('Agency') . '</span>';
        $type = substr($this->input->get('Level'), 0, 1);
        $selected = substr($this->input->get('Level'), -1);
        $level = $this->generateLevelName($type);

        //rewrite session vars
        $this->user['DropdownDefault']->LevelType = $type;
        $this->user['DropdownDefault']->LevelID = $selected;
        $this->user['DropdownDefault']->SelectedID = $selected;
        $this->user['DropdownDefault']->PermLevel = $this->generateLevelNumber($level);
    
        
        switch ($type) {
            case 'a':
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getAgencyID($selected)->AgencyID;
                $this->user['DropdownDefault']->SelectedGroup = NULL;
                $this->user['DropdownDefault']->SelectedClient = NULL;
                break;
            case 'g':
                $this->user['DropdownDefault']->SelectedGroup  = $this->administration->getGroupID($selected)->GroupID;
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getGroupID($selected)->AgencyID;
                $this->user['DropdownDefault']->SelectedClient = NULL;
                break;
            case 'c':
                
                $group_id = $this->administration->getClientID($id)->GroupID;
                
                $this->user['DropdownDefault']->SelectedClient = $this->administration->getClientID($selected)->ClientID;
                $this->user['DropdownDefault']->SelectedGroup = $this->administration->getClientID($selected)->GroupID;
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getGroupID($selected)->AgencyID;
                break;
        }

        $this->session->sess_write();

        print $level . $name;
    }

    public function selected_dealer($selected_id) {

        $this->session->set_userdata['valid_user']['DropdownDefault']->SelectedID = $selected_id;

        //$this->session->userdata['valid_user']['DropdownDefault']->SelectedID = $selected_id;
        //$this->session->sess_write();
    }

    public function selected_tag() {
        $selected_tag = $this->input->post('selected_tag');
        $this->session->userdata['valid_user']['DropdownDefault']->SelectedTag = $selected_tag;
        $this->session->sess_write();
    }

    /*
      ADMIN CONTROLLERS
     */

    public function add_agency_popup() {
        $this->load->helper('formwriter');
        $data = array(
            'formName' => 'Add New Agency',
            'form' => AddAgencyForm()
        );
        $this->load->view(THEMEDIR . 'popups/basic_form', $data);
    }

}
