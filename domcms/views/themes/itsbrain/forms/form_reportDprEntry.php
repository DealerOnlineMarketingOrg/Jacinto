<!-- Content -->
<div class="content hideTagFilter" id="container">
    <div class="title"><h5>Reports</h5></div>
    <?php notifyError(); ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
        $form = array(
            'name' => 'reportDpr',
            'class' => 'mainForm valid'
        );
		
        echo form_open('/dpr/reports',$form);
    ?>
	        <!-- Input text fields -->
        <fieldset>
            <div id="reportEntry">
                <label>Starting Month:</label><input type="text" class="required validate[required]" style="width:150px" id="startMonth" name="startMonth" placeholder="Starting month" max-length="10" /><br />
                <label>Starting Year:</label><input type="text" class="required validate[required]" style="width:150px" id="startYear" name="startYear" placeholder="Starting year" max-length="10" /><br />
                <label>Ending Month:</label><input type="text" class="required validate[required]" style="width:150px" id="endingMonth" name="endingMonth" placeholder="Ending month" max-length="10" value="<?php echo date('m'); ?>" /><br />
                <label>Ending Year:</label><input type="text" class="required validate[required]" style="width:150px" id="endingYear" name="endingYear" placeholder="Ending year" max-length="10" value="<?php echo date('Y'); ?>" />
            </div>
            
            <input type="submit" value="submit" class="redBtn" />
        </fieldset>
    <?php echo  form_close(); ?>
    
    <script type="text/javascript">
		
	</script>
</div>
<div class="fix"></div>