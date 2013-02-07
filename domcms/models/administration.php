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
		$results = query_results($this,$sql);
		
		return (($results) ? $results->Name : false);
	}
	
	public function getUsersByUserInfo($client_id) {
		$sql = 'SELECT ui.*,d.*,u.* 
				FROM Users_Info ui 
				INNER JOIN Directories d ON ui.DIRECTORY_ID = d.DIRECTORY_ID 
				INNER JOIN Users u ON ui.USER_ID = u.USER_ID WHERE ui.CLIENT_ID = "' . $client_id . '" ORDER BY d.DIRECTORY_LastName, d.DIRECTORY_FirstName ASC;';
		return query_results($this,$sql);
	}
	
	public function getAllTags() {
		$sql = 'SELECT TAG_ID as ID,TAG_Name as Name,TAG_Color as Color,TAG_Notes as Notes,TAG_Active as Status,TAG_ClassName as ClassName FROM xTags ORDER BY TAG_Name ASC;';
		return $this->db->query($sql)->result();	
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
                a.ACCESS_ID as AccessID
                FROM Users u
                INNER JOIN Users_Info ui ON ui.USER_ID = u.USER_ID
                INNER JOIN xSystemAccess a ON ui.ACCESS_ID = a.ACCESS_ID
                INNER JOIN Directories d ON ui.DIRECTORY_ID = d.DIRECTORY_ID " . (($id) ? "WHERE u.USER_ID = '" . $id . "' " : "") . "
                ORDER BY d.DIRECTORY_LastName ASC";
        $users = query_results($this, $sql);

        if ($id) {
            $users->Address= (isset($users->Address) ? mod_parser($users->Address) : '');
            $users->Email  = (isset($users->Emails)  ? mod_parser($users->Emails) : '');
            $users->Phone  = (isset($users->Phones)  ? mod_parser($users->Phones) : '');
        }

        return $users;
    }

    public function getPermissionsList($userLevel) {
        $sql = "SELECT ACCESS_ID as ID, ACCESS_Name as Name,ACCESS_Level as Level,ACCESS_Perm as Modules FROM xSystemAccess WHERE ACCESS_Level <= '" . $userLevel . "' ORDER BY ACCESS_LEVEL DESC;";
        return query_results($this, $sql);
    }

    public function getAgencies($id = false) {
        if($id) :
            $sql = "SELECT AGENCY_ID as ID, AGENCY_Name as Name, AGENCY_Notes as Description, AGENCY_Active as Status FROM Agencies WHERE AGENCY_ID = '" . $id . "';";
        else :
            $sql = "SELECT AGENCY_ID as ID, AGENCY_Name as Name,AGENCY_Notes as Description, AGENCY_Active as Status FROM Agencies ORDER BY AGENCY_Name; ";
        endif;
        return query_results($this, $sql);
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
                INNER JOIN Agencies a
                        ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.AGENCY_ID = '" . $id . "';";
        return query_results($this, $sql);
    }

    public function getClient($id) {
        $sql = "SELECT CLIENT_ID as ID, CLIENT_Name as Name, GROUP_ID as GroupdID FROM Clients WHERE CLIENT_ID = '" . $id . "';";
        return query_results($this, $sql);
    }

    public function getTypeList() {
        $sql = "SELECT TITLE_ID as Id, TITLE_Name as Name FROM xTitles ORDER BY TITLE_Name;";
        return query_results($this, $sql);
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
		if($this->db->insert('Clients',$data))
			return TRUE;
		else
			return FALSE;
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

        return query_results($this, $sql);
    }

    /*     * *********************************************************************
     *
     * These are for the session after you select something from the dropdown
     *
     * ********************************************************************** */

    public function getGroupID($id) { //Get the selected group id and the associated agency id
        $sql = 'SELECT GROUP_ID as GroupID,AGENCY_ID as AgencyID FROM Groups WHERE GROUP_ID = "' . $id . '";';
        return query_results($this, $sql);
    }

    public function getClientID($id) { //get the client id with the associated group id
        $sql = 'SELECT CLIENT_ID as ClientID, GROUP_ID as GroupID FROM Clients WHERE Client_ID = "' . $id . '";';
        return query_results($this, $sql);
    }

    public function getAgencyID($id) { //get the agency id
        $sql = 'SELECT AGENCY_ID as AgencyID FROM Agencies WHERE AGENCY_ID = "' . $id . '";';
        return query_results($this, $sql);
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
        return query_results($this, $sql);
    }
    
    public function getSelectedClient($id) {
        $sql = 'SELECT
                c.CLIENT_ID as ClientID,
                c.CLIENT_Name as Name,
                c.CLIENT_Address as Address,
                c.CLIENT_Phone as Phone,
                c.CLIENT_Notes as Description,
                c.CLIENT_Code as Code,
                c.CLIENT_Tag as Tags,
                c.CLIENT_Active as Status
                FROM Clients c
                WHERE c.CLIENT_ID = "' . $id . '";';
        return query_results($this,$sql);
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
		return $this->db->query($sql)->result();
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
        return query_results($this, $sql);
    }
    
    public function getAllClientsInAgency($agency_id) {
        //first we get the groups in the agency
        $groups = $this->getAllActiveGroupsInAgency($agency_id);
        
        $clients = array();
        foreach($groups as $group) {
            array_push($clients,$this->getAllClientsInGroup($group->GroupID));
        }
        return $clients;
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

        return $this->db->query($sql)->result();
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
        return $this->db->query($sql)->result();
    }
	
	public function getSelectedGroupResults($id) {
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
                WHERE g.GROUP_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		return $this->db->query($sql)->result();
	}
	
	public function getAllGroupsInAgencyResults($id) {
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
		return $this->db->query($sql)->result();
	}
    
    public function getAllClientsInGroup($group_id) {
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
                WHERE c.GROUP_ID = "' . $group_id . '" ORDER BY c.CLIENT_Name ASC;';
        return $this->db->query($sql)->result();
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
    
    public function getContacts($id) {
        $sql = 'SELECT 
				DIRECTORY_ID as ContactID,
				TITLE_ID as Title,
				DIRECTORY_Type as Type,
				DIRECTORY_FirstName as FirstName,
				DIRECTORY_LastName as LastName,
				DIRECTORY_Address as Address,
				DIRECTORY_Email as Email,
				DIRECTORY_Phone as Phone,
				DIRECTORY_Notes as Notes FROM Directories 
				WHERE DIRECTORY_Type = "CID:' . $id . '" OR DIRECTORY_Type = "VID:' . $id . '" 
				ORDER BY DIRECTORY_LastName,DIRECTORY_FirstName';
        return $this->db->query($sql)->result();
    }
	
	public function getContact($id) {
        $sql = 'SELECT 
				DIRECTORY_ID as ContactID,
				TITLE_ID as Title,
				DIRECTORY_Type as Type,
				DIRECTORY_FirstName as FirstName,
				DIRECTORY_LastName as LastName,
				DIRECTORY_Address as Address,
				DIRECTORY_Email as Email,
				DIRECTORY_Phone as Phone,
				DIRECTORY_Notes as Notes FROM Directories 
				WHERE DIRECTORY_ID = "' . $id . '"';
        return query_results($this,$sql);
	}
    
    public function addContact($data) {
        return $this->db->insert('Directories', $data); 
    }

}