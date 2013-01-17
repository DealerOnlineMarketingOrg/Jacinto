<!-- Login form area -->
<div class="loginWrapper">
	<div class="loginLogo"><img src="<?= base_url(); ?>assets/themes/global/imgs/loginLogo.png" alt="" style="height:100%; " /></div>
    <div class="loginPanel">
        <div class="head"><h5 class="iUser">Forgot Password</h5></div>
        <form action="<?= base_url(); ?>authenticate" id="valid" class="mainForm">
            <fieldset>
                <div class="loginRow noborder">
                    <label for="req1">Enter Password:</label>
                    <div class="loginInput"><input type="text" name="email" class="validate[required]" id="req1" /></div>
                    <div class="fix"></div>
                </div>
                <div class="loginRow">Configirm password
                    <label for="req2">Password:</label>
                    <div class="loginInput"><input type="password" name="password" class="validate[required]" id="req2" /></div>
                    <div class="fix"></div>
                </div>
                <div class="loginRow">
                    <div class="rememberMe"><input type="checkbox" id="check2" name="chbox" /><label for="check2">Remember me</label></div>
                    <input type="submit" value="Log me in" class="greyishBtn submitForm" />
                    <div class="fix"></div>
                </div>
            </fieldset>
        </form>
    </div>
</div>