<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administration extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('query');
        $this->load->helper('string_parser');
    }
	
	public function disableWebsite($wid) {
		//we need the client id
		$this->db->select('CLIENT_ID as ClientID');
		$this->db->from('Websites');
		$this->db->where('WEB_ID',$wid);
		$client_id = $this->db->get();
		if($client_id) {
			$client_id = $client_id->row()->ClientID;
			$this->db->where('WEB_ID',$wid);
			$update = ($this->db->update('Websites',array('WEB_Active'=>0)) ? TRUE : FALSE);	
			if($update) {
				return $client_id;	
			}else {
				return FALSE;	
			}
		}else {
			return FALSE;	
		}
	}
	
	public function enableWebsite($wid) {
		//we need the client id
		$this->db->select('CLIENT_ID as ClientID');
		$this->db->from('Websites');
		$this->db->where('WEB_ID',$wid);
		$client_id = $this->db->get();
		if($client_id) {
			$client_id = $client_id->row()->ClientID;
			$this->db->where('WEB_ID',$wid);
			$update = ($this->db->update('Websites',array('WEB_Active'=>1)) ? TRUE : FALSE);	
			if($update) {
				return $client_id;	
			}else {
				return FALSE;	
			}
		}else {
			return FALSE;	
		}
	}
	
	public function editWebsiteInfo($formdata) {
		$data = array(
			'CLIENT_ID' => $formdata['ClientID'],
			'WEB_Vendor' => $formdata['vendor'],
			'WEB_GoogleUACode'=>$formdata['ua_code'],
			'WEB_GoogleWebToolsMetaCode'=>$formdata['meta_code_number'],
			'WEB_GooglePlusCode'=>$formdata['gplus_code'],
			'WEB_BingCode'=>$formdata['bing_code'],
			'WEB_YahooCode'=>$formdata['yahoo_code'],
			'WEB_GlobalScript'=>$formdata['global_code'],
			'WEB_Url'=>$formdata['url'],
			'WEB_Notes'=>$formdata['notes'],
			'WEB_ActiveTS'=>date(FULL_MILITARY_DATETIME),
			//'WEB_Created'=>date(FULL_MILITARY_DATETIME)
		);
		$this->db->where('WEB_ID',$formdata['web_id']);
		return ($this->db->update('Websites',$data) ? TRUE :FALSE);
	}
	
	public function addWebsiteInfo($formdata) {
		$vid = FALSE;
		if($formdata['custom_vendor'] != '') {
			$vendor = array(
				'VENDOR_Name' => $formdata['custom_vendor'],
				'VENDOR_Active' => 1,
				'VENDOR_ActiveTS' => date(FULL_MILITARY_DATETIME),
				'VENDOR_Created' => date(FULL_MILITARY_DATETIME)
			);	
			$vendor = $this->db->insert('Vendors',$vendor);
			if($vendor) {
				$this->db->select('VENDOR_ID as ID');
				$this->db->from('Vendors');
				$this->db->where('VENDOR_Name',$formdata['custom_vendor']);
				$vid = $this->db->get();
				if($vid) {
					$vid = $vid->row()->ID;	
				}
			}
		}
		$data = array(
			'CLIENT_ID' => $formdata['ClientID'],
			'WEB_Vendor' => ($vid) ? $vid : $formdata['vendor'],
			'WEB_GoogleUACode'=>$formdata['ua_code'],
			'WEB_GoogleWebToolsMetaCode'=>$formdata['meta_code_number'],
			'WEB_GooglePlusCode'=>$formdata['gplus_code'],
			'WEB_BingCode'=>$formdata['bing_code'],
			'WEB_YahooCode'=>$formdata['yahoo_code'],
			'WEB_GlobalScript'=>$formdata['global_code'],
			'WEB_Type'=>'cid:' . $formdata['ClientID'],
			'WEB_Url'=>$formdata['url'],
			'WEB_Active'=>'1',
			'WEB_Notes'=>$formdata['notes'],
			'WEB_ActiveTS'=>date(FULL_MILITARY_DATETIME),
			'WEB_Created'=>date(FULL_MILITARY_DATETIME)
		);
		
		return ($this->db->insert('Websites',$data) ? TRUE : FALSE);
	}
	
	public function getWebsite($wid) {
		$this->db->select('w.WEB_ID as ID,w.WEB_Vendor as Vendor,w.WEB_GoogleUACode as GoogleUACode,w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,w.WEB_GooglePlusCode as GooglePlusCode,w.WEB_BingCode as BingCode,w.WEB_YahooCode as YahooCode,w.WEB_GlobalScript as GlobalScript,w.WEB_Type as Type,w.WEB_Url as URL,w.WEB_Notes as Description,w.WEB_Active as Status,w.WEB_ActiveTS as LastUpdate,w.WEB_Created as Created,v.VENDOR_Name as VendorName,v.VENDOR_Address as VendorAddress,v.VENDOR_Phone as VendorPhone,v.Vendor_Notes as VendorDescription,v.VENDOR_Active as VendorStatus');
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID');
		$this->db->where('w.WEB_ID',$wid);
		$website = $this->db->get();
		
		return ($website) ? $website->row() : FALSE;
	}
	
	public function getClientWebsites($cid,$wid=false) {
		$this->db->select('w.WEB_ID as ID,w.WEB_Vendor as Vendor,w.WEB_GoogleUACode as GoogleUACode,w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,w.WEB_GooglePlusCode as GooglePlusCode,w.WEB_BingCode as BingCode,w.WEB_YahooCode as YahooCode,w.WEB_GlobalScript as GlobalScript,w.WEB_Type as Type,w.WEB_Url as URL,w.WEB_Notes as Description,w.WEB_Active as Status,w.WEB_ActiveTS as LastUpdate,w.WEB_Created as Created,v.VENDOR_Name as VendorName,v.VENDOR_Address as VendorAddress,v.VENDOR_Phone as VendorPhone,v.Vendor_Notes as VendorDescription,v.VENDOR_Active as VendorStatus');
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID');
		$this->db->where('w.CLIENT_ID',$cid);
		if($wid) {
			$this->db->where('w.WEB_ID',$wid);
		}
		$website = $this->db->get();
		
		return ($website) ? $website->result() : FALSE;
	}
	
	public function getContactTitle($id) {
		$sql = 'SELECT TITLE_Name as Name FROM xTitles WHERE TITLE_ID = "' . $id . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->row()->Name : FALSE;
	}
	
	public function getUsersByUserInfo($client_id) {
		$sql = 'SELECT ui.*,d.*,u.*,t.* 
				FROM Users_Info ui 
				INNER JOIN Directories d ON ui.DIRECTORY_ID = d.DIRECTORY_ID 
				INNER JOIN Users u ON ui.USER_ID = u.USER_ID INNER JOIN xTags t ON u.Team = t.TAG_ID WHERE ui.CLIENT_ID = "' . $client_id . '" ORDER BY d.DIRECTORY_LastName, d.DIRECTORY_FirstName ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllTags() {
		$sql = 'SELECT TAG_ID as ID,TAG_Name as Name,TAG_Color as Color,TAG_Notes as Notes,TAG_Active as Status,TAG_ClassName as ClassName FROM xTags ORDER BY TAG_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllContactsInAgency($id) {
		$all_contacts = array();
		$asql = 'SELECT 
				 AGENCY_ID as AID
				 FROM Agencies
				 WHERE AGENCY_ID = "' . $id . '"
				 ORDER BY AGENCY_Name ASC';
		$agencies = $this->db->query($asql)->result();
		
		foreach($agencies as $agency) :
			
			$gsql = 'SELECT GROUP_ID as GID FROM Groups WHERE AGENCY_ID = "' . $agency->AID . '" ORDER BY GROUP_Name ASC';
			$groups = $this->db->query($gsql)->result();
			
			foreach($groups as $group) :
				$csql = 'SELECT CLIENT_ID as CID,CLIENT_Tag as Tag,Client_Name as Dealership FROM Clients WHERE CLIENT_ID = "' . $group->GID . '" ORDER BY CLIENT_Name ASC';
				$clients = $this->db->query($csql)->result();
				
				foreach($clients as $client) :
					$contacts = $this->getContacts($client->CID);
					foreach($contacts as $contact) {
						array_push($all_contacts, $contact);
					}
				endforeach; //end clients foreach
				
			endforeach; //end group foreach
		
		endforeach;	//end agency foreach
		
		return $all_contacts;
	}

    public function getUsers($id = false,$client_id = false) {
    	$selects = "u.USER_ID as ID,u.USER_Name as Username,u.USER_Name as EmailAddress,ui.USER_Active as Status,ui.USER_Created as JoinDate,ui.USER_ActiveTS as LastUpdate,ui.USER_Modules as Modules,ui.USER_Avatar as Avatar,ui.Google_Avatar,ui.USER_GravatarEmail as Gravatar,d.DIRECTORY_ID as DirectoryID,d.DIRECTORY_Type as UserType,d.DIRECTORY_FirstName as FirstName,d.DIRECTORY_LastName as LastName,d.DIRECTORY_Address as Address,d.DIRECTORY_EMAIL as Emails,d.DIRECTORY_Phone as Phones,d.DIRECTORY_Notes as Notes,d.TITLE_ID as TitleID,a.ACCESS_NAME as AccessName,a.ACCESS_Level as AccessLevel,a.ACCESS_ID as AccessID,c.CLIENT_Name as Dealership,c.CLIENT_Address as CompanyAddress,t.TAG_ID as TagID,t.TAG_Name as TeamName,t.TAG_ClassName as ClassName,t.TAG_Color as Color";
    	$this->db->select($selects);
		$this->db->from('Users u');
		$this->db->join('Users_Info ui','ui.USER_ID = u.USER_ID','inner');
		$this->db->join('xSystemAccess a','ui.ACCESS_ID = a.ACCESS_ID','inner');
		$this->db->join('Directories d','ui.DIRECTORY_ID = d.DIRECTORY_ID','inner');
		$this->db->join('Clients c','c.CLIENT_ID = ui.CLIENT_ID','inner');
		$this->db->join('xTags t','t.TAG_ID = u.Team','inner');
		
		if($id) :
			$this->db->where('u.USER_ID',$id);
		else :
			if($client_id) :
				$this->db->where('ui.CLIENT_ID',$client_id);
			else :
				$this->db->order_by('d.DIRECTORY_LastName','ASC');
			endif;
		endif; 
		
        //query to show users info on listing and edit pages.
       /* $sql = "SELECT 
                u.USER_ID as ID, 
				u.USER_Name as Username,
                u.USER_Name as EmailAddress,
                ui.USER_Active as Status,
                ui.USER_Created as JoinDate,
                ui.USER_ActiveTS as LastUpdate,
                ui.USER_Modules as Modules,
				ui.USER_Avatar as Avatar,
				ui.Google_Avatar,
				ui.USER_GravatarEmail as Gravatar,
				d.DIRECTORY_ID as DirectoryID,
                d.DIRECTORY_Type as UserType,
                d.DIRECTORY_FirstName as FirstName,
                d.DIRECTORY_LastName as LastName,
                d.DIRECTORY_Address as Address,
                d.DIRECTORY_EMAIL as Emails,
                d.DIRECTORY_Phone as Phones,
                d.DIRECTORY_Notes as Notes,
                d.TITLE_ID as TitleID,
                a.ACCESS_NAME as AccessName,
                a.ACCESS_Level as AccessLevel,
                a.ACCESS_ID as AccessID,
				c.CLIENT_Name as Dealership,
				c.CLIENT_Address as CompanyAddress,
				t.TAG_ID as TagID,
				t.TAG_Name as TeamName,
				t.TAG_ClassName as ClassName,
				t.TAG_Color as Color
                FROM Users u
                INNER JOIN Users_Info ui ON ui.USER_ID = u.USER_ID
                INNER JOIN xSystemAccess a ON ui.ACCESS_ID = a.ACCESS_ID
                INNER JOIN Directories d ON ui.DIRECTORY_ID = d.DIRECTORY_ID
				INNER JOIN Clients c ON c.CLIENT_ID = ui.CLIENT_ID
				INNER JOIN xTags t ON t.TAG_ID = u.Team";
				if($id) { 
					$sql .= " WHERE u.USER_ID = '" . $id . "'"; 
				}else {
					if($client_id) {
						$sql .= " WHERE ui.CLIENT_ID = '" . $client_id . "'";
					}else {
        				$sql .= " ORDER BY d.DIRECTORY_LastName ASC";
					}
				} 
        $users = $this->db->query($sql);*/
        $users = $this->db->get();
        if ($id) {
			$users = $users->row();
            $users->Address = isset($users->Address) ? mod_parser($users->Address) : '';
            $users->Email   = isset($users->Emails)  ? mod_parser($users->Emails)  : '';
            $users->Phone   = isset($users->Phones)  ? mod_parser($users->Phones)  : '';
        }else {
			$users = $users->result();	
		}
        return ($users) ? $users : FALSE;
    }

    public function getPermissionsList($userLevel) {
    	$this->db->select('ACCESS_ID as ID,ACCESS_Name as Name,ACCESS_Level as Level,ACCESS_Perm as Modules');
    	$this->db->from('xSystemAccess');
    	$this->db->where('ACCESS_Level <=', $userLevel);
    	$this->db->order_by('ACCESS_LEVEL','DESC');
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
    }

    public function getAgencies($id = false) {
    	$this->db->select('AGENCY_ID as ID,AGENCY_Name as Name,AGENCY_Notes as Description, AGENCY_Active as Status');
		$this->db->from('Agencies');
		if($id) {$this->db->where('AGENCY_ID',$id);}	
		$query = $this->db->get();
		return($query) ? $query->result() : FALSE;	
    }

    public function getGroups($id) {
    	$this->db->select('g.GROUP_ID as GroupId,g.AGENCY_ID as AgencyId,g.GROUP_Name as Name,g.GROUP_Notes as Description,g.GROUP_Active as Status,g.GROUP_Created as CreateDate,a.AGENCY_Name as AgencyName,a.AGENCY_ID as AgencyId');
		$this->db->from('Groups g');
		$this->db->join('Agencies a','g.AGENCY_ID = a.AGENCY_ID');
		$this->db->where('g.AGENCY_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
    }
	
	/*
	 * Enabling and disabling a client
	 */ 
	 
	 public function changeClientStatus($cid,$which) {
		$data = array(
			'CLIENT_Active' => ($which) ? 1 : 0
		);
		$this->db->where('CLIENT_ID',$cid);
		return ($this->db->update('Clients',$data)) ? TRUE : FALSE;
	 }
    
	/*
	 * TO RETURN A SINGLE CLIENT
	 * @PARAM $id = Client ID passed from controller.
	 * Will return a single client if the id is found, else it will return false
	 */
    public function getClient($id) {
    	$this->db->select('CLIENT_ID as ID, CLIENT_Name as Name, CLIENT_Address as Address, CLIENT_Phone as Phone, CLIENT_Notes as Notes, GROUP_ID as GroupdID');
		$this->db->from('Clients');
		$this->db->where('CLIENT_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }
	
	public function getAllVendors() {
		$this->db->select('VENDOR_ID as ID,VENDOR_Name as Name,VENDOR_Address as Address,VENDOR_Phone as Phone,VENDOR_Notes as Description');
		$this->db->from('Vendors');
		$this->db->where('VENDOR_Active','1');
		$vendors = $this->db->get();
		return ($vendors) ? $vendors->result() : FALSE;
	}

    public function getTypeList() {
    	$this->db->select('TITLE_ID as Id, TITLE_Name as Name');
		$this->db->from('xTitles');
		$this->db->order_by('TITLE_Name','ASC');
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
    }

    public function addAgencies($data) {
        if ($this->db->insert('Agencies', $data))
            return TRUE;
        else
            return FALSE;
    }
	
	public function addGroup($data) {
		if($this->db->insert('Groups',$data))
			return TRUE;
		else
			return FALSE;	
	}
	
	public function editGroup($data) {
       $this->db->where('GROUP_ID', $data['GROUP_ID']);
       return $this->db->update('Groups', $data);
	}
	
	public function addClient($data) {
		if($this->db->insert('Clients',$data)) {
			$this->db->where($data);
			$this->db->order_by('CLIENT_ActiveTS','desc');
			$query = $this->db->get('Clients',1);
			return $query->row()->CLIENT_ID;
		}else {
			return FALSE;
		}
	}
	
	public function editClient($data, $id) {
		$this->db->where('CLIENT_ID',$id);
		return $this->db->update('Clients',$data);	
	}

    public function getAgencyByID($id) {
    	$this->db->select('AGENCY_ID as Id,AGENCY_Name as Name,AGENCY_Notes as Description,AGENCY_Active as Status,AGENCY_Created as Created');
		$this->db->from('Agencies');
		$this->db->where('AGENCY_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    /*     * *********************************************************************
     *
     * These are for the session after you select something from the dropdown
     *
     * ********************************************************************** */

    public function getGroupID($id) { //Get the selected group id and the associated agency id
    	$this->db->select('GROUP_ID as GroupID,AGENCY_ID as AgencyID');
		$this->db->from('Groups');
		$this->db->where('GROUP_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    public function getClientID($id) { //get the client id with the associated group id
    	$this->db->select('CLIENT_ID as ClientID,GROUP_ID as GroupID');
		$this->db->from('Clients');
		$this->db->where('CLIENT_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    public function getAgencyID($id) { //get the agency id
    	$this->db->select('AGENCY_ID as AgencyID');
		$this->db->from('Agencies');
		$this->db->where('AGENCY_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    /* End session queries */

    /*     * ****************************************************************************************************
     *
     * These are the queries for the Admin/Groups section based on the selected item in the dealer dropdown
     *
     * **************************************************************************************************** */

    public function getSelectedGroup($id) { //Get the selected group while on group level
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName
                FROM Groups g
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.GROUP_ID = "' . $id . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
    }
    
    public function getSelectedClient($id) {
        $sql = 'SELECT
                c.CLIENT_ID as ClientID,
                c.CLIENT_Name as Name,
                c.CLIENT_Address as Address,
                c.CLIENT_Phone as Phone,
                c.CLIENT_Notes as Description,
                c.CLIENT_Code as Code,
                c.CLIENT_Tag as Tag,
                c.CLIENT_Active as Status
                FROM Clients c
                WHERE c.CLIENT_ID = "' . $id . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
    }
	
	public function getSelectedClientsReviews($client_id,$service_id) {
		//Using active record to select content from database
		$sql = 'SELECT ID,URL FROM Reputations WHERE ServicesID = "' . $service_id . '" AND ClientID = "' . $client_id . '";';
		$result = $this->db->query($sql);
		$row = ($result) ? $result->row() : FALSE;
		return ($row) ? $row : FALSE;
	}	
	
	public function editReviews($data,$client_id,$services_id) {
		return ($this->db->update('Reputations', $data,array('ServicesID'=>$services_id,'ClientID'=>$client_id))) ? TRUE : FALSE; 
	}
	
	public function getClientByID($id) {
        $sql = 'SELECT
                g.GROUP_Name as GroupName,
                c.CLIENT_ID as ClientID,
                c.CLIENT_Name as Name,
                c.CLIENT_Code as ClientCode,
                c.CLIENT_Phone as PhoneNumber,
                c.CLIENT_Address as Address,
                c.CLIENT_Active as Status,
				t.TAG_ClassName as ClassName
                FROM Clients c
                INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
                WHERE c.CLIENT_ID = "' . $id . '" ORDER BY c.CLIENT_Name ASC;';		
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function doReviewsExist($client_id,$service_id) {
		$sql = 'SELECT ID FROM Reputations WHERE ClientID = "' . $client_id . '" AND ServicesID = "' . $service_id . '"';
		$query = $this->db->query($sql);
		return ($query) ? TRUE : FALSE;
	}

    public function getParentGroupOfClient($id) { //Get the selected clients parent group.
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName
                FROM Clients c
                INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE c.CLIENT_ID = "' . $id . '";';
		$query = $this->db->query($sql);
        return ($query) ? $query->row() : FALSE;
    }
    
    public function getAllClientsInAgency($agency_id) {
        //first we get the groups in the agency
        $groups = $this->getAllActiveGroupsInAgency($agency_id);
        
        $list = array();
        foreach($groups as $group) {
			
			$clients = $this->getAllClientsInGroup($group->GroupID);
			foreach($clients as $client) {
				array_push($list,$client);
			}
            //array_push($clients,$this->getAllClientsInGroup($group->GroupID));
        }
        return $list;
    }
    
    public function getAllActiveGroupsInAgency($id) { //get all groups in a agency
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName
                FROM Groups g
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.GROUP_Active = "1" AND g.AGENCY_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }


    public function getAllGroupsInAgency($id) { //get all groups in a agency
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName,
				t.TAG_ClassName as ClassName
                FROM Groups g
				INNER JOIN xTags t ON g.GROUP_Tags = t.TAG_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.AGENCY_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }
	
	public function getSelectedGroupResults($id) {
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
				g.GROUP_Notes as Notes,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName,
				t.TAG_ClassName as ClassName
                FROM Groups g
				INNER JOIN xTags t ON g.GROUP_Tags = t.TAG_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.GROUP_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllGroupsInAgencyResults($id) {
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Notes,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName,
				t.TAG_ClassName as ClassName
                FROM Groups g
				INNER JOIN xTags t ON g.GROUP_Tags = t.TAG_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.AGENCY_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
    
    public function getAllClientsInGroup($group_id) {
        $sql = 'SELECT
				c.GROUP_ID as GroupID,
                g.GROUP_Name as GroupName,
                c.CLIENT_ID as ClientID,
                c.CLIENT_Name as Name,
                c.CLIENT_Code as ClientCode,
                c.CLIENT_Phone as PhoneNumber,
                c.CLIENT_Address as Address,
                c.CLIENT_Active as Status,
				t.TAG_ClassName as ClassName
                FROM Clients c
                INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
                WHERE c.GROUP_ID = "' . $group_id . '" ORDER BY c.CLIENT_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }
	
	public function getClientWebsitesByCID($cid) {
		$sql = 'SELECT url as URL,special_label	as SpecialLabel FROM ClientWebsites WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getClientCMSByCID($cid) {
		$sql = 'SELECT label as Label, url as URL, special_label as SpecialLabel FROM ClientCMS WHERE client_id = "' . $cid . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getClientCRMByCID($cid) {
		$sql = 'SELECT label as Label, url as URL FROM ClientCRM WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getClientDocsByCID($cid) {
		$sql = 'SELECT url as URL FROM ClientDocs WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getClientCrazyEggByCID($cid) {
		$sql = 'SELECT url as URL, special_label as SpecialLabel, label as Label FROM ClientCrazyEgg WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}

    /* End Admin/Group Queries */

    public function updateAgencyInformation($id, $data) {
        $this->db->where('AGENCY_ID', $id);
        $this->db->update('Agencies', $data);
    }
	
	public function updateUser($data) {
		if($this->db->update('Users',$data['Users'],'USER_ID = "' . $data['Users_Info']['USER_ID'] . '"') AND $this->db->update('Directories',$data['Directories'],'DIRECTORY_ID = "' . $data['Directories']['DIRECTORY_ID'] . '"') AND $this->db->update('Users_Info',$data['Users_Info'],'USER_ID = "' . $data['Users_Info']['USER_ID'] . '"')) {
			return TRUE;	
		}else {
			return FALSE;	
		}
	}
	
	public function updateContact($data, $id) {
		if($this->db->update('Directories',$data,'DIRECTORY_ID ="' . $id . '"')) {
			return TRUE;	
		}else {
			return FALSE;	
		}
	}
	
	public function getClientIDSInGroup($id) {
		$sql = 'SELECT CLIENT_ID as ID FROM Clients WHERE GROUP_ID = "' . $id . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;	
	}
    
    public function getContacts($id) {
        $sql = 'SELECT 
				d.DIRECTORY_ID as ContactID,
				d.TITLE_ID as Title,
				d.JobTitle as JobTitle,
				d.DIRECTORY_Type as Type,
				d.DIRECTORY_FirstName as FirstName,
				d.DIRECTORY_LastName as LastName,
				d.DIRECTORY_Address as Address,
				d.DIRECTORY_Email as Email,
				d.DIRECTORY_Phone as Phone,
				c.CLIENT_Tag as TagID,
				c.CLIENT_Name as Dealership,
				t.TAG_Name as TagName,
				t.TAG_Color as TagColor,
				t.TAG_ClassName as Tag,
				d.DIRECTORY_Notes as Notes 
				FROM Directories d
				INNER JOIN Clients c ON c.CLIENT_ID = "' . $id . '"
				INNER JOIN xTags t on c.CLIENT_Tag = t.TAG_ID
				WHERE d.DIRECTORY_Type = "CID:' . $id . '" OR d.DIRECTORY_Type = "VID:' . $id . '" 
				ORDER BY d.DIRECTORY_LastName,d.DIRECTORY_FirstName,c.CLIENT_Tag ASC';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }
	
	public function getContact($id) {
        $sql = 'SELECT 
				d.DIRECTORY_ID as ContactID,
				d.TITLE_ID as Title,
				d.JobTitle as JobTitle,
				d.DIRECTORY_Type as Type,
				d.DIRECTORY_FirstName as FirstName,
				d.DIRECTORY_LastName as LastName,
				d.DIRECTORY_Address as Address,
				d.DIRECTORY_Email as Email,
				d.DIRECTORY_Phone as Phone,
				d.DIRECTORY_Notes as Notes,
				c.CLIENT_Tag as TagID,
				c.CLIENT_Name as Dealership,
				c.CLIENT_ID as DealershipID,
				t.TAG_Name as TagName,
				t.TAG_Color as TagColor,
				t.TAG_ClassName as Tag
				FROM Directories d
				INNER JOIN Clients c ON c.CLIENT_ID = d.CLIENT_Owner
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
				WHERE DIRECTORY_ID = "' . $id . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
	}
    
    public function addContact($data) {
        return $this->db->insert('Directories', $data); 
    }
	
	public function addReviews($data) {
		foreach($data as $review) {
			if($this->db->insert('Reputations',$review)) {
				return TRUE;	
			}else {
				return FALSE;	
			}
		}
	}
	
	public function addReview($data) {
		return ($this->db->insert('Reputations',$data)) ? TRUE : FALSE;	
	}
	
	/*Masterlist*/
	public function getMasterlist() {
		$agency = $this->session->userdata['valid_user']['DropdownDefault']->SelectedAgency;
		$sql = 'SELECT 
				w.special_label as Dealership,
				w.url as DealershipURL,
				w.doc as Doc,
				w.xls as Xls,
				w.egg as NewEgg,
				cms.service as CMSLabel,
				cms.url as CMSUrl,
				crm.service as CRMLabel,
				crm.url as CRMUrl,
				c.CLIENT_Code as Code,
				c.CLIENT_Active as Status,
				t.TAG_ClassName as Class,
				g.AGENCY_ID as AgencyID
				FROM ClientWebsites w
				INNER JOIN MasterlistBank cms ON w.cms = cms.id
				INNER JOIN MasterlistBank crm ON w.crm = crm.id
				INNER JOIN Clients c ON w.client_id = c.CLIENT_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
				INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
				WHERE g.AGENCY_ID = ' . $agency . '
				ORDER BY w.special_label ASC';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllTypes() {
		$sql = 'SELECT
				p.PASS_TYPE_ID as ID,
				p.PASS_TYPE_Name as Name
				FROM xPasswordTypes p
				ORDER BY p.PASS_TYPE_Name';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;		
	}
	
	public function getPasswords($cid = FALSE) {
		return $this->getPasswordsList($cid, FALSE);
	}
	
	public function getPasswordsByID($id = FALSE) {
		return $this->getPasswordsList(FALSE, $id);
	}
	
	private function getPasswordsList($cid, $pwdid) {
		$sql = 'SELECT
				c.CLIENT_ID as ClientID,
				p.PASS_ID as ID,
				tag.TAG_ClassName as Tag,
				t.PASS_TYPE_Name as Type,
				v.VENDOR_Name as Vendor,
				p.PASS_Rep as Rep,
				p.PASS_BestPhone as BestPhone,
				p.PASS_LoginAddress as LoginAddress,
				p.PASS_Username as Username,
				p.PASS_Password as Password,
				p.PASS_LeadRouting as LeadRouting,
				p.PASS_RoutingPhone as RoutingPhone,
				p.PASS_Terms as Terms,
				p.PASS_Budget as Budget,
				p.PASS_Notes as Notes
				FROM Passwords p
				INNER JOIN xPasswordTypes t on p.PASS_TypeID = t.PASS_TYPE_ID
				INNER JOIN Vendors v on p.PASS_VendorID = v.VENDOR_ID
				INNER JOIN Clients c on p.PASS_ClientID = c.CLIENT_ID
				INNER JOIN xTags tag ON c.CLIENT_Tag = tag.TAG_ID';
				$where = '';
				$where_count = 0;
				if ($cid) {
					$where = ($cid ? ('p.PASS_ClientID = ' . $cid) : '');
					$where_count++;
				}
				if ($pwdid)
			    	$where .= ($pwdid ? (($where_count > 0 ? ' AND' : '') . ' p.PASS_ID = ' . $pwdid)     : '');
				if ($where)
					$sql .= ' WHERE ' . $where;
				$sql .= ' ORDER BY p.PASS_Username ASC';
				
		$query = $this->db->query($sql);
		switch ($query->num_rows) {
			case 0:  return FALSE;
			case 1:  return $query->row();
			default: return $query->result();
		}
	}
	
	public function getVendors() {
		$this->db->select('VENDOR_ID as ID,VENDOR_Name as Name,VENDOR_Address as Address,VENDOR_Notes as Notes,VENDOR_Active as Status,VENDOR_ActiveTS as LastUpdate,VENDOR_Created as Created');
		$this->db->from('Vendors');
		$query = $this->db->get();
		
		if($query) {
			return $query->result();
		}else {
			return FALSE;
		}
		
	}

	public function getVendor($id) {
		$this->db->select('VENDOR_ID as ID,VENDOR_Name as Name,VENDOR_Address as Address,VENDOR_Notes as Notes,VENDOR_Active as Status,VENDOR_ActiveTS as LastUpdate,VENDOR_Created as Created');
		$this->db->from('Vendors');
		$this->db->where('VENDOR_ID',$id);
		
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
		
	}

	public function disableVendor($id,$which = 'disable') {
		$data = array(
			'VENDOR_Active' => (($which != 'enable') ? 0 : 1)
		);
		$this->db->where('VENDOR_ID',$id);
		return ($this->db->update('Vendors',$data) ? TRUE : FALSE);
	}

}