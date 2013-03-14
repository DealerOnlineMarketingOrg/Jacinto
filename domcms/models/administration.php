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


    public function getUsers($id = false) {
        //query to show users info on listing and edit pages.
        $sql = "SELECT 
                u.USER_ID as ID, 
				u.USER_Name as Username,
                u.USER_Name as EmailAddress,
                ui.USER_Active as Status,
                ui.USER_Created as JoinDate,
                ui.USER_ActiveTS as LastUpdate,
                ui.USER_Modules as Modules,
				ui.USER_Avatar as Avatar,
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
				c.CLIENT_Address as CompanyAddress
                FROM Users u
                INNER JOIN Users_Info ui ON ui.USER_ID = u.USER_ID
                INNER JOIN xSystemAccess a ON ui.ACCESS_ID = a.ACCESS_ID
                INNER JOIN Directories d ON ui.DIRECTORY_ID = d.DIRECTORY_ID
				INNER JOIN Clients c ON c.CLIENT_ID = ui.CLIENT_ID";
				if($id) { 
					$sql .= " WHERE u.USER_ID = '" . $id . "'"; 
				}else {
        			$sql .= " ORDER BY d.DIRECTORY_LastName ASC";
				}
		
        $users = $this->db->query($sql);

        if ($id) {
			$users = $users->row();
            $users->Address = (isset($users->Address) ? mod_parser($users->Address) : '');
            $users->Email   = (isset($users->Emails)  ? mod_parser($users->Emails)  : '');
            $users->Phone   = (isset($users->Phones)  ? mod_parser($users->Phones)  : '');
        }else {
			$users = $users->result();	
		}
        return ($users) ? $users : FALSE;
    }

    public function getPermissionsList($userLevel) {
        $sql = "SELECT
				ACCESS_ID as ID,
				ACCESS_Name as Name,
				ACCESS_Level as Level,
				ACCESS_Perm as Modules 
				FROM xSystemAccess 
				WHERE ACCESS_Level <= '" . $userLevel . "' 
				ORDER BY ACCESS_LEVEL DESC;";
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }

    public function getAgencies($id = false) {
		$sql = "SELECT 
				AGENCY_ID as ID, 
				AGENCY_Name as Name, 
				AGENCY_Notes as Description, 
				AGENCY_Active as Status 
				FROM Agencies";
		$sql = ($id) ? " WHERE AGENCY_ID = '" . $id . "';" : " ORDER BY AGENCY_Name ASC";
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }

    public function getGroups($id) {
        $sql = "SELECT 
                g.GROUP_ID as GroupId, 
                g.AGENCY_ID as AgencyId, 
                g.GROUP_Name as Name, 
                g.GROUP_Notes as Description, 
                g.GROUP_Active as Status, 
                g.GROUP_Created as CreateDate ,
                a.AGENCY_Name as AgencyName,
                a.AGENCY_ID as AgencyId
                FROM Groups g 
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.AGENCY_ID = '" . $id . "';";
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }

    public function getClient($id) {
        $sql = "SELECT CLIENT_ID as ID, CLIENT_Name as Name, GROUP_ID as GroupdID FROM Clients WHERE CLIENT_ID = '" . $id . "';";
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
    }

    public function getTypeList() {
        $sql = "SELECT TITLE_ID as Id, TITLE_Name as Name FROM xTitles ORDER BY TITLE_Name;";
		$query = $this->db->query($sql);
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
        $sql = "SELECT 
                AGENCY_ID as Id, 
                AGENCY_Name as Name, 
                AGENCY_Notes as Description, 
                AGENCY_Active as Status, 
                AGENCY_Created as Created 
                FROM Agencies WHERE AGENCY_ID = '" . $id . "';";
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
    }

    /*     * *********************************************************************
     *
     * These are for the session after you select something from the dropdown
     *
     * ********************************************************************** */

    public function getGroupID($id) { //Get the selected group id and the associated agency id
        $sql = 'SELECT GROUP_ID as GroupID,AGENCY_ID as AgencyID FROM Groups WHERE GROUP_ID = "' . $id . '";';
		$query = $this->db->query($sql);
        return ($query) ? $query->row() : FALSE;
    }

    public function getClientID($id) { //get the client id with the associated group id
        $sql = 'SELECT CLIENT_ID as ClientID, GROUP_ID as GroupID FROM Clients WHERE Client_ID = "' . $id . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
    }

    public function getAgencyID($id) { //get the agency id
        $sql = 'SELECT AGENCY_ID as AgencyID FROM Agencies WHERE AGENCY_ID = "' . $id . '";';
		$query = $this->db->query($sql);
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
				t.TAG_ClassName as Class
				FROM ClientWebsites w
				INNER JOIN MasterlistBank cms ON w.cms = cms.id
				INNER JOIN MasterlistBank crm ON w.crm = crm.id
				INNER JOIN Clients c ON w.client_id = c.CLIENT_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
				ORDER BY w.special_label ASC';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}

}