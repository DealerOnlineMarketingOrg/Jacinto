<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <!-- Form begins -->
    <?=  form_open('/admin/form_processor/users/edit',array('id'=>'usualValidate','class'=>'mainForm','name'=>'edit')); ?>

        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Edit User</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Title</label>
                    <div class="formRight">
                    <select name="Title" id="Title" class="validate[required]">
                        <option value="1" <?=  (($user->TitleID == '1') ? 'selected="selected"' : ''); ?>>Account Executive</option>
                        <option value="2" <?=  (($user->TitleID == '2') ? 'selected="selected"' : ''); ?>>Account Manager</option>
                        <option value="3" <?=  (($user->TitleID == '3') ? 'selected="selected"' : ''); ?>>Vendor Manager</option>
                        <option value="4" <?=  (($user->TitleID == '4') ? 'selected="selected"' : ''); ?>>Owner</option>
                        <option value="5" <?=  (($user->TitleID == '5') ? 'selected="selected"' : ''); ?>>General Manager</option>
                        <option value="6" <?=  (($user->TitleID == '6') ? 'selected="selected"' : ''); ?>>Sales Manager</option>
                        <option value="7" <?=  (($user->TitleID == '7') ? 'selected="selected"' : ''); ?>>Sales Person</option>
                        <option value="8" <?=  (($user->TitleID == '8') ? 'selected="selected"' : ''); ?>>Service Manager</option>
                        <option value="9" <?=  (($user->TitleID == '9') ? 'selected="selected"' : ''); ?>>Service Person</option>
                        <option value="10" <?=  (($user->TitleID == '10') ? 'selected="selected"' : ''); ?>>Internet Manager</option>
                        <option value="11" <?=  (($user->TitleID == '11') ? 'selected="selected"' : ''); ?>>Internet Person</option>
                        <option value="12" <?=  (($user->TitleID == '12') ? 'selected="selected"' : ''); ?>>General Contact</option>
                    </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Personal Email</label>
                    <div class="formRight"><?=  form_input(array('class'=>'required validate[required,custom[email]]','name'=>'PersonalEmailAddress','id'=>'email','value'=>((isset($user->Email['home'])) ? $user->Email['home'] : ''))); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Work Email</label>
                    <div class="formRight">
						<?= form_input(array('class'=>'required validate[custom[email]]','name'=>'WorkEmailAddress','id'=>'email','value'=>((isset($user->Email['work'])) ? $user->Email['work'] : ''))); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> First Name</label>
                    <div class="formRight">
						<?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=>$user->FirstName)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Last Name</label>
                    <div class="formRight">
						<?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>$user->LastName)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Address</label>
                    <div class="formRight">
						<?=  form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => ((isset($user->Address['street'])) ? $user->Address['street'] : ''))); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>City</label>
                    <div class="formRight">
						<?=  form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>((isset($user->Address['city'])) ? $user->Address['city'] : ''))); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?=  showStates(((isset($user->Address['state'])) ? $user->Address['state'] : false)); ?>
                        <?=  ((isset($user->Address['state'])) ? '' : '<span class="formNote">No state found for user</span>'); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Zip Code</label>
                    <div class="formRight">
						<?=  form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'zip','id'=>'zip','value' => ((isset($user->Address['zipcode'])) ? $user->Address['zipcode'] : ''))); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Direct Number</label>
                    <div class="formRight">
						<?=  form_input(array('name'=>'DirectPhone','id'=>'phone','class'=>'maskPhone validate[required,custom[phone]]','value'=>((isset($user->Phone['main'])) ? $user->Phone['main'] : ''))); ?>
                        <span class="formNote">(999) 999-9999</span>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Mobile Number</label>
                    <div class="formRight">
						<?=  form_input(array('name'=>'MobilePhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($user->Phone['mobile'])) ? $user->Phone['mobile'] : ''))); ?>
                        <span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Fax Number</label>
                    <div class="formRight">
						<?= form_input(array('name'=>'FaxPhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($user->Phone['fax'])) ? $user->Phone['fax'] : ''))); ?>
                        <span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Notes</label>
                    <div class="formRight">
						<?=  form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes', 'value' => $user->Notes)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Permission Level<span class="req">*</span></label>
                    <div class="formRight">
                        <select id="permissionLevel" class="validate[required]" name="permissionlevel">
                            <option value="">Choose a level...</option>
                            <option value="1" <?=  (($user->AccessID == '1') ? 'selected="selected"' : ''); ?>>Super-Admin</option>
                            <option value="2" <?=  (($user->AccessID == '2') ? 'selected="selected"' : ''); ?>>Admin</option>
                            <option value="3" <?=  (($user->AccessID == '3') ? 'selected="selected"' : ''); ?>>Group Admin/option>
                            <option value="4" <?=  (($user->AccessID == '4') ? 'selected="selected"' : ''); ?>>Client Admin</option>
                            <option value="5" <?=  (($user->AccessID == '5') ? 'selected="selected"' : ''); ?>>Manager</option>
                            <option value="6" <?=  (($user->AccessID == '6') ? 'selected="selected"' : ''); ?>>User</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Enable/Disable</label>
                    <div class="formRight">
                        <div class="radio" id="uniform-radio1">
                            <input type="radio" id="radio1" name="Status" value="1" <?=  (($user->Status >= 1) ? 'checked="checked"' : ''); ?> style="opacity:0;"/>
                        </div>
                        <label for="radio1">Enabled</label>

                        <div class="radio" id="uniform-radio2">
                            <input type="radio" id="radio2" name="Status" value="0" <?=  (($user->Status < 1) ? 'checked="checked"' : ''); ?> style="opacity:0;" />
                        </div>
                        <label for="radio2">Disabled</label>
                    </div>
                    <div class="fix"></div>
                </div>

                <div class="submitForm">
                	<input type="hidden" name="directory_id" value="<?=  $user->DirectoryID; ?>" />
                    <input type="hidden" name="username" value="<?=  $user->Username; ?>" />
                    <input type="hidden" name="user_id" value="<?=  $user->ID; ?>" />
                    <input type="submit" value="submit" class="redBtn" />
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?=  form_close(); ?>

</div>

<div class="fix"></div>
</div>
