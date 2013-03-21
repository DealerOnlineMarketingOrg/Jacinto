<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Members extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('pass');
		$this->load->helper('string_parser');
        $this->load->helper('query');
    }
	
	public function logGoogleToken($email,$token) {
		$data = array(
			'oAuth_token' => $token
		);
		$this->db->where('USER_Name',$email);
		return ($this->db->update('Users',$data)) ? TRUE : FALSE;
	}
	
	public function avatar_update($user_id,$filename) {
		$data = array(
			'USER_Avatar' => $filename
		);
		$this->db->where('USER_ID',$user_id);
		return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
	}
	
	public function plus_avatar($email,$url) {
		
		$this->db->select('USER_ID as ID');
		$this->db->from('Users');
		$this->db->where('USER_Name', $email);
		
		$email = $this->db->get();
		
		if($email) {
			$data = array(
				'USER_Avatar' => $url,
				'Google_Avatar' => 1
			);
		
			$row = $email->row();
		
			$this->db->where('USER_ID',$row->ID);
			return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
		}else {
			return FALSE;
		}
	}
	
	public function get_user_avatar($user_id = false) {
		$this->load->helper('url');
		$this->load->library('gravatar');
		if($user_id) {
			$a_sql = 'SELECT Google_Avatar, USER_Avatar as Avatar FROM Users_Info WHERE USER_ID = "' . $user_id . '";';
			$a_query = $this->db->query($a_sql);
			$customAvatar = $a_query->row();
			if($customAvatar->Google_Avatar != 1) {
				if($customAvatar->Avatar != NULL) {
					return ((file_exists(base_url() . 'assets/uploads/avatars/' . $customAvatar->Avatar)) ? base_url() . 'assets/uploads/avatars/' . $customAvatar->Avatar : base_url() . 'assets/themes/itsbrain/imgs/icons/color/users.png');
				}else {
					$g_sql = 'SELECT USER_GravatarEmail as Gravatar FROM Users_Info WHERE USER_ID = "' . $user_id . '";';
					$g_query = $this->db->query($g_sql);
					$gravatar = $g_query->row();
					if($gravatar->Gravatar) {
						return $this->gravatar->get_gravatar($gravatar->Gravatar);
					}else {
						return base_url() . 'assets/themes/itsbrain/imgs/icons/color/users.png';
					}
				}
			}else {
				return $customAvatar->Avatar;	
			}
		}else {
			return base_url() . 'assets/themes/itsbrain/imgs/icons/color/users.png';
		}
	}
    
	public function validate($email,$password = false,$oAuth_token = false,$skip_encrypt = false) {
		$email = $this->security->xss_clean($email);
		
		if($password AND !$skip_encrypt) {
			$password = encrypt_password($this->security->xss_clean($password));
		}
		
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->join('Users_Info','Users.USER_ID = Users_Info.USER_ID');
		$this->db->join('Directories','Directories.DIRECTORY_ID = Users_Info.DIRECTORY_ID');
		$this->db->join('xSystemAccess','xSystemAccess.ACCESS_ID = Users_Info.Access_ID');
		$this->db->join('Clients','Clients.CLIENT_ID = Users_Info.CLIENT_ID');
		$this->db->join('Groups','Groups.GROUP_ID = Clients.GROUP_ID');
		$this->db->join('Agencies','Agencies.AGENCY_ID = Groups.AGENCY_ID');
		$this->db->where('Users.USER_Name',$email);
		
		if($password) {
			$this->db->where('Users_Info.USER_Password',$password);
		}elseif(!$password AND $oAuth_token) {
			$this->db->where('Users.oAuth_token',$oAuth_token);	
		}

		$query = $this->db->get();
			
	    if($query->num_rows() == 1) {
		   $row = $query->row();
		   //This array becomes our session array, any data we want to travel from page to page, needs to be defined here.
		   //Start drop down default insert for session
		   $ClientID 	= $row->CLIENT_ID;
		   $GroupID 	= $row->GROUP_ID;
		   $AgencyID 	= $row->AGENCY_ID;
		   $AccessLevel = $row->ACCESS_Level;
		   
		   //process levels of users for drop down
		   //If the access level is greater than or equel to 600,000 (SuperAdmin)
		   if($AccessLevel >= 600000) : 
				$dropdown_defaults = array(
				   'LevelID' 		=> $AgencyID,
				   'PermType' 		=> 'SuperAdmin',
				   'PermLevel'		=> 600000,
				   'LevelType'      => 1,
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> FALSE
				);
			//If the access level is less than 600,000 and greater than or equel to 500,000
			elseif($AccessLevel < 600000 && $AccessLevel >= 500000) : 
				$dropdown_defaults = array(
				   'LevelID' 		=> $AgencyID,
				   'PermType' 		=> 'AgencyAdmin',
				   'PermLevel'		=> 500000,
				   'LevelType'      => 2,
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> FALSE
				);
			//If the access level is less than 500,000 and greater than or equel to 400,000
			elseif($AccessLevel < 500000 && $AccessLevel >= 400000) :
				$dropdown_defaults = array(
				   'LevelID' 		=> $GroupID,
				   'PermType' 		=> 'GroupAdmin',
				   'PermLevel'		=> 400000,
				   'LevelType'      => 3,
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
			elseif($AccessLevel < 400000 AND $AccessLevel >= 300000) :
				$dropdown_defaults = array(
				   'LevelID' 		=> $ClientID,
				   'PermType' 		=> 'ClientAdmin',
				   'PermLevel'		=> 300000,
				   'LevelType'      => 4,
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
			elseif($AccessLevel < 300000 AND $AccessLevel >= 200000) :
				$dropdown_defaults = array(
				   'LevelID' 		=> $ClientID,
				   'PermType' 		=> 'Manager',
				   'PermLevel'		=> 200000,
				   'LevelType'      => 5,
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
			else:
				$dropdown_defaults = array(
				   'LevelID' 		=> $ClientID,
				   'PermType' 		=> 'User',
				   'PermLevel'		=> 100000,
				   'LevelType'      => 6,
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
		   endif;

		   $data = array(
			   'Username' 		 => (string)$row->USER_Name,
			   'FirstName' 		 => (string)$row->DIRECTORY_FirstName,
			   'LastName' 		 => (string)$row->DIRECTORY_LastName,
			   'Emails' 		 => (object)mod_parser($row->DIRECTORY_Email),
			   'Gravatar'		 => (string)$row->USER_GravatarEmail,
			   'Avatar'			 => (string)$row->USER_Avatar,
			   'UserID' 		 => (int)$row->USER_ID,
			   'DirectoryID' 	 => (int)$row->DIRECTORY_ID,
			   'ClientID' 	     => (int)$row->CLIENT_ID,
			   'GroupID' 	     => (int)$row->GROUP_ID,
			   'AgencyID' 	     => (int)$row->AGENCY_ID,
			   'ClientName' 	 => (string)$row->CLIENT_Name,
			   'ClientAddress' 	 => (object)group_parser($row->CLIENT_Address),
			   'ClientPhone' 	 => (object)group_parser($row->CLIENT_Phone),
			   'ClientNotes' 	 => (string)$row->CLIENT_Notes,
			   'ClientCode' 	 => (string)$row->CLIENT_Code,
			   'ClientActive' 	 => (bool)$row->CLIENT_Active,
			   'ClientActiveTS'  => date(FULL_MILITARY_DATETIME, strtotime($row->CLIENT_ActiveTS)),
			   'AccessLevel' 	 => (int)$row->ACCESS_Level,
			   'AccessName' 	 => (string)$row->ACCESS_Name,
			   'UserModules' 	 => $this->UserModules(mod_parser($row->USER_Modules)),
			   'isActive' 		 => (bool)$row->USER_Active,
			   'TimeActive' 	 => date(FULL_MILITARY_DATETIME, strtotime($row->USER_ActiveTS)),
			   'isGenerated' 	 => (int)$row->USER_Generated,
			   'CreatedOn' 		 => date(FULL_MILITARY_DATETIME, strtotime($row->USER_Created)),
			   'validated' 		 => (bool)TRUE,
			   'DropdownDefault' => (object)$dropdown_defaults,
			   'google_token' => (($oAuth_token) ? $oAuth_token : FALSE)
		   );
		   		   
		   $this->session->set_userdata('valid_user', $data);
		   //$this->session->sess_write();
		   return (object)$data;

	   }
   }  

   public function AuthenticateGoogleUser($email,$token) {
   	$log = FALSE;
	
	//log token
	if(isset($_SESSION['token'])) {
		
		$data = array(
			'oAuth_token' => $token
		);
		
		$this->db->where('USER_Name',$email);
		$log = $this->db->update('Users',$data);
	}
	
   	if($log) {
   		$this->db->select('*');
		$this->db->from('Users');
		$this->db->join('Users_Info', 'Users.USER_ID = Users_Info.USER_ID');
		$this->db->where('USER_Name',$email);
		$query = $this->db->get();
		
		if($query) {
			$user = $query->row();		
			//print_object($user);
			$valid_user = $this->validate($email,$user->USER_Password,false,true);
			if($valid_user) {
				return TRUE;
			}
		}
		return FALSE;
   	}
   }

   public function save_google_avatar($email,$avatar) {
   	 $this->db->select('USER_ID');
	 $this->db->from('Users');
	 $this->db->where('USER_Name',$email);
	 $uid = $this->db->get();
	 $uid = $uid->row()->USER_ID;
	 
	 if($uid) {
	 	$data = array(
			'USER_Avatar' => $avatar,
			'Google_Avatar' => 1
		);
		$this->db->where('USER_ID',$uid);
		return ($this->db->update('Users_Info',$data) ? TRUE : FALSE);
	 }

	return FALSE;
   }
   
   public function checkPasswordGeneration($email) {
		$sql = 'SELECT u.USER_ID as ID, ui.USER_Generated as IsGenerated FROM Users u INNER JOIN Users_Info ui ON u.USER_ID = ui.USER_ID WHERE u.USER_Name = "' . $email . '"';
		$query = $this->db->query($sql)->row();
		if($query) {
			return $query->IsGenerated;	
		}else {
			return 'ERROR';	
		}
   }
   
   public function UserModules($mods) {
		$modules = array();
		foreach($mods as $key => $value) {
			if($value != 0) {
				$sql = "SELECT * FROM xModules WHERE MODULE_ID = '" . $key . "';";	
				$query = $this->db->query($sql);
				if($query) {
					array_push($modules,$query->row());	
				}
			}
		}
		return $modules;
   }
   
   public function valid_email($email) {
		$sql = 'SELECT USER_Name from Users WHERE USER_Name = "' . $email . '";';
		$query = $this->db->query($sql);
		if($query)
			return TRUE;
		else
			return FALSE;   
   }
   
   public function reset_password($email) {
		$this->load->helper('msg_helper');
		$email = $this->security->xss_clean($this->input->post('email'));
		$new_pass = createRandomString(10,'ALPHANUMSYM');
		
        $sql = 'SELECT * FROM Users WHERE USER_Name = "' . $email . '";';
        $query = $this->db->query($sql);
        
        if($query->num_rows() == 1) {
            $user_row = $query->row();
            $user_id = $user_row->USER_ID;
            
            $update_sql = "UPDATE Users_Info SET USER_Password = '" . encrypt_password($new_pass) . "', USER_Generated='1' WHERE USER_ID = '" . $user_id . "';";
            
            $update = $this->db->query($update_sql);
            
            if($update) {
                //return TRUE;
                $subject = 'Password Reset';
				$msg = email_reset_msg($new_pass);
                $emailed = $this->email_results($email, $subject, $msg);
                if($emailed) {
                    return TRUE;
                }else {
                    return FALSE;
                }
            }else {
                return FALSE;
            }
        }
        return FALSE;
    }
	
	public function validateUser($email, $password) {
		$this->load->helper('pass');
		$password = encrypt_password($password);
		
		//validate against the database
		$sql = 'SELECT u.USER_ID as ID FROM Users u RIGHT JOIN Users_Info ui ON u.USER_ID = ui.USER_ID WHERE u.USER_Name = "' . $email . '" AND ui.USER_Password = "' . $password . '"';
		
		$query = $this->db->query($sql)->row();
		
		//if the query found a match, return true, return false.
		if($query) : 
			return $query->ID;
		else : 
			return FALSE;
		endif;
				
	}
	
	public function change_password($email, $oldPass, $newPass) {
		$this->load->helper('pass');
		$savedPass = $newPass;
		$newPass = encrypt_password($newPass);
		//validate users old credentials
		$isValidUser = $this->validateUser($email,$oldPass);
		
		if(!$isValidUser) :
			return FALSE;
		else :
			$data = array(
				'USER_Password' => $newPass,
				'USER_Generated' => 0
			);
			$this->db->where('USER_ID', $isValidUser);
			
			// If the update succeeds, create the session and return the object to the system.
			if(!$this->db->update('Users_Info',$data)) {
				return FALSE;
			}else {
				$user = $this->validate($email,$savedPass);
				return $user;
			}
		endif;
	}
	
    public function email_results($email, $subject, $msg) {
        $this->load->library('email');
        $this->email->from('no-reply@dealeronlinemarketing.com','Dealer Online Marketing');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($msg);
        if($this->email->send()) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function addUsers($data) {
        $insert = $this->db->insert('Users',$data);
        if($insert) {
            $sql = 'SELECT USER_ID as ID FROM Users WHERE USER_Name = "' . $data['USER_Name'] . '" LIMIT 1';
            $results = query_results($this,$sql);
            return $results;
        }else {
            return false;
        }
    }
    
    public function addDirectory($data) {
        $insert = $this->db->insert('Directories',$data);
        if($insert) {
            //get id of inserted
            $sql = 'SELECT DIRECTORY_ID as ID FROM Directories WHERE DIRECTORY_FirstName = "' . $data['DIRECTORY_FirstName'] . '" AND DIRECTORY_LastName = "' . $data['DIRECTORY_LastName'] . '" AND DIRECTORY_Email ="' . $data['DIRECTORY_Email'] . '" LIMIT 1;';          
            $results = query_results($this, $sql);
            return $results;
        }else {
            return false;
        }    
    }
    
    public function addUserInfo($data) {
        $insert = $this->db->insert('Users_Info',$data);
        if($insert) {
            return true;
        }else {
            return false;
        }
    }
    
}