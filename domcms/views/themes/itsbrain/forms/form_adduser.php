<div class="content" id="container">
    <script type="text/javascript">
        $(document).ready(function() {
            $("#usualValidate").validationEngine('validate'); 
        });
    </script>
    <div class="title"><h5>Admin</h5></div>
    <?php notifyError(); ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php echo  form_open('/admin/users/form_processor/users/add',array('id'=>'valid','class'=>'mainForm','name'=>'AddUser')); ?>

        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Add User</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Title</label>
                    <div class="formRight">
                    <select name="Title" id="Title" class="validate[required]">
                        <option value="1">Account Executive</option>
                        <option value="2">Account Manager</option>
                        <option value="3">Vendor Manager</option>
                        <option value="4">Owner</option>
                        <option value="5">General Manager</option>
                        <option value="6">Sales Manager</option>
                        <option value="7">Service Manager</option>
                        <option value="8">Service Person</option>
                        <option value="9">Internet Manager</option>
                        <option value="10">Internet Person</option>
                        <option value="11">General Contact</option>
                    </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Username</label>
                    <div class="formRight"><?php echo  form_input(array('class'=>'required validate[required,custom[email]]','name'=>'Username','id'=>'username')); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Personal Email</label>
                    <div class="formRight"><?php echo  form_input(array('class'=>'required validate[required,custom[email]]','name'=>'PersonalEmailAddress','id'=>'personalEmail')); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Work Email</label>
                    <div class="formRight"><?php echo  form_input(array('class'=>'required validate[custom[email]]','name'=>'WorkEmailAddress','id'=>'workEmail')); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> First Name</label>
                    <div class="formRight"><?php echo  form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'FirstName','id'=>'firstname')); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Last Name</label>
                    <div class="formRight"><?php echo  form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'LastName','id'=>'lastname')); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder"><label>Address</label><div class="formRight"><?php echo  form_input(array('class'=>'validate[custom[onlyLetterNumberSp]','name'=>'Street','id'=>'address')); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder"><label>City</label><div class="formRight"><?php echo  form_input(array('class'=>'validate[onlyLetterSp]','name'=>'City','id'=>'city')); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?php echo  showStates(); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder"><label>Zip Code</label><div class="formRight"><?php echo  form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'ZipCode','id'=>'zip')); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Direct Number</label>
                    <div class="formRight"><?php echo  form_input(array('name'=>'DirectPhone','id'=>'DirectPhone','class'=>'maskPhone validate[required,custom[phone]]')); ?><span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Mobile Number</label>
                    <div class="formRight"><?php echo  form_input(array('name'=>'MobilePhone','id'=>'MobilePhone','class'=>'maskPhone validate[custom[phone]]')); ?><span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Fax Number</label>
                    <div class="formRight"><?php echo  form_input(array('name'=>'FaxPhone','id'=>'FaxPhone','class'=>'maskPhone validate[custom[phone]]')); ?><span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder"><label>Notes</label><div class="formRight"><?php echo  form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'Notes','id'=>'notes')); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Permission Level</label>
                    <div class="formRight">
                        <select id="permissionLevels" class="required validate[required]" name="AccessLevel">
                            <option value="1">Super-Admin</option>
                            <option value="2">Admin</option>
                            <option value="3">Group Admin/option>
                            <option value="4">Client Admin</option>
                            <option value="5">Manager</option>
                            <option value="6">User</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="submitForm"><input type="submit" value="submit" class="redBtn" /></div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?php echo  form_close(); ?>

</div>

<div class="fix"></div>
</div>
