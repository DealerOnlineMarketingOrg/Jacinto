
<!-- Content -->
<div class="content hideTagFilter" id="container">
	
    <div class="title"><h5>Admin</h5></div>
    <?php notifyError(); ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    <?php echo  form_open('/admin/groups/form_processor/groups/edit',array('name'=>'addGroups','id'=>'valid','class'=>'mainForm')); ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head">
                    <h5 class="iList">Add Group</h5>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Group Name</label>
                    <div class="formRight">
                        <?php echo  form_input(array('name'=>'GroupName','id'=>'GroupName','class'=>'required validate[required]','value'=>$group->Name)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Group Description</label>
                    <div class="formRight">
                        <?php echo  form_textarea(array('name'=>'Description','id'=>'description','cols'=>'','rows'=>'8','value'=>$group->Description)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Enable/Disable</label>
                    <div class="formRight">
                        <div class="radio" id="uniform-radio1">
                            <input type="radio" id="radio1" name="Status" value="1" <?php echo  (($group->Status != 0) ? 'checked="checked"' : ''); ?> style="opacity:0;"/>
                        </div>
                        <label for="radio1">Enabled</label>

                        <div class="radio" id="uniform-radio2">
                            <input type="radio" id="radio2" name="Status" value="0" <?php echo  (($group->Status != 1) ? 'checked="checked"' : ''); ?>style="opacity:0;" />
                        </div>
                        <label for="radio2">Disabled</label>
                    </div>
                    <div class="fix"></div>
                </div>

                <div class="submitForm">
                	<?php echo  form_hidden('GroupID',$group->GroupID); ?>
                    <?php echo  form_submit(array('class'=>'redBtn','value'=>'Edit Group')); ?>
                </div>
                <div class="fix"></div>
            </div>

        </fieldset>
    <?php echo  form_close(); ?>

</div>
<div class="fix"></div>