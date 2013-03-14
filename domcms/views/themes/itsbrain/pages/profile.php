<div class="content">
    <div class="title"><h5>User Information</h5></div>
    	<?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
        <div id="profilePage">
        	<div class="widget first">
            	<div class="head"><h5 class="iUser"><?= $user->FirstName . ' ' . $user->LastName; ?></h5></div>
                <div class="body alignleft">
                	<div class="avatar">
                    	<img src="<?= $user->avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" />
                        <?php if($user->EditAvatar) { ?><span><a href="javascript:editAvatar('<?= $user->UserID; ?>');">Edit Avatar</a></span> <?php } ?>
                    </div>
                    <div class="info">
                    	<ul>
                        	<li><strong>Name:</strong> <?= $user->FirstName . ' ' . $user->LastName; ?></li>
                            <li><strong>Company:</strong> <?= $user->Company; ?></li>
                            <li><strong>Address:</strong> <?= $user->CompanyAddress; ?></li>
                            <li><strong>User Level:</strong> <?= $user->AccessName; ?></li>
                        </ul>
                    </div>
                    <div class="fix"></div>
                </div>
                    <div class="head"><h5 class="iPhone">Personal Contact Information</h5></div>
                    <div class="body alignleft">
                        <ul>
                            <li><strong>Email:</strong> <?= $user->Emails; ?></li>
                            <li><strong>Phone:</strong> <?= $user->Phone; ?></li>
                        </ul>
                        <div class="fix"></div>
                    </div>
                <div class="head"><h5 class="iRobot">Module Access</h5></div>
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
            <? form_close(); ?>
        </div>
    </div>
</div>
<img src="https://www.google.com/s2/photos/profile/0/112721005725999868667" />
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
</script>