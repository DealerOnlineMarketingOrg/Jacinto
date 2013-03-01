
<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php echo  form_open('/admin/form_processor/groups/add',array('name'=>'addGroups','id'=>'valid','class'=>'mainForm')); ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head">
                    <h5 class="iList">Add Group</h5>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Group Name</label>
                    <div class="formRight">
                        <?php echo  form_input(array('name'=>'GroupName','id'=>'GroupName','class'=>'required validate[required]')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Group Description</label>
                    <div class="formRight">
                        <?php echo  form_textarea(array('name'=>'Description','id'=>'description','cols'=>'','rows'=>'8')); ?>
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

                <div class="submitForm">
                
                    <?php echo  form_submit(array('class'=>'redBtn','value'=>'Add Group')); ?>
                </div>
                <div class="fix"></div>
            </div>

        </fieldset>
    <?php echo  form_close(); ?>

</div>
<div class="fix"></div>