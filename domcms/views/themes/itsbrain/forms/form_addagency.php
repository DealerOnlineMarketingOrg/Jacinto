<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?php notifyError(); ?>
    <?php echo  $html; ?>
    <!-- Form begins -->
    <?php echo  form_open('/admin/form_processor/agency/add',array('name'=>'add','class'=>'mainForm','id'=>'usualValidate')); ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Add Agency</h5></div>
                <div class="rowElem noborder">
                    <label>Agency Name<span class="req">*</span></label>
                    <div class="formRight"><?php echo  form_input(array('class','required validate[required]','name' => 'name','id'=>'name')); ?></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Agency Description</label>
                    <div class="formRight">
                        <?php echo  form_textarea(array('rows'=>'8','cols'=>'','name'=>'Notes','class'=>'auto')); ?>                      
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="submitForm">
                    <input type="submit" value="submit" class="redBtn" />
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?php echo  form_close(); ?>        
</div>
<div class="fix"></div>