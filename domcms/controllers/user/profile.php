<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->helper(array('form','url','pass','file'));
		$this->load->model(array('members','administration'));
    }

    public function index() {
		$this->View();
    }

	public function View($msg = false) {
		
		if($this->input->post()) {
			$user_id = $this->input->post('user_id');
		}else {
			$user_id = $this->user['UserID'];	
		}
		
		$user                   = $this->administration->getUsers($user_id);
		$user->UserID           = $user->ID;
		$user->Edit       		= ($this->user['UserID'] == $user->UserID) ? TRUE : FALSE;
		
		//Grab avatar
		if($user->Avatar AND !$user->Google_Avatar) {
			$user->avatar       = base_url() . 'assets/uploads/avatars/' . $user->Avatar;	
		}else {
			if($user->Google_Avatar) {
				$user->avatar = $user->Avatar;
			}else {
				if($user->Gravatar) {
					$user->avatar   = $this->gravatar->get_gravatar($user->Gravatar);	
				}else {
					$user->avatar   = base_url() . 'assets/uploads/avatars/default_avatar.gif';
				}
			}
		}
			  
		$user->Company 			= $user->Dealership;
		$user->CompanyAddress   = ArrayWithTextIndexToString(mod_parser($user->CompanyAddress), true);
		$user->Emails 		    = OrderArrayForTableDisplay(mod_parser($user->Emails));
		$user->Phone 			= OrderArrayForTableDisplay(mod_parser($user->Phones));
		$user->UserModules 		= ParseModulesInReadableArray($user->Modules);
		
		
		$msg = (($msg) ? $msg : FALSE);
		$data = array(
			'user' => $user,
			'msg' => $msg,
			'admin' => $this->user
		);
			
		$this->LoadTemplate('pages/profile',$data);
	}
	
	public function Upload_avatar() {
		$profile_url = base_url() . 'profile/' . strtolower($this->user['FirstName'] . $this->user['LastName']);
		//print_object($this->input->post());
		$rootpath = $_SERVER['DOCUMENT_ROOT'];
		
		$config['upload_path'] = $rootpath . '/assets/uploads/avatars/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '1000';
		$config['max_width'] = '640';
		$config['max_height'] = '480';
		$config['file_name'] = $this->user['UserID'] . '_' . strtolower($this->user['FirstName']) . '_' . strtolower($this->user['LastName']) . "_" . createRandomString(30,"ALPHANUM") . '.jpg';
		
		$this->load->library('upload',$config);
		$avatar = 'avatar';
		if(!$this->upload->do_upload($avatar)) {
			$error = $this->upload->display_errors();
			redirect($profile_url . '/upload_avatar_error?error=' . $error);
		}else {
			$data = array('upload_data' => $this->upload->data());
			$filename = $data['upload_data']['file_name'];
			// the current logged in users profile url
			//log the url to the database
			$updateAvatar = $this->members->avatar_update($this->user['UserID'],$filename);
			// if the query was successfull, redirect back to the profile.
			if($updateAvatar) :
				redirect($profile_url,'refresh');
			else :
			    redirect($profile_url . '/upload_avatar_error','refresh');
			endif;
			
		}
	}
}
