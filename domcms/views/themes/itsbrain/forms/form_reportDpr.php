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
                    <div class="widgets">
                        <div class="left">
                            <div class="widget"><!-- Pie chart 1 -->
                                <div class="head"><h5 class="iGraph">Line Graph</h5></div>
                                <div class="body">
                                    <div id="lineChart" class="chart" style="width:900px; height:200px"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="right">
                            <div class="widget"><!-- Pie chart 2 -->
                                <div class="head"><h5 class="iChart8">Pie Graph</h5></div>
                                <div class="body">
                                    <div id="pieChart" class="pieWidget" style="width:100px; height:100px"></div>
                                </div>
                            </div>
                        </div>
                        <div class="fix"></div>
                    </div>
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
		//jQuery("#loading").bind("complete", function() {
		//	jQuery("input#excel").hide();
		//});
		
		jQuery('input#excel').click(function() {
			var form_vars = {
				type: "excel",
				html: "<?php echo str_replace('"', '\\"',$providers); ?>"
			}
			$.ajax({
				type: "post",
				url: "<?= base_url(); ?>converter", 
				data: {type:'excel',html:"<?php echo str_replace('"', '\\"',$providers); ?>"},
				success:function(data) {
					alert(data);
				}
			})
			/*.done(function(data) { alert("success: " + data); })
			.fail(function(xhr, textStatus) { alert("error " + xhr.responseText); })
			.always(function() { alert("finished"); });*/
		});
		
		jQuery('input#pdf').click(function() {
			var form_vars = {
				type: "pdf",
				html: "<?php echo str_replace('"', '\\"',$providers); ?>"
			}
			jQuery.post('/converter', form_vars, function(data) {alert(data);}, "text");
		});

		jQuery.plot(jQuery("#lineChart"), [
			{ label: "Foo", data: [ [10, 1], [17, -14], [30, 5] ] },
			{ label: "Bar", data: [ [11, 13], [19, 11], [30, -7] ] } ],
			{ yaxis: { max: 1 } }, legend: { position: "nw"} } );
	</script>
</div>
<div class="fix"></div>