<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Masterlist extends DOM_Controller {


	public $activeLevel;
	public $accessLevel;
    public function __construct() {
        parent::__construct();
		$this->activeNav = 'admin';
		$this->accessLevel = $this->user['AccessLevel'];
		switch($this->user['DropdownDefault']->LevelType) :
			case 'a': $this->activeLevel = 1; break;
			case 'g': $this->activeLevel = 2; break;
			case 'c': $this->activeLevel = 3; break;
			default : $this->activeLevel = 1; break;
		endswitch;
		$this->load->model('administration','master');
    }

    public function index() {
		$html = '';
		$scripts = '';
		if($this->activeLevel != 1) {
			$html .= '<div class="nNote nFailure"><p>The current page is not available on ' . (($this->activeLevel == 2) ? 'Group' : 'Client') . ' Level. Please use the Dealer Dropdown to change to Agency Level to view the Master List.</p></div>';
		}else {
			if($this->accessLevel >= 500000) {
				$masterlist = $this->master->getMasterlist();
				$table  = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
				$table .= '<thead><tr><th>Team</th><th>Code</th><th>Dealership</th><th>Website</th><th>CMS</th><th>CRM</th><th>DOC</th><th>XLS</th><th>Crazy Egg</th><th>Action</th></tr></thead>';
				$table .= '<tbody>';
				foreach($masterlist as $client) :
					$table .= '<tr class="tagElement ' . $client->Class . '">';
					$table .= '<td class="' . $client->Class . '"><div class="' . $client->Class . '">&nbsp;</div></td>';
					$table .= '<td>' . $client->Code . '</td>';
					$table .= '<td><a href="' . $client->Xls . '" target="_blank">' . $client->Dealership . '</a></td>';
					$table .= '<td><a href="' . $client->DealershipURL . '" target="_blank">' . $client->DealershipURL . '</a></td>';
					$table .= '<td style="text-align:center"><a href="' . $client->CMSUrl . '" target="_blank">' . $client->CMSLabel . '</a></td>';
					$table .= '<td style="text-align:center"><a href="' . $client->CRMUrl . '" target="_blank">' . $client->CRMLabel . '</a></td>';
					$table .= '<td style="width:23px;"><a href="' . $client->Doc . '" target="_blank"><img src="' . base_url() . 'assets/themes/itsbrain/imgs/icons/middlenav/docs.png" alt="Google Doc" /></a></td>';
					$table .= '<td style="width:23px;"><a href="' . $client->Xls . '" target="_blank"><img src="' . base_url() . 'assets/themes/itsbrain/imgs/icons/middlenav/excelDoc.png" alt="Spreadsheet" /></a></td>';
					$table .= '<td style="text-align:center"><a href="http://www.crazyegg.com/login" target="_blank">' . $client->NewEgg . '</a></td>';
					$table .= '<td style="text-align:center"><div class="editButton bar"><a href="javascript:editInfo(' . $client->AgencyID . ');"><span>Edit</span></a></div></td>';
					$table .= '</tr>
									';
				endforeach;
				$table .= '</tbody>';
				$table .= '</table>';
				$html .= $table;
				//print_object($masterlist);
				
				// Popup for editing selected masterlist item.
				$edit = '
					<div id="editMasterListInfo">
						<div class="dialog-message" id="editMasterListInfo" title="Edit Master List Info">
							<div class="uiForm">';
								$edit .= form_open(base_url()."", array("id"=>"UpdateMasterListInfo", "class"=>"valid"));
								$edit .= '
									<p style="margin-left:15px !important;">Team</p>
									<input id="team" name="team" placeHolder="Team" value="' . $client->Class . '" class="validate[required]" style="margin-top:5px;">
									<p style="margin-left:15px !important;">Code</p>
									<input id="code" name="code" placeHolder="Code" value="' . $client->Code . '" class="validate[required]" style="margin-top:5px;">
									<p style="margin-left:15px !important;">Dealership</p>
									<input id="dealership" name="dealership" placeHolder="Dealership" value=' . $client->Dealership . 'vclass="validate[required]" style="margin-top:5px;">
									<p style="margin-left:15px !important;">Website</p>
									<input id="website" name="website" placeHolder="Website" value="' . $client->DealershipURL . '" class="" style="margin-top:5px;">
									<p style="margin-left:15px !important;">CMS</p>
									<input id="cms" name="cms" placeHolder="CMS" value="' . $client->CMSLabel . '" class="" style="margin-top:5px;">
									<p style="margin-left:15px !important;">CRM</p>
									<input id="crm" name="crm" placeHolder="CRM" value="' . $client->CRMLabel . '" class="" style="margin-top:5px;">
									<p style="margin-left:15px !important;">DOC</p>
									<input id="doc" name="doc" placeHolder="DOC" value="' . $client->Doc . '" class="" style="margin-top:5px;">
									<p style="margin-left:15px !important;">XLS</p>
									<input id="xls" name="xls" placeHolder="XLS" value="' . $client->Xls . '" class="" style="margin-top:5px;">
									<p style="margin-left:15px !important;">Crazy Egg</p>
									<input id="crazy_egg" name="crazy_egg" placeHolder="Crazy Egg" value="' . $client->NewEgg . '" class="" style="margin-top:5px;">
								</form>
							</div>
						</div>
					</div>';
				
				$html .= $edit;
				
				// Scripts.
				$scripts = '
					function editInfo(id) {
						jQuery("#editMasterListInfo").dialog({
							autoOpen: true,
							modal: true,
							buttons: {
								Save: function() {
									jQuery("#UpdateMasterListInfo").submit();
								}
							}
						});
					}';
				
			}else {
				$html .= '<div class="nNote nFailure"><p>You do not have permission to view this page. Please contact support and request this module be added.</p></div>';	
			}
		}
		
		$data = array(
			'page_id' => '',
			'page_html' => $html,
			'page_scripts' => $scripts
		);
		$this->LoadTemplate('pages/listings',$data);
    }

}
