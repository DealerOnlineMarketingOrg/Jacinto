<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends DOM_Controller {


	public $agency_id;
	public $group_id;
	public $level;

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $this->group_id  = $this->user['DropdownDefault']->SelectedGroup;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

    public function index() {
		//This is the listings page.
		//Check the permissions for this page, if the user has permission to view it, this will be 1, else 0;
		$permissions = $this->CheckModule('Group_List');
	
		if (!$permissions) {
			//If the user doesnt have permission to view the page, include access denied page.
			$this->AccessDenied();
		} else {
			
			$tmpl = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
	
			$groups = ($this->level != 'a') ? $this->administration->getSelectedGroupResults($this->group_id) : $this->administration->getAllGroupsInAgencyResults($this->agency_id);
			
			$tmpl .= '<thead>';
			$tmpl .= 	'<tr>';
			$tmpl .= 		'<th>Tags</th>';
			$tmpl .= 		'<th style="text-align:left;">Name</th>';
			$tmpl .= 		'<th style="text-align:center;">Member Of</th>';
			$tmpl .= 		'<th style="text-align:center;">Status</th>';
			$tmpl .=		'<th style="text-align:left;">Notes</th>';
			$tmpl .= 		'<th>Actions</th>';
			$tmpl .= 	'</tr>';
			$tmpl .= '</thead>';
			$tmpl .= '<tbody>';
			
			//This is the template for the dataTables table. This is used to automatically format with dataTables.
			$form = '';
			
			foreach($groups as $group) :
			
				//Instead of including the group id in the url, we post the GroupID to the form processor.
				//This is the attributes for the form open tag.
				$form_attr = array(
					'name' => 'edit_' . $group->GroupID,
					'id' => 'edit_form_' . $group->GroupID,
				);
				
				$tmpl .= '<tr class="tagElement ' . $group->ClassName . '">';
				
				//this is the edit button for each row in the table.
				//each button has different attribute values.
				//the form also has a hidden element, this is the group id being posted to the form processor.
				$button = array(
					'name'  => 'submit',
					'id' 	=> 'group_id_' . $group->GroupID,
					'class' => 'basicBtn',
					'value' => 'Edit'
				);
	
				if ($this->CheckModule('Group_Edit')) {
					$form = form_open('/groups/edit', $form_attr) . form_hidden('group_id', $group->GroupID) . form_submit($button) . form_close();
				}
				
				$tmpl .= '<td class="tags"><div class="' . $group->ClassName . '">&nbsp;</div></td>';
				$tmpl .= '<td class="client_name">' . $group->Name . '</td>';
				$tmpl .= '<td class="agency" style="width:155px;text-align:center;">' . $group->AgencyName . '</td>';
				$tmpl .= '<td class="status" style="width:75px;text-align:center;">' . (($group->Status) ? 'Active' : 'Disabled') . '</td>';
				$tmpl .= '<td class="notes" style="width:auto;text-align:left;"><p>' . (($group->Notes) ? $group->Notes : 'No notes available') . '</p></td>';
				$tmpl .= '<td class="editButtons" style="width:55px;">' . $form . '</td>';
				
			endforeach;
			
			$tmpl .= '</tbody>';
			$tmpl .= '</table>';
			//Starts the html for the page
			$html = '';
			
			//This builds the pages html out. We do this here so all our listing pages can have the same template view.
			$html .= $tmpl . "\n";
			$html .= '<div class="fix"></div>';
	
			//If the user has permission to add a new group, then show a button to do so.
			if ($this->CheckModule('Group_Add')) {
				$html .= anchor(base_url() . 'groups/add', 'Add New Group', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_group_btn"');
			}
	
			$data = array(
				'page_id' => 'Groups',
				'page_html' => $html
			);
			$this->LoadTemplate('pages/listings', $data);
			
		}
    }
	
	public function Add() {
		$html = '';
		
		$data = array(
			'html' => $html
		);
	
		$this->LoadTemplate('forms/form_addgroups',$data);
	}
	
	public function Edit() {
		
		if($this->level == 'c') {
			redirect('/clients/edit','refresh');	
		}
		
		if($this->level == 'a') {
			redirect('/agencies/edit','refresh');	
		}
		
		 //WE POST WHAT Group WERE EDITING, THIS IS THE ID IN THE DB.
		$group_id = ($this->input->post('group_id')) ? $this->input->post('group_id') : $this->group_id;
		$group    = $this->administration->getSelectedGroup($group_id);
		
		//PREPARE THE VIEW FOR THE FORM
		$data     = array(
			'group' => $group,
			'html' => $html
		);
		
		$this->LoadTemplate('forms/form_editgroups',$data);
	}
	
	public function Delete() {
		
	}
	
	public function View() {
		
	}

}
