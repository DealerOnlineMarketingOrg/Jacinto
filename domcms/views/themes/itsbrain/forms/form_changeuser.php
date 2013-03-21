<div class="uDialog" id="changeUserInfoForm">
    <div id="reset" class="dialog-message" title="Change User Info">
        <p><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/lock50.png" style="float:left;margin:5px;"  alt="Change User Info" />Fill the form out below to change your personal info.</p>
        <div class="uiForm">
            <?php 
                echo form_open(base_url().'/admin/change', array('id' => 'userInfo','class'=>'valid')); 
                echo form_input(array('id' => 'name','name'=>'name','placeHolder'=>'Your Name','class'=>'validate[required]','style'=>'margin-top:5px;')); 
                echo form_input(array('id' => 'username','name'=>'username','placeHolder'=>'Your Username (email)','class'=>'validate[required]','style'=>'margin-top:5px;'));
                echo form_input(array('id' => 'company','name'=>'company','placeHolder'=>'Which Company You Work At','class'=>'validate[required]','style'=>'margin-top:5px;'));
                echo form_input(array('id' => 'address','name'=>'address','placeHolder'=>'Your Address','class'=>'validate[required]','style'=>'margin-top:5px;'));
                if($admin['AccessLevel'] >= 600000) {
					$options = array(
                    	'1' => 'Super-Admin',
                        '2' => 'Admin',
                        '3' => 'Group Admin',
                        '4' => 'Client Admin',
                        '5' => 'Manager',
                        '6' => 'User'
					);
                    echo form_dropdown('security', $options, $user->AccessID);        
				}
                echo form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	jQuery('#changePassword').validationEngine({promptPosition : "right", scroll: true});
	
	jQuery('#changePassword').submit(function(e) {
		e.preventDefault();
	});

	jQuery("#reset").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Change: function() {
				ChangePassword();
			}
		}
	});

	function ChangePassword() {
		$.ajax({
			type:'POST',
			url:'<?= base_url(); ?>change_pass',
			data:{email:'<?= $email; ?>',oldPass:$('#oldPass').val(),newPass:$('#newPass').val()},
			success:function(data) {
				if(data == '1') {
					jAlert('Your password has been successfully changed. The system will automatically login you in in 5 seconds','Success');
					$('#changePassword').delay(5000,function() {
						LogMeIn();
					});
				}else {
					jAlert(data, 'Password Error');
				}
			}
		});
	}
</script>