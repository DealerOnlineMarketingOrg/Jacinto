<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendors extends DOM_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('administration'));
        $this->load->helper(array('msg','html'));
		$this->activeNav = 'admin';
    }
	
	public function index() {
		$table = '';
		$html = '';
		$vendors = $this->administration->getVendors();
		if($vendors) :
			if($this->CheckModule('Vendor_List')) {
				$table .= '<table style="width:100%" cellpadding="0" cellspacing="0" class="tableStatic">';
				$table .= '<thead><tr><th>Name</th><th>Phone</th><th>Status</th><th class="actions">Actions</th></tr></thead>';
				$table .= '<tbody>';
				foreach($vendors as $vendor) {
					$table .= '<tr>';
					$table .= '<td>' . $vendor->Name . '</td>';
					$table .= '<td>' . ($vendor->Status) ? 'Active' : 'Disabled' . '</td><td>';
					if(isset($vendor->Phone)) {
						foreach($vendor->Phone as $phone) {
							$table .= '<span class="tablePhoneHeading">Primary Phone</span><br />' . $phone['primary'];
							if(isset($phone['secondary'])) {
								$table .= '<br /><span class="tablePhoneHeading">Secondary Phone</span><br />' . $phone['secondary'] . '</span>';
							}
						}
					}
					$table .= '</td>';
					
					
					$table .= '<td>' .
					$table .= ($this->CheckModule('Vendor_Edit')) ? 
								'<a href="javascript:editVendor("' . $vendor->ID . '");">
									<img src="' . base_url() . THEMEIMGS . 'icons/dark/pencil.png" alt="Edit Vendor Details" />
								</a>' : '';
					
					
					if($this->CheckModule('Vendor_Del')) {
						
						if($vendor->Status) {
							$table .= '<a href="javascript:disableVendor("' . $vendor->ID . '"); "><img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable Vendor" /></a>';
						}else {
							$table .= '<a href="javascript:enableVendor("' . $vendor->ID . '"); "><img src="' . base_url() . THEMEIMGS . 'icons/color/plus.png" alt="Disable Vendor" /></a>';
						}
						
					}
					
					$table .= ($this->CheckModule('Vendor_Del')) ? '<a href="javascript:disableVendor("' . $vendor->ID . '");">
									<img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable Vendor" />
								</a>' : '';
					$table .= '</td>';
					$table .= '</tr>';
				}
				$table .= '</tbody>';
				$table .= '</table>';
				
				
				
				$table = '<script type="text/javascript">
				
				
					function disableVendor(id) {	
						jConfirm("Can you confirm this?", "Confirmation", function(r) {
							if(r) {
								//disable the vendor
								jQuery.ajax({
									type:"POST",
									url:"' . base_url() . 'vendors/remove/disable",
									data:{vid:id},
									success:function(data) {
										if(data) {
											window.location.href = "' . base_url() . 'vendors";
										}
									}
								});
							}
						});
					}

					function enableVendor(id) {	
						jConfirm("Are you sure you want to disable this vendor?", "Confirmation", function(r) {
							if(r) {
								//disable the vendor
								jQuery.ajax({
									type:"POST",
									url:"' . base_url() . 'vendors/remove/enable",
									data:{vid:id},
									success:function(data) {
										if(data) {
											window.location.href = "' . base_url() . 'vendors";
										}
									}
								});
							}
						});
					}
					
					function editVendor(id) {
						jQuery.ajax({
							type:"POST",
							url:"' . base_url() . 'vendors/edit",
							data:{vid:id},
							success:function(data) {
								if(data) {
									jQuery("editaddVendorForm").html(data);
								}
							}
						});
					}
				</script>';
			
			}else {
				$table .= '<div class="nNote nWarning"><p><strong>Warning:</strong> You do not have permission to view this data. </p></div>';
			}
		else :
			$html = '<div class="nNote nFailure"><p><strong>Error:</strong> There are no vendors in the system.</p></div>';
		endif;
		$html .= $table;
		
		if ($this->CheckModule('Vendor_Add')) {
			$html .= '<a href="javascript:void(0);" class="greenBtn floatRight button" style="margin-top:10px;" id="add_new_vendor">Add New Vendor</a>';
			$html .= '<script type="text/javascript">					
						jQuery("#add_new_vendor").click(function() {
							jQuery.ajax({
								type:"POST",
								url:"' . base_url() . 'admin/vendors/add",
								success:function(data) {
									if(data) {
										jQuery(".table").append(\'<div id="editaddVendorForm"></div>\');
										jQuery("#editaddVendorForm").html(data);
									}
								}
							});						
						});</script>';
		}
		
		$data = array(
			'page_id' => 'Vendors',
			'page_html' => $html
		);
		
		$this->LoadTemplate('pages/listings', $data);
	}
	
	public function add() {
		//were loading this page on a popup
		$this->load->view('themes/itsbrain/forms/form_addeditvendors');
	}
	
	public function edit() {
		//this is a popup that receives a post/get so we need to set the id
		$vid = $this->input->post('vid');
		$vendor = $this->administration->getVendor($vid);
		$data = array(
			'vendor' => $vendor
		);
		$this->load->view($this->theme_settings['ThemeDir'] . 'forms/form_addeditvendors',$data);
	}
	
	public function remove($which) {
		$vid = $this->input->post('vid');
		$disable = $this->administration->disableVendor($vid,$which);
		if($disable) :
			return TRUE;
		else :
			return FALSE;
		endif;
	}
	
	public function form() {
		$form = $this->input->post();
		
		$address = 'street:'   . stripslashes(trim($form['address'])) . ',city:' . stripslashes(trim($form['city'])) . ',state:' . $form['state'] . ',zipcode:' . stripslashes(trim($form['zipcode']));
		$phone   = 'primary:'  . stripslashes(trim($form['primary'])) . ((isset($form['secondary'])) ? ',secondary:' . $form['secondary'] : '');
		 
		$data = array(
			'VENDOR_Name' 		=> $form['name'],
			'VENDOR_Address' 	=> $address,
			'VENDOR_Phone' 		=> $phone,
			'VENDOR_Notes' 		=> $form['notes'],
			'VENDOR_Active' 	=> $form['status'],
			'VENDOR_ActiveTS' 	=> date('Y-m-d H:i:s'),
			'VENDOR_Created' 	=> date('Y-m-d H:i:s')
		);
		
		$vid = isset($form['id']) ? $form['id'] : FALSE;
		
		$process = $this->administration->vendorForm($vid);
		
		if($process) {
			echo '1';
		}else {
			echo '0';
		}
	}
	
}

