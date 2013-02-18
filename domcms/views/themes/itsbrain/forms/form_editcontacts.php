<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <!-- Form begins -->
    <?= form_open('/admin/form_processor/contacts/edit',array('id'=>'usualValidate','class'=>'mainForm','name'=>'edit')); ?>

        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Edit Contact</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Contact Type</label>
                    <div class="formRight">
                        <select class="validate[required]" name="type">
                            <option value="CID" <?= (($contact->Type == 'CID') ? 'selected="selected"' : ''); ?>>Client</option>
                            <option value="VID" <?= (($contact->Type == 'VID') ? 'selected="selected"' : ''); ?>>Vendor</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label><span class="req">*</span> Job Title</label>
                    <div class="formRight">
                    	<?= form_input(array('class'=>'validate[required]','name'=>'JobTitle','id'=>'JobTitle','value'=>$contact->JobTitle)); ?>
                        <p class="formNote">Contacts Job Title</p>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Personal Email</label>
                    <div class="formRight"><?= form_input(array('class'=>'required validate[required,custom[email]]','name'=>'PersonalEmailAddress','id'=>'email','value'=>((isset($contact->Email['home'])) ? $contact->Email['home'] : ''))); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Work Email</label>
                    <div class="formRight"><?= form_input(array('class'=>'validate[custom[email]]','name'=>'WorkEmailAddress','id'=>'email','value'=>((isset($contact->Email['work'])) ? $contact->Email['work'] : ''))); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> First Name</label>
                    <div class="formRight"><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=>$contact->FirstName)); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Last Name</label>
                    <div class="formRight"><?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>$contact->LastName)); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder"><label>Address</label><div class="formRight"><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => ((isset($contact->Address['street'])) ? $contact->Address['street'] : ''))); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder"><label>City</label><div class="formRight"><?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>((isset($contact->Address['city'])) ? $contact->Address['city'] : ''))); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?= showStates(((isset($contact->Address['state'])) ? $contact->Address['state'] : false)); ?>
                        <?= ((isset($contact->Address['state'])) ? '' : '<span class="formNote">No state found for user</span>'); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder"><label>Zip Code</label><div class="formRight"><?= form_input(array('class'=>'validate[custom[onlyNumber]]','name'=>'zip','id'=>'zip','value' => ((isset($contact->Address['zipcode'])) ? $contact->Address['zipcode'] : ''))); ?></div><div class="fix"></div></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Direct Number</label>
                    <div class="formRight"><?= form_input(array('name'=>'DirectPhone','id'=>'phone','class'=>'maskPhone validate[required,custom[phone]]','value'=>((isset($contact->Phone['main'])) ? $contact->Phone['main'] : ''))); ?><span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Mobile Number</label>
                    <div class="formRight"><?= form_input(array('name'=>'MobilePhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($contact->Phone['mobile'])) ? $contact->Phone['mobile'] : ''))); ?><span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Fax Number</label>
                    <div class="formRight"><?= form_input(array('name'=>'FaxPhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]','value'=>((isset($contact->Phone['fax'])) ? $contact->Phone['fax'] : ''))); ?><span class="formNote">(999) 999-9999</span></div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder"><label>Notes</label><div class="formRight"><?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes', 'value' => $contact->Notes)); ?></div><div class="fix"></div></div>

                <div class="submitForm">
                	<input type="hidden" name="contact_id" value="<?= $contact->ContactID; ?>" />
                    <input type="submit" value="submit" class="redBtn" />
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?= form_close(); ?>

</div>

<div class="fix"></div>
</div>
