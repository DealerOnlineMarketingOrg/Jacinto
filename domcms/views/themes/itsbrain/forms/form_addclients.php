
<!-- Content -->
<div class="content hideTagFilter" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?= (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
        $form = array(
            'name' => 'addClient',
            'id' => 'valid',
            'class' => 'mainForm'
        );

        echo form_open('/admin/form_processor/clients/add',$form);
    ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Add Client</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Client Name</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required validate[required]','name'=>'ClientName','id'=>'name')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Address</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'street','id'=>'address')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>City</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'city','id'=>'city')); ?>

                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?= showStates(); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Zip Code</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'zip','id'=>'zip','maxlength'=>'5')); ?>
                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Phone Number</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone')); ?>
                        <span class="formNote">(999) 999-9999</span>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Notes</label>
                    <div class="formRight">
                        <?= form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes')); ?>
                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Client Code</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required validate[required]','name'=>'ClientCode','id'=>'code')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Tags</label>
                    <div class="formRight searchDrop">
                        <select style="width:200px;" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="9">
                            <option value=""></option>
                            <?php foreach($tags as $tag) : ?>
                            	<option value="<?= $tag->ID; ?>"><?= $tag->Name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>

                <div class="rowElem noborder">
                    <label>Enable/Disable</label>
                    <div class="formRight">
                        <div class="radio" id="uniform-radio1">
                            <input type="radio" id="radio1" name="Status" value="1" checked="checked" style="opacity:0;"/>
                        </div>
                        <label for="radio1">Enabled</label>

                        <div class="radio" id="uniform-radio2">
                            <input type="radio" id="radio2" name="Status" value="0" style="opacity:0;" />
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
                </div> -->
                <div class="fix"></div>
                <div class="submitForm"><input type="submit" value="submit" class="redBtn" /></div>
                <div class="fix"></div>
            </div>

        </fieldset>
    <?= form_close(); ?>
    
</div>
<div class="fix"></div>