	<!-- Content -->
    <div class="content" id="container">
    	<div class="title"><h5>Admin</h5></div>
        <?= (($html) ? $html : ''); ?>
        <!-- Statistics -->        
        <!-- Form begins -->
        <?= form_open('/admin/form_processor/contacts/add',array('name'=>'addContacts','id'=>'valid','class'=>'mainForm')); ?>
        	<!-- Input text fields -->
            <fieldset>
                <div class="widget first">
                    <div class="head"><h5 class="iList">Add Contacts</h5></div>
                        <div class="rowElem noborder">
                            <label>Contact Type<span class="req">*</span></label>
                            <div class="formRight">
                            <select class="validate[required]" name="type">
                                <option value="CID">Client</option>
                                <option value="VID">Vendor</option>
                            </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Job Title</label>
                            <div class="formRight">
                                <?= form_input(array('class'=>'validate[required]','name'=>'JobTitle','id'=>'JobTitle')); ?>
                                <p class="formNote">Contacts Job Title</p>
                            </div>
                            <div class="fix"></div>
                        </div>

                        <div class="rowElem noborder">
                            <label>First Name<span class="req">*</span></label>
                            <div class="formRight"><input type="text" class="validate[required]'" name="firstname" id="firstname" /></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Last Name<span class="req">*</span></label>
                            <div class="formRight"><input type="text" class="validate[required]" name="lastname" id="lastname" /></div><div class="fix"></div>
                        </div>
                        <div class="rowElem noborder"><label>Address</label><div class="formRight"><input type="text" name="street" /></div><div class="fix"></div></div>
                        <div class="rowElem noborder"><label>City</label><div class="formRight"><input type="text" name="city" /></div><div class="fix"></div></div>
                        <div class="rowElem noborder">
                            <label>State</label>
                            <div class="formRight searchDrop">
                            <select data-placeholder="Choose a State..." class="chzn-select" style="width:350px;" tabindex="2" name="state">
                                <option value=""></option> 
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                            </div>             
                            <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder"><label>Zip Code</label><div class="formRight"><input id="zip" type="text" name="zip" /></div><div class="fix"></div></div>
                            <div class="rowElem noborder">
                                <label><span class="req">*</span> Personal Email</label>
                                <div class="formRight"><?= form_input(array('class'=>'validate[required,custom[email]]','name'=>'PersonalEmailAddress','id'=>'email')); ?></div><div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Work Email</label>
                                <div class="formRight"><?= form_input(array('class'=>'validate[custom[email]]','name'=>'WorkEmailAddress','id'=>'email')); ?></div><div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label><span class="req">*</span> Direct Number</label>
                                <div class="formRight"><?= form_input(array('name'=>'DirectPhone','id'=>'phone','class'=>'maskPhone validate[required,custom[phone]]')); ?><span class="formNote">(999) 999-9999</span></div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Mobile Number</label>
                                <div class="formRight"><?= form_input(array('name'=>'MobilePhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]')); ?><span class="formNote">(999) 999-9999</span></div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Fax Number</label>
                                <div class="formRight"><?= form_input(array('name'=>'FaxPhone','id'=>'phone','class'=>'maskPhone validate[custom[phone]]')); ?><span class="formNote">(999) 999-9999</span></div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder"><label>Notes</label><div class="formRight"><textarea rows="8" cols="" class="auto" name="notes"></textarea></div><div class="fix"></div></div>
                            <div class="submitForm"><input type="submit" value="submit" class="redBtn" /></div>
                            <div class="fix"></div>
                    </div>
                
            </fieldset>
        </form>        
            
    </div>
    
    <div class="fix"></div>
