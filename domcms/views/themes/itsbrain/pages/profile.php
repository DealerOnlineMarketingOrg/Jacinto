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
	            echo '<p style="margin-left:15px !important;">First Name</p>';
	            echo form_input(array('id' => 'firstname','name'=>'firstname','placeHolder'=>'Your First Name','value'=>$user->FirstName,'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:15px !important;">Last Name</p>';
                echo form_input(array('id' => 'lastname','name'=>'lastname','placeHolder'=>'Your Last Name','value'=>$user->LastName,'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:15px !important;">Username</p>';
				echo form_input(array('id' => 'username','name'=>'username','placeHolder'=>'Your Username','value'=>$user->Username,'class'=>'validate[required]','style'=>'margin-top:5px;'));
				if($admin['AccessLevel'] >= 600000) {
					echo '<p style="margin-left:15px !important;">Security Level</p>';
					$options = array(
						'1' => 'Super-Admin',
						'2' => 'Admin',
						'3' => 'Group Admin',
						'4' => 'Client Admin',
						'5' => 'Manager',
						'6' => 'User'
					);
					echo form_dropdown('permissionlevel', $options, $user->AccessID);        
				}
				echo '</form>'; ?>
        </div>
    </div>
</div>
<div id="editContactInfo">
    <div class="dialog-message" id="editUserContact" title="Edit User Contact Info">
        <div class="uiForm">
                <?= form_open(base_url().'profile/update/userContactInfo', array('id' => 'UpdateUserContactInfo','class'=>'valid'));
	            echo '<p style="margin-left:15px !important;">Home Email</p>';
	            echo form_input(array('id' => 'home_email','name'=>'home_email','placeHolder'=>'Your Home Email','value'=>$user->Emails['home'],'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:15px !important;">Work Email</p>';
                echo form_input(array('id' => 'work_email','name'=>'work_email','placeHolder'=>'Your Work Email','value'=>$user->Emails['work'],'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:15px !important;">Main Phone</p>';
				echo form_input(array('id' => 'main_phone','name'=>'main_phone','placeHolder'=>'Your Main Phone Number','value'=>$user->Phones['main'],'class'=>'validate[required]','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:15px !important;">Mobile Phone</p>';
				echo form_input(array('id' => 'mobile_phone','name'=>'mobile_phone','placeHolder'=>'Your Mobile Phone Number','value'=>$user->Phones['mobile'],'class'=>'','style'=>'margin-top:5px;'));
    	        echo '<p style="margin-left:15px !important;">Fax Number</p>';
				echo form_input(array('id' => 'fax_phone','name'=>'fax_phone','placeHolder'=>'Your Fax Phone Number','value'=>$user->Phones['fax'],'class'=>'','style'=>'margin-top:5px;'));
				echo '</form>'; ?>
        </div>
    </div>
</div>
<div id="editUserModules"></div>
<script type="text/javascript">

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