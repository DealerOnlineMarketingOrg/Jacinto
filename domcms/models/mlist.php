<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mlist extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	//RETURNS Object of clients.
	private function getClients() {
		//1 = agency, 2=group, 3=client
		$level = $this->getLevelType();
		//check out if we have a selected client or not.
		//if we do, we only have to work with one client, which is a lot simpler.
		$isClientSelected = ($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : FALSE;
		//get the group_id's, if only one group is being worked with this will be a single number, else it will be an object.
		$groups = $this->getGroups();	
		//array to collect all clients in, this will end up being the array returned by the function.
		$client_collection = array();
		//select statement to grab data from db
		$select_statement = 'c.CLIENT_ID as ClientID,c.CLIENT_Name as Dealership,c.CLIENT_Code as Code,t.TAG_ClassName as Class';
		//check to see if the $groups is an object, if it is we know we had to go to the db to get the group ids
		
		if(is_array($groups) AND $level <= 1) {
			foreach($groups as $group) {
				$client = $this->db->select($select_statement)
						  ->order_by('c.CLIENT_Name','asc')
						  ->join('xTags t','c.CLIENT_Tag = t.TAG_ID')
						  ->get_where('Clients c',array('c.GROUP_ID'=>$group->GroupID,'c.CLIENT_Active'=>1));
						  
				//now lets toss the return into our collection array to return to the system
				if($client) {
					//shortcut to the data of the query
					$clients = $client->result();
					//loop through the clients and grab data from multi level array and convert to single level array.
					/*
						for example instead of the array being:
						client = array(array(clients),array(clients),array(clients),array(clients));
						it will be:
						client = array(clients);
					*/
					foreach($clients as $single) {
						array_push($client_collection,$single);
					}
				}
			}
		}elseif(is_int($groups) AND $level === 2) {
			//we are only dealing with one group so we have one less loop to worry about here.
			$client = $this->db->select($select_statement)
					  ->order_by('c.CLIENT_Name','asc')
					  ->join('xTags t','c.CLIENT_Tag = t.TAG_ID')
					  ->get_where('Clients c',array('c.GROUP_ID'=>$groups));
					  
			//now lets toss the return into our collection array to return to the system
			if($client) {
				//shortcut to the data of the query
				$clients = $client->result();
				//loop through the clients and grab data from multi level array and convert to single level array.
				/*
					for example instead of the array being:
					client = array(array(clients),array(clients),array(clients),array(clients));
					it will be:
					client = array(clients);
				*/
				foreach($clients as $single) {
					array_push($client_collection,$single);
				}
				}
		}else {
			//This is the client level...we know the client, so we dont have to loop and do crazy stuff to get our info
			$client = $this->db->select($select_statement)
					  ->join('xTags t','c.CLIENT_Tag = t.TAG_ID')
					  ->get_where('Clients c',array('c.CLIENT_ID'=>$this->user['DropdownDefault']->SelectedClient));	
					  
			//now lets toss the return into our collection array to return to the system
			if($client) {
				//shortcut to the data of the query
				$clients = $client->result();
				//loop through the clients and grab data from multi level array and convert to single level array.
				/*
					for example instead of the array being:
					client = array(array(clients),array(clients),array(clients),array(clients));
					it will be:
					client = array(clients);
				*/
				foreach($clients as $single) {
					array_push($client_collection,$single);
				}
			}
		}
		
		//return the collection array that should, at this point have plenty of clients for you to work with.
		//if none of the above is ran, it will just return FALSE;
		return (!empty($client_collection)) ? $client_collection : FALSE;
	}
	
	//RETURNS data of specific known client.
	private function getKnownClient($id) {
		//array to collect all clients in, this will end up being the array returned by the function.
		$client_collection = array();
		
		//select statement to grab data from db
		$select_statement = 'c.CLIENT_ID as ClientID,c.CLIENT_Name as Dealership,c.CLIENT_Code as Code,t.TAG_ClassName as Class';
		
		//This is the client level...we know the client, so we dont have to loop and do crazy stuff to get our info
		$client = $this->db->select($select_statement)
				  ->join('xTags t','c.CLIENT_Tag = t.TAG_ID')
				  ->get_where('Clients c',array('c.CLIENT_ID'=>$id));	
		
		//now lets toss the return into our collection array to return to the system
		if($client) {
			//shortcut to the data of the query
			$clients = $client->result();
			
			//loop through the clients and grab data from multi level array and convert to single level array.
			foreach($clients as $single) {
				array_push($client_collection,$single);
			}
		}

		//return the collection array that should, at this point have plenty of clients for you to work with.
		//if none of the above is ran, it will just return FALSE;
		return (!empty($client_collection)) ? $client_collection : FALSE;
	}

	
	/*
		RETURN will either return a integer or object from the database.
		when dealing with this return, you need to check the type before sending the data elsewhere.
	*/
	private function getGroups() {
		//are we dealing with one group or all groups?
		$level = $this->getLevelType();
		
		$isGroupSelected = ($level >= 2) ? $this->user['DropdownDefault']->SelectedGroup : FALSE;
		//grab the selected agency because there is always a selected agency.
		$agency_id = $this->getAgency();
		//if the group is already selected, there is no need to query the database to get the selected group id when we already know it.
		if(!$isGroupSelected) {
			//query the db based on our level of selection.
			$query = $this->db->select('GROUP_ID as GroupID')
					 ->order_by('GROUP_Name','asc')
					 ->get_where('Groups',array('AGENCY_ID'=>$agency_id));
					 
			return ($query) ? $query->result() : FALSE;
		}else {
			//this will always default to the selected group, 
			//if by chance the group isnt selected, 
			//the function will already return from the query above and never fire the below return.
			return (int)$isGroupSelected;
		}
		
	}
	
	private function getLevelType() {
		//grab the level type from the session
		$session_level = $this->user['DropdownDefault']->LevelType;	
		//get what level where on from the dealer dropdown
		switch($session_level) {
			case 'a': return 1; break;
			case 'g': return 2; break;
			case 'c': return 3; break;
			case '1': return 1; break;
			case '2': return 2; break;
			case '3': return 3; break;
		}
	}
	
	private function getAgency() {
		//return the selected agencies id;
		//if the selected agency isnt set (which is impossible) we set it to 1, which will always be DOM
		return ($this->user['DropdownDefault']->SelectedAgency) ? $this->user['DropdownDefault']->SelectedAgency : 1;
	}
	
	//this returns the clients website url along with the crazy egg status and link to crazy egg url
	private function getWebsites($cid) {
		$query = $this->db->select('w.WEB_ID as ID,w.WEB_Url as href,wo.CrazyEggStatusID as CrazyEggLabelID')
				 ->join('WebsiteOptions wo','wo.WEB_ID = w.WEB_ID')
				 ->get_where('Websites w',array('w.WEB_Type'=>'CID:'.$cid));
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getCrazyEggOptions() {
		$query = $this->db->select('Name,ID')->from('xCrazyEgg')->get();
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getVendorOptions() {
		$query = $this->db->select('VENDOR_ID as ID,VENDOR_Name as Name')->get_where('Vendors',array('VENDOR_Active'=>1));
		return ($query) ? $query->result() : FALSE;
	}
	
	private function getCrazyEgg($wid) {
		$query = $this->db->select('ce.Name as title')
				 ->join('xCrazyEgg ce','ce.ID = wo.CrazyEggStatusID')
				 ->get_where('WebsiteOptions wo',array('wo.WEB_ID'=>$wid));
				 
		return ($query) ? $query->result() : FALSE;
	}
	
	//get all doc files for clients from master list table
	private function getDocLinks($cid) {
		$query = $this->db->select('DOC_Link as href')->
				 get_where('MasterListAssets',array('CLIENT_ID'=>$cid));
				 
		return ($query) ? $query->result() : FALSE;	
	}
	
	private function getExcelLinks($cid) {
		$query = $this->db->select('XLS_Link as href')->
				 get_where('MasterListAssets',array('CLIENT_ID'=>$cid));	
		return ($query) ? $query->result() : FALSE;
	}
	
	private function getCRMNameAndLink($cid) {
		$this->db->select('m.ASSETS_ID as AssetsID,m.CRM_Vendor_Link as href,v.VENDOR_Name as title');
		 $this->db->from('MasterListAssets m');
		 $this->db->join('Vendors v','m.CRM_Vendor_ID = v.VENDOR_ID');
		 $this->db->where('m.CLIENT_ID',$cid);
		 
		 $query = $this->db->get();
				 
		return ($query) ? $query->result() : FALSE;
	}
	
	private function getCMSNameAndLink($cid) {
		$query = $this->db->select('m.MASTER_ID as MID,w.WEB_Url as href, v.VENDOR_Name as title')->
				 join('Vendors v','m.CMS_Vendor_ID = v.VENDOR_ID')->
				 join('Websites w','w.WEB_Type = CONCAT("VID:",v.VENDOR_ID)')->
				 get_where('MasterList m',array('m.CLIENT_ID'=>$cid));

		
		return ($query) ? $query->result() : FALSE;
	}
	
	//BUILD MASTERLIST 
	/*
		@optional parameter passed if we already have the client data and just want the rest.
	*/
	public function buildMasterList($clients = false) {
		//if we dont know the client, we need to gather that list
		if(!$clients) {
			//clients based on the level of dealer dropdown
			$clients = $this->getClients();
		}
		
		//collection array for all data
		$masterlist = array();
		//loop through clients
		if($clients) {
			foreach($clients as $client) :
				$client->Docs = 		$this->getDocLinks($client->ClientID);
				$client->Spreadsheets = $this->getExcelLinks($client->ClientID);
				$client->CRM = 			$this->getCRMNameAndLink($client->ClientID);
				$client->CMS = 			$this->getCMSNameAndLink($client->ClientID);
				$client->Websites = 	$this->getWebsites($client->ClientID);
				$client->CrazyEgg = array();
				
				if(!empty($client->Websites)) {
					foreach($client->Websites as $website) {
						$ce = $this->getCrazyEgg($website->ID);	
						foreach($ce as $cet) {
							array_push($client->CrazyEgg,$cet);	
						}
					}
				}
				
				//push data to collection
				array_push($masterlist,$client);
			endforeach;
		}
		
		if(!empty($masterlist)) {
			return $masterlist;	
		}else {
			//returns false by default
			return FALSE;
		}
	}
	
	//we call this when a popup is loaded because we already know the client id.
	public function getFormData($id) {
		//client based on the id passed 
		$client = $this->getKnownClient($id);
		
		//collection array to hold all data after were done formating it.
		$clients_collection = $this->buildMasterList($client);	
		return $clients_collection[0];
	}
	
	public function updateCms($id,$value) {
		$data = array(
			'CMS_Vendor_ID'=>$value
		);
		$this->db->where('MASTER_ID',$id);
		return ($this->db->update('MasterList',$data)) ? TRUE : FALSE;
	}
	
	public function updateCrazyEgg($id,$value) {
		$data = array(
			'CrazyEggStatusID'=>$value
		);	
		$this->db->where('WEB_ID',$id);
		return ($this->db->update('WebsiteOptions',$data)) ? TRUE : FALSE;
	}
	
	public function updateDocExcelCRM($id,$data) {
		$this->db->where('ASSETS_ID',$id);
		return ($this->db->update('MasterListAssets',$data)) ? TRUE : FALSE;
	}
}