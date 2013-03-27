<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends DOM_Controller {
	
	public $level;
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('members','administration','utilities'));
        $this->load->helper(array('template','msg','html'));
        $this->level = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

    public function index() {
		$html = '';
		$table = '';
		if($this->level == 1 OR $this->level == 'a') {
			$agency_id = $this->user['DropdownDefault']->SelectedAgency;	
			$groups = $this->administration->getAllGroupsInAgencyResults($agency_id);
			$users = array();
			foreach($groups as $group) {
				//print_object($group);
				$clients = $this->administration->getAllClientsInGroup($group->GroupID);	
				//print_object($clients);
				foreach($clients as $client) {
					$userGrouped = $this->administration->getUsers(false,$client->ClientID);
					if($userGrouped) {
						foreach($userGrouped as $usersGroup) {
							array_push($users,$usersGroup);	
						}
					}
					
				}
			}
		}elseif($this->level == 2 OR $this->level == 'g') {
			$group_id = $this->user['DropdownDefault']->SelectedGroup;	
			$clients = $this->administration->getAllClientsInGroup($group_id);
			$users = array();
			foreach($clients as $client) {
				$userGrouped = $this->administration->getUsers(false,$client->ClientID);
				foreach($userGrouped as $usersGroup) {
					array_push($users,$usersGroup);	
				}
			}
		}elseif($this->level == 3 OR $this->level == 'c') {
			$client_id = $this->user['DropdownDefault']->SelectedClient;	
			$users = $this->administration->getUsers(false,$client_id);
		}
		
		if($users) {
			
			$table .= '<table style="width:100%" cellpadding="0" cellspacing="0" class="tableStatic">';
			$table .= '<thead><tr><td>Team</td><td>Avatar</td><td>Email Address</td><td>Name</td><td>Status</td><td>Member Since</td><td>Actions</td></tr></thead>';
			$table .= '<tbody>';
			foreach ($users as $user) {
				$table .= '<tr class="tagElement ' . $user->ClassName . '">';
				$form_cred = array(
					'name' => 'edit_user',
					'id' => 'user_' . $user->ID
				);
				
				//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
				$button = array(
					'name' => 'submit',
					'id' => 'user_id_' . $user->ID,
					'class' => 'redBtn',
					'value' => 'Edit'
				);
				
				$view_button = array(
					'name'=>'view',
					'id'=>'view_user_'.$user->ID,
					'class'=>'blueBtn',
					'value'=>'View'
				);
				
				$green_check = '<img src="' . base_url() . THEMEIMGS . 'icons/notifications/accept.png" alt="Enabled" />';
				$disabled_ex = '<img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disabled" />';

				$avatar = $this->members->get_user_avatar($user->ID);
				
				$view_form = form_open('profile/'.strtolower($user->FirstName . $user->LastName),array('name'=>'view_user','id'=>'form_' . $user->ID,'style'=>'float:left;')) . form_hidden('user_id',$user->ID) . '<img class="viewBtn" id="view_user_' . $user->ID . '" src="' . base_url() . 'assets/themes/global/imgs/icons/dark/user.png" alt="View Profile" />' . form_close();
				$disable_user = '<a href="javascript:disableUser(\'' . $user->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable User" /></a>';
				$enable_user = '<a href="javascript:enableUser(\'' . $user->ID . '\');"><img src="' . base_url() . THEMEIMGS . 'icons/notifications/accept.png" alt="Enable User" /></a>';
				
				$table .= '<td class="tags" style="width:35px;"><div class="' . $user->ClassName . '">&nbsp;</div></td>';
				//grab users avatar
				$table .= '<td style="width:30px;"><div align="center"><img style="width:25px;" src="' . $avatar . '" /></div></td>';
				
				$table .= '<td>' . '<a href="mailto:' . $user->Username . '">' . $user->Username . '</a>' . '</td>';
				$table .= '<td>' . $user->FirstName . ' ' . $user->LastName . '</td>';
				$table .= '<td style="width:30px;text-align:center;">' . (($user->Status != 1) ? $disabled_ex : $green_check) . '</td>';
				$table .= '<td style="width:65px;text-align:center;">' . date('n-j-Y',strtotime($user->JoinDate)) . '</td>';
				$table .= '<td style="width:40px;"><div align="center">' . $view_form . (($user->Status != 1) ? $enable_user : $disable_user) . '</div></td>';
				$table .= '</tr>';				
			}
			$table .= '</tbody></table>
				<script type="text/javascript">
					jQuery(".viewBtn").click(function() { jQuery(this).parent().submit(); });
				</script>';
		}else {
			$table = '<p style="text-align:center">No users are associated for this client. Please use the Dealer Dropdown to select another client, or add users by clicking the "Add New User" button below.</p>';	
		}
		
		//BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
		$html .=  $table;

		if ($this->CheckModule('User_Add')) {
			$html .= anchor(base_url() . 'users/add', 'Add New User', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_user_btn"');
		}
		
		//Prepare data for template in $data array; when in template, you call the keys like vars: example => $html;
		$data = array(
			'page_id' => 'Users',
			'page_html' => $html
		);

		
		$this->LoadTemplate('pages/listings', $data);
    }
	
	public function Add() {
		$html = '';
		$data = array(
		  'html' => $html  
		);
		$this->LoadTemplate('forms/form_adduser',$data);
	}
	
	public function Edit() {
		
		$html = '';

		$user_id = $this->input->post('user_id');
		$user = $this->administration->getUsers($user_id);
						
		$data = array(
			'user' => $user,
		);
		
		$this->LoadTemplate('forms/form_edituser', $data);
	}
	
	public function Delete() {
		
	}
	
	public function Disable() {
		$this->load->model('members');
		$user_id = $this->input->post('user_id');
		
		$disable = $this->members->disable_user($user_id);
		if($disable) {
			echo TRUE;
		}else {
			echo FALSE;
		}
	}
	
	public function Enable() {
		$this->load->model('members');
		$user_id = $this->input->post('user_id');
		
		$enable = $this->members->enable_user($user_id);
		if($enable) {
			echo TRUE;
		}else {
			echo FALSE;
		}
	}
	
	public function View() {
		//the user posted to the view.
		$user_id = $this->input->post('user_id');
		$user = $this->administration->getUsers($user_id);
		//print_object($user);
		$user->Name = $user->FirstName . ' ' . $user->LastName;
		$users_address = '';
		if($user->Address['street'] != '') {
			foreach($user->Address as $address) {
				$users_address .= $address . ' ';
			}
		}else {
			$users_address = FALSE;	
		}
		$user->Address = $users_address;		
		$user->Notes = (($user->Notes) ? $user->Notes : FALSE);
		$data = array(
			'display' => $user
		);
		$this->LoadTemplate('pages/details_user',$data);
	}

}
