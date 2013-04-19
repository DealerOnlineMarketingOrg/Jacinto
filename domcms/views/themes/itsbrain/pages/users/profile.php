<style type="text/css">
		ul.modulesTable{min-width:709px !important;width:100%;display:block;border-bottom:1px solid #d5d5d5;height:30px;border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;}
		ul.modulesTable li {display:inline;float:left;width:23%;padding:5px;border-right:1px solid #d5d5d5;}
		ul.modulesTable li span.check{float:left;margin-right:5px;}
		ul.modulesTable li:last-child{border-right:none;}
		ul.modulesTable.first{border-top:1px solid #d5d5d5 !important;margin-top:0 !important;}
		ul.odd{background-color:#E2E4FF;}

</style>
<div class="content">
    <div class="title"><h5>User Information</h5></div>
    <? notifyError(); ?>
    	<?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
        <div id="profilePage">
        	<div class="widget first">
            	<div class="head info">
                	<h5 class="iUser"><?= $user->LastName . ', ' . $user->FirstName; ?></h5>
                    <?php if($user->Edit) { ?>
                    	<div class="editButton bar"><a href="javascript:editInfo('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
					<?php } ?>
                </div>
                <div class="body alignleft">
                	<div class="avatar" style="border:2px solid <?= $user->Color; ?>;">
                    	<img src="<?= $user->avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" />
                        <?php if($user->Edit) { ?>
                        	<div class="editButton inAvatar"><a href="javascript:editAvatar('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
						<?php } ?>
                    </div>
                    <div class="profileInfo alignleft">
                    	<ul>
                        	<li><span>Name:</span> <?= $user->FirstName . ' ' . $user->LastName; ?></li>
                            <li><span>Username:</span> <a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></li>
                            <li><span>Company:</span> <?= $user->viewCompany; ?></li>
                            <li><span>Address:</span> <?= $user->viewCompanyAddress; ?></li>
                            <li><span>Security:</span> <?= $user->AccessName; ?></li>
                            <li><span>Member Since:</span> <?= date('m/d/Y',strtotime($user->JoinDate)); ?></li>
                        </ul>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="head contactInfo">
                    <h5 class="iPhone">Contact Information</h5>
                    <?php if($user->Edit) { ?>
                        <div class="editButton"><a href="javascript:editContactInfo('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
                    <?php } ?>
                </div>
                <div class="body alignleft contactInfo">
                    <ul>
                        <li class="parentLabel"><span>Email:</span></li>
                        <li class="userContent"><?= $user->viewEmails; ?></li>
                        <li class="parentLabel"><span>Phone:</span></li>
                        <li class="userContent"><?= $user->viewPhones; ?></li>
                    </ul>
                    <div class="fix"></div>
                </div>
                <div class="head">
                	<h5 class="iRobot">Module Access</h5>
                    <?php if($admin['AccessLevel'] >= 600000) { ?>
                        <div class="editButton contactInfo"><a href="javascript:editUserModules('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
                    <?php } ?>
                </div>
                <div class="body alignleft modList">
                	<?php ModulesToEvenlyDesignedTable($user->viewUserModules); ?>
                    <div class="fix"></div>
                </div>
            </div>
        </div>
    <div class="fix"></div>
</div>
<div class="fix"></div>
<div class="uDialog">
    <div class="dialog-message" id="editAvatar" title="Edit Avatar">
        <div class="uiForm">
            <p style="margin-left:15px !important;">Upload a custom Avatar to our system.</p>
            <?= form_open_multipart(base_url().'profile/avatar/upload', array('id' => 'uploadAvatar','class'=>'valid')); ?>
            	<input name="avatar" placeholder="Custom Avatar" id="fileInput" class="fileInput" type="file" size="24" style="opacity:0;" />
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div id="editInfo">
    <div class="dialog-message" id="editUser" title="Edit User Info">
        <div class="uiForm">
                <?= form_open(base_url().'profile/update/userInfo', array('id' => 'UpdateUserInfo','class'=>'valid'));
	            echo '<p style="margin-left:5px !important;text-align:left;">First Name</p>';
	            echo form_input(array('id' => 'firstname','name'=>'firstname','placeHolder'=>'Your First Name','value'=>$user->FirstName,'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:5px !important;text-align:left;">Last Name</p>';
                echo form_input(array('id' => 'lastname','name'=>'lastname','placeHolder'=>'Your Last Name','value'=>$user->LastName,'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:5px !important;text-align:left;">Username</p>';
				echo form_input(array('id' => 'username','name'=>'username','placeHolder'=>'Your Username','value'=>$user->Username,'class'=>'validate[required]','style'=>'margin-top:5px;'));
				if($admin['AccessLevel'] >= 600000) {
					echo '<p style="margin-left:5px !important;text-align:left;">Security Level</p>';
					$options = array(
						'1' => 'Super-Admin',
						'2' => 'Admin',
						'3' => 'Group Admin',
						'4' => 'Client Admin',
						'5' => 'Manager',
						'6' => 'User'
					);
					echo form_dropdown('permissionlevel', $options, $user->AccessID,'style="width:100%;"');        
					if($admin['AccessLevel'] >= 600000) {
						echo '<br /><a href="javascript:resetPassword(\'' . $user->EmailAddress . '\');" class="button blueBtn" style="display:block;margin-top:15px;width:90%;float:left;">Reset Password</a>';
					}
				}
				echo form_close(); ?>
        </div>
    </div>
</div>
<div id="editContactInfo">
    <div class="dialog-message" id="editUserContact" title="Edit User Contact Info">
        <div class="uiForm">
                <?= form_open(base_url().'profile/update/userContactInfo', array('id' => 'UpdateUserContactInfo','class'=>'valid'));
	            
				foreach ($contact->Email as $type => $email) {
					echo '<p style="margin-left:15px !important;">'.$type.' Email</p>';
					echo form_input(array('id' => $type.'_email','name'=>$type.'_email','placeHolder'=>'Your '.$type.' Email','value'=>$email,'class'=>'validate[required]','style'=>'margin-top:5px;'));
				}
				
				// Locate primary.
				foreach ($contact->Phone as $type => $phone) {
					if ($phone == $contact->PrimaryPhoneType) {
						echo '<p style="margin-left:15px !important;">Main Phone</p>';
						echo form_input(array('id' => $type.'_phone','name'=>$type.'_phone','placeHolder'=>'Your '.$type.' Phone Number','value'=>$phone,'class'=>'validate[required]','style'=>'margin-top:5px;'));
						break;
					}
				}
				// Locate others.
				foreach ($contact->Phone as $type => $phone) {
					if ($phone != $contact->PrimaryPhoneType) {
						echo '<p style="margin-left:15px !important;">'.$type.' Phone</p>';
						echo form_input(array('id' => $type.'_phone','name'=>$type.'_phone','placeHolder'=>'Your '.$type.' Phone Number','value'=>$phone,'class'=>'validate[required]','style'=>'margin-top:5px;'));
					}
				}
				
				echo '</form>'; ?>
        </div>
    </div>
</div>
<div id="editUserModules"></div>
<script type="text/javascript">

	function resetPassword(email) {
		jConfirm('Are you sure you want to reset this users password?', 'Confirmation Password Reset', function(r) {
			if(r) {
				jQuery.ajax({
					type:'POST',
					url:'<?= base_url(); ?>user/profile/reset_password',
					data:{userEmail:email},
					success:function(data) {
						alert(data);
						if(data != '0') {
							jAlert('You have reset the users password to '+ data);
						}else {
							jAlert('There was a problem with the password reset. Please try again.');
						}
					}
				})
			}
		});
	}

	function editAvatar(id) {
		jQuery("#editAvatar").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Upload: function() {
					jQuery('#uploadAvatar').submit();
        		}
			}
		});
	}
	
	function editInfo(id) {
		jQuery("#editUser").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Save: function() {
					jQuery('#UpdateUserInfo').submit();
        		}
			}
		});
	}
	
	function editContactInfo(id) {
		jQuery("#editUserContact").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Save: function() {
					jQuery('#UpdateUserContactInfo').submit();
        		}
			}
		});
	}
	
	function editUserModules(id) {
		alert('edit users module access');
	}
	
	jQuery('div.avatar').hover(function() {
		jQuery(this).find('.editButton').slideDown('fast');
	},function() {
		jQuery(this).find('.editButton').slideUp('fast');
	});
</script>