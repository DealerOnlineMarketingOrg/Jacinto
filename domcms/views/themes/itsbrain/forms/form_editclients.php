
<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?= (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
        $form = array(
            'name' => 'addClient',
            'id' => 'valid',
            'class' => 'mainForm'
        );

        echo form_open('/admin/form_processor/clients/edit',$form);
    ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Edit Client</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Client Name</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required validate[required]','name'=>'ClientName','id'=>'name','value'=>$client->Name)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Address</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'street','id'=>'address','value'=> ((isset($client->Address['street'])) ? $client->Address['street'] : ''))); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>City</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'city','id'=>'city','value' => ((isset($client->Address['city'])) ? $client->Address['city'] : ''))); ?>

                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?= showStates(((isset($client->Address['state'])) ? $client->Address['state'] : false)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Zip Code</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : ''))); ?>
                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Phone Number</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value' => ((isset($client->Phone['main'])) ? $client->Phone['main'] : ''))); ?>
                        <span class="formNote">(999) 999-9999</span>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Notes</label>
                    <div class="formRight">
                        <?= form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>$client->Description)); ?>
                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Client Code</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>$client->Code)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Enable/Disable</label>
                    <div class="formRight">
                        <div class="radio" id="uniform-radio1">
                            <input type="radio" id="radio1" name="Status" value="1" <?= (($client->Status != 0) ? 'checked="checked"' : ''); ?> style="opacity:0;" />
                        </div>
                        <label for="radio1">Enabled</label>

                        <div class="radio" id="uniform-radio2">
                            <input type="radio" id="radio2" name="Status" value="0" <?= (($client->Status != 1) ? 'checked="checked"' : ''); ?> style="opacity:0;" />
                        </div>
                        <label for="radio2">Disabled</label>
                    </div>
                    <div class="fix"></div>
                </div>

                <!-- <div class="rowElem noborder">
                    <label>Member of<span class="req">*</span></label>
                    <div class="formRight">
                        
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Tags</label>
                    <div class="formRight">
                        <select name="select2" name="tags">
                            <option value="opt1">Choose a team...</option>
                            <option>Red Team</option>
                            <option>Blue Team</option>
                            <option>Green Team</option>
                            <option>Purple Team</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div> -->
                <div class="submitForm"><input type="hidden" name="ClientID" value="<?= $client->ClientID; ?>" /><input type="submit" value="submit" class="redBtn" /></div>
                <div class="fix"></div>
            </div>

        </fieldset>
    <?= form_close(); ?>
    
</div>
<div class="fix"></div>