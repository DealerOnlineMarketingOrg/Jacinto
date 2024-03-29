<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends DOM_Controller {
	
	public $level;
	public $user_id;
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('members','administration','utilities'));
        $this->load->helper(array('template','msg','html','file'));
        $this->level = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
		
		if(isset($_GET['uid'])) {
			$this->user_id = $_GET['uid'];	
		}else {
			$this->user_id = FALSE;	
		}
    }

	public function Load_table($return = false) {
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/users/table');
	}

    public function Index() {
		$this->LoadTemplate('pages/users/listing');
    }
	
	public function Add() {
		$html = '';
		$data = array(
		  'html' => $html
		);
		$this->LoadTemplate('forms/users/add',$data);
	}
	
	public function Edit_avatar_form() {
		$data = array(
			'user_id'=>$this->user_id
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/users/edit_avatar',$data);	
	}
	
	public function Upload_avatar() {
		$user = $this->administration->getMyUser($this->user_id);
		$profile_url = base_url() . 'profile/' . strtolower($user->FirstName . $user->LastName);
		//print_object($this->input->post());
		$rootpath = $_SERVER['DOCUMENT_ROOT'];
		
		$config['upload_path'] = $rootpath . '/assets/uploads/avatars/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '1000';
		$config['max_width'] = '640';
		$config['max_height'] = '480';
		$config['file_name'] = $this->user_id . '_' . strtolower($user->FirstName) . '_' . strtolower($user->LastName) . "_" . createRandomString(30,"ALPHANUM") . '.jpg';
		
		$this->load->library('upload',$config);
		$avatar = 'avatar';
		if(!$this->upload->do_upload($avatar)) {
			$error = $this->upload->display_errors();
			redirect('/users?trigger=' . $this->user_id . '&cem=' . $error,'location');
		}else {
			$data = array('upload_data' => $this->upload->data());
			$filename = $data['upload_data']['file_name'];
			// the current logged in users profile url
			//log the url to the database
			$updateAvatar = $this->members->avatar_update($this->user_id,$filename);
			// if the query was successfull, redirect back to the profile.
			if($updateAvatar) :
				redirect('/users?trigger=' . $this->user_id,'location');
			else :
			    redirect('/users?trigger=' . $this->user_id . '&e=2','location');
			endif;
		}
	}
	
	public function Import_google_avatar() {
		$import = $this->members->activateGoogleAvatar($this->user_id);
		if($import) {
			echo '1';
		}else {
			echo '0';	
		}
	}
	
	public function Add_user_Form() {
		$this->load->model('mlist');
		$data = array(
			'dealerships'=>$this->mlist->getClients(),
			'SecurityLevels'=>$this->members->getSecurityLevels(),
			'tags'=>$this->administration->getAllTags(),
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/users/add',$data);	
	}
	
	public function Submit_add_user() {
		$this->load->helper('pass');
		$form = $this->input->post();
		$modules = $this->members->getDefaultModules($form['security_level']);
		
		$user_update = array(
			'USER_Name'=>$form['username'],
			'Team'=>$form['team']
		);
		
		$directory_update = array(
			'DIRECTORY_Type'=>'CID:' . $form['dealership'],
			'CLIENT_Owner'=>$form['dealership'],
			'DIRECTORY_Address'=>'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zipcode'],
			'DIRECTORY_Tag'=>$form['team'],
			'DIRECTORY_Email'=>'work:'.$form['username'],
			'DIRECTORY_Primary_Email'=>$form['username']
			
		);
		
		$user_info_update = array(
			'ACCESS_ID' => $form['security_level'],
			'USER_Modules'=>$modules,
			'CLIENT_ID'=>$form['dealership'],
			'USER_Active'=>1,
			'USER_Generated'=>1,
		);
		
		$add_user = $this->administration->addNewUser($user_update,$directory_update,$user_info_update);
		
		if($add_user) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Edit() {
		if(isset($_GET['UID'])) {
			$uid = $_GET['UID'];
		}
		
		$user = $this->administration->getMyUser($uid);
		$user->ContactID = $user->DirectoryID;
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Email = mod_parser($user->Emails,false,true);
		$user->Phone = mod_parser($user->Phones,false,true);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $this->members->get_user_avatar($user->ID);
		$user->TypeCode = substr($user->UserType,0,3);
		$user->TypeID = substr($user->UserType,4);
		
		$data = array(
			'user'=>$user,
			'avatar'=>$avatar,
			'allMods'=>$this->administration->getAllModules(),
			'websites'=>WebsiteListingTable($uid, 'UID'),
			'contact'=>$user,
			'contactInfo'=>ContactInfoListingTable($user, 'UID', true),
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/users/edit_add_view',$data);
	}
	
	public function Edit_details_form() {
		//gonna use the clients from the masterlist function because it auto filters based on the dealer dropdown
		$this->load->model('mlist');
		$dealerships = $this->mlist->getClients();
		$user = $this->administration->getMyUser($this->user_id);
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Email = mod_parser($user->Emails,false,true);
		$user->Phone = mod_parser($user->Phones,false,true);
		$data = array(
			'user'=>$user,
			'dealerships'=>$dealerships
		);	
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/users/edit_details',$data);
	}
	
	public function Submit_user_details_form() {
		$form = $this->input->post();
		
		$did = $this->administration->getDirectoryID($this->user_id);
		
		if($did) {
			$directory_data = array(
				'DIRECTORY_FirstName' => $form['first_name'],
				'DIRECTORY_LastName'=>$form['last_name'],
				'DIRECTORY_Address'=>'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zipcode']
			);	
			
			$user_data = array(
				'USER_Name'=>$form['username']
			);
			
			$update_directory = $this->administration->updateDirectory($did,$directory_data);
			$update_users = $this->administration->udpateUserName($this->user_id,$user_data);
			
			if($update_directory AND $update_users) {
				echo '1';	
			}elseif($update_directory AND !$update_users) {
				echo '2';	
			}elseif(!$update_directory AND $update_users) {
				echo '3';	
			}else {
				echo '0';	
			}
		}
	}
	
	public function Submit_user_edit_modules() {
		$form = $this->input->post();
		$modules = '';
		$i = 1;
		$count = count($form['modules']);
		
		foreach($form['modules'] as $key => $value) {
			$modules .= $key . ':' . $value . (($i < $count) ? ',' : '');
			$i++;
		}
		
		$update_mods = $this->administration->updateUserModules($this->user_id,$modules);
		
		if($update_mods) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function View_popup() {
		if(isset($_GET['UID'])) {
			$uid = $_GET['UID'];
		}
		
		$user = $this->administration->getMyUser($uid);
		$user->ContactID = $user->DirectoryID;
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Email = mod_parser($user->Emails,false,true);
		$user->Phone = mod_parser($user->Phones,false,true);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $this->members->get_user_avatar($user->ID);
		
		$user->TypeCode = substr($user->UserType,0,3);
		$user->TypeID = substr($user->UserType,4);
		
		$data = array(
			'user'=>$user,
			'view'=>TRUE,
			'avatar'=>$avatar,
			'allMods'=>$this->administration->getAllModules(),
			'websites'=>WebsiteListingTable($uid, 'UID', false),
			'contact'=>$user,
			'contactInfo'=>ContactInfoListingTable($user, 'UID'),
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/users/edit_add_view',$data);
	}
	
	public function Change_pass_form() {
		$user = $this->administration->getMyUser($this->user_id);
		$data = array(
			'user'=>$user
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/users/change_password',$data);
	}
	
	/*This will change the users password to whatever the user wants it to be*/
	public function Change_user_password() {
		$this->load->helper('pass');
		$user = $this->administration->getMyUser($this->user_id);
		//password
		$old_pass = $this->input->post('oldPassword');
		$newPass = $this->input->post('newPassword');
		$confirmNewPass = $this->input->post('confirmNewPassword');
		if($newPass == $confirmNewPass) {
			//check if old password is corrent
			$verify = $this->members->validateUser($user->Username,$old_pass);
			if($verify) {
				//validate if the password is in the correct format
				$valid_new_pass = checkPasswordCharacters($newPass);
				if($valid_new_pass) {
					$change_pass = $this->members->simple_pass_change($user->ID,$newPass);
					if($change_pass) {
						echo '1';
					}else {
						echo '6';	
					}
				}else {
					echo '7';	
				}
			}else {
				echo '8';	
			}
		}else {
			echo '9';	
		}
	}
	
	public function Reset_user_password() {
		$this->load->helper('pass');
		$user = $this->administration->getMyUser($this->user_id);
		//set the users primary email address
		$user->Emails = mod_parser($user->Emails,false,true);
		// Locate primary.
		foreach ($user->Emails as $userEmails) foreach ($userEmails as $type => $email) {
			if ($email == $user->PrimaryEmailType) {
				$primaryEmail = $email;
				break;
			}
		}
		//generate a new password
		$newPass = createRandomString();
		//this will also email the user their email address, this is sent to the users PRIMARY email, which could differ from the email they are logging in with.
		$reset = $this->members->simple_reset_pass($user->Username,$user->ID,$newPass);
		if($reset) {
			echo $newPass;	
		}else {
			echo '0';	
		}
	}
	
	public function View() {
		//the user posted to the view.
		$user_id = $this->input->post('user_id');
		$user = $this->administration->getUsers($user_id);
		//print_object($user);
		$user->Name = $user->FirstName . ' ' . $user->LastName;
		$users_address = '';
		
		$user->TypeCode = substr($user->UserType,0,3);
		$user->TypeID = substr($user->UserType,4);
		
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
			'display' => $user,
		);
		$this->LoadTemplate('pages/users/view',$data);
	}
	
}