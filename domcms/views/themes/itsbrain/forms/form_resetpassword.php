<div class="uDialog">
    <div class="dialog-message" title="Reset Password">
        <p><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/lock50.png" style="float:left;margin:5px;" alt="Reset Password" />To reset your password, please give us the email address associated with your account.</p>
        <div class="uiForm">
            <?= form_open(base_url().'reset_password', array('id' => 'resetPassword','class'=>'valid')); ?>
                <input id="emailAddress" type="text" class="validate[required,custom[email]]" name="email" placeholder="Email Address" />
            <? form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery('#resetPassword').submit(function(e) {
		e.preventDefault();
	});

	jQuery(".dialog-message").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Reset: function() {
				jQuery('#loadedContent').empty();
				resetPass();
			}
		}
	});

	function resetPass() {
		jQuery.ajax({
			type:'POST',
			url:'<?= base_url(); ?>reset_password',
			data:{email:jQuery('#emailAddress').val()},
			success:function(data) {
				if(data == 1) {
					jAlert('The password for this account has been reset. A new password has been emailed to the email address provided.','Success');
				}else {
					jAlert('We could not find the email address provided in our system. Make sure the email address is the same email address you use to login.','Error');	
				}
			}
		});
	}
</script>
