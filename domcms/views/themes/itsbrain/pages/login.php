<!-- Login form area -->
<div class="loginWrapper">
	<div class="loginLogo"><img src="<?= base_url(); ?>assets/themes/global/imgs/loginLogo.png" alt="" style="height:100%; " /></div>
    <div class="loginPanel">
        <div class="head"><h5 class="iUser">Login</h5></div>
        <?
        $form = array(
            'name' => 'login_form',
            'class' => 'mainForm',
            'id' => 'valid',
            'autocomplete' => 'off'
		);
		
		$email = array(
            'name' => 'email',
            'id' => 'req1',
            'class' => 'validate[required, custom[email]]',
            'value' => '',
            'autocomplete' => 'off'
		);
		
		$password = array(
            'name' => 'password',
            'id' => 'req2',
            'class' => 'validate[required,onlyLetterNumber]',
            'value' => '',
            'autocomplete' => 'off'
		);
		
		$submit = array(
			'value' => 'Log Me In',
			'class' => 'greyishBtn submitForm'
		);
		echo form_open('authenticate', $form); ?>
        <form action="<?= base_url(); ?>authenticate" id="valid" class="mainForm" method="post">
            <fieldset>
                <div class="loginRow noborder">
                    <label for="req1">Username:</label>
                    <div class="loginInput"><?= form_input($email); ?></div>
                    <div class="fix"></div>
                </div>
                
                <div class="loginRow">
                    <label for="req2">Password:</label>
                    <div class="loginInput"><?= form_password($password); ?></div>
                    <div class="fix"></div>
                </div>
                
                <div class="loginRow">
                    <div class="rememberMe"><input type="checkbox" id="check2" name="chbox" /><label for="check2">Remember me</label></div>
                    <?= form_submit($submit); ?>
                    <div class="fix"></div>
                </div>
            </fieldset>
        <?= form_close(); ?>
    </div>
</div>