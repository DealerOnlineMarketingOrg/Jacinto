<!-- Content -->
<div class="content hideTagFilter" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
        $form = array(
            'name' => 'reportDpr',
            'class' => 'mainForm valid'
        );
		
        echo form_open('/reportdpr/',$form);
    ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">DPR Report</h5>
                	<div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                    </div>
                </div>
                <div class="rowElem noborder">
                    <!-- Line graph of year -->
                    <div style="color:lightgrey">
                    	Line graph will go here.
					</div>
                    <!-- Pie graph of provider comparisons -->
					<div style="color:lightgrey;display:inline">
                    	Pie graph will go here.
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<!-- Provider data lists -->
                    <div>
                    	<?php echo $providers; ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?php echo  form_close(); ?>
	<script type="text/javascript">		
		jQuery("#loading").bind("complete", function() {
			jQuery("input#excel").hide();
		});
		
		jQuery('input#excel').bind('click', function() {
			var form_vars = {
				type: "excel",
				html: "<?php echo str_replace('"', '\\"',$providers); ?>"
			}
			jQuery.post('/converter', form_vars, function(data) {alert(data);}, "text");
		});
		
		jQuery('input#pdf').bind('click', function() {
			var form_vars = {
				type: "pdf",
				html: "<?php echo str_replace('"', '\\"',$providers); ?>"
			}
			jQuery.post('/converter', form_vars, function(data) {alert(data);}, "text");
		});
	</script>
</div>
<div class="fix"></div>