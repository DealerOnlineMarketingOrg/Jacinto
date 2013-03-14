<div class="content">
    <div class="title"><h5>User Information</h5></div>
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
                	<div class="avatar <?= $user->ClassName; ?>">
                    	<img src="<?= $user->avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" />
                        <?php if($user->Edit) { ?>
                        	<div class="editButton inAvatar"><a href="javascript:editAvatar('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
						<?php } ?>
                    </div>
                    <div class="profileInfo alignleft">
                    	<ul>
                        	<li><span>Name:</span> <?= $user->FirstName . ' ' . $user->LastName; ?></li>
                            <li><span>Username:</span> <a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></li>
                            <li><span>Company:</span> <?= $user->Company; ?></li>
                            <li><span>Address:</span> <?= $user->CompanyAddress; ?></li>
                            <li><span>Security:</span> <?= $user->AccessName; ?></li>
                        </ul>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="head contactInfo">
                    <h5 class="iPhone">Contact Information</h5>
                    <?php if($user->Edit) { ?>
                        <div class="editButton contactInfo"><a href="javascript:editContactInfo('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
                    <?php } ?>
                </div>
                <div class="body alignleft">
                    <ul>
                        <li><strong>Email:</strong> <?= $user->Emails; ?></li>
                        <li><strong>Phone:</strong> <?= $user->Phone; ?></li>
                    </ul>
                    <div class="fix"></div>
                </div>
                <div class="head">
                	<h5 class="iRobot">Module Access</h5>
                    <?php if($admin['AccessLevel'] >= 600000) { ?>
                        <div class="editButton contactInfo"><a href="javascript:editContactInfo('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
                    <?php } ?>
                </div>
                <div class="body alignleft">
                	<ul class="modList">
                    	<? foreach($user->UserModules as $module) {?>
							<li><?= $module->MODULE_Title; ?></li>
						<? } ?>
                    </ul>
                    <div class="fix"></div>
                </div>
            </div>
        </div>
    <div class="fix"></div>
</div>
<div class="fix"></div>
<div class="uDialog">
    <div class="dialog-message" title="Edit Avatar">
        <div class="uiForm">
            <p style="margin-left:15px !important;">Upload a custom Avatar to our system.</p>
            <?= form_open_multipart(base_url().'profile/avatar/upload', array('id' => 'uploadAvatar','class'=>'valid')); ?>
            	<input name="avatar" placeholder="Custom Avatar" id="fileInput" class="fileInput" type="file" size="24" style="opacity:0;" />
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	function editAvatar(id) {
		jQuery(".dialog-message").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Upload: function() {
					jQuery('#uploadAvatar').submit();
        		}
			}
		});
	}
	jQuery('div.avatar').hover(function() {
		jQuery(this).find('.editButton').slideDown('fast');
	},function() {
		jQuery(this).find('.editButton').slideUp('fast');
	});
</script>