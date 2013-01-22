<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <!-- Form begins -->
    <?php
		$form = array(
			'name' => 'EditAgency',
			'id' => 'EditAgency',
			'class' => 'mainForm'
		);
		
		echo form_open('admin/form_processor/agency/edit',$form);
		
	?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Edit Agency</h5></div>
                <div class="rowElem noborder">
                    <label>Agency Name<span class="req">*</span></label>
                    <div class="formRight"><input type="text" class="required" name="AGENCY_Name" id="name" value="<?= $agency->Name; ?>" /></div><div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Agency Description</label>
                    <div class="formRight">
                        <textarea name="AGENCY_Notes" rows="8" cols="" class="auto" name="notes"><?= $agency->Description; ?></textarea>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Enable/Disable</label>
                    <div class="formRight">
                        <div class="radio" id="uniform-radio1">
                        	<input type="radio" id="radio1" name="AGENCY_Active" value="1" <?= (($agency->Status >= 1) ? 'checked="checked"' : ''); ?> style="opacity:0;"/>
                        </div>
                        <label for="radio1">Enabled</label>

                        <div class="radio" id="uniform-radio2">
                        	<input type="radio" id="radio2" name="AGENCY_Active" value="0" <?= (($agency->Status < 1) ? 'checked="checked"' : ''); ?> style="opacity:0;" />
                        </div>
                        <label for="radio2">Disabled</label>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="submitForm">
                	<input type="hidden" name="AGENCY_ID" value="<?= $agency->Id; ?>" />
                    <input type="hidden" name="AGENCY_Created" value="<?= $agency->Created; ?>" />
                    <input type="submit" value="submit" class="redBtn" />
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?= form_close(); ?>       
</div>
<div class="fix"></div>