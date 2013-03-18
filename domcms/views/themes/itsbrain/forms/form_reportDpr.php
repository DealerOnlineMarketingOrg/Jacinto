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
		
        echo form_open('/dpr/reports/',$form);
    ?>
	        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">DPR Report</h5>
                	<div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                        <input ID="test" class="greyishBtn" type="button" value="Test" />
                    </div>
                </div>
                <div class="rowElem noborder">
                    <!-- Line graph of year -->
                    <div class="widgets">
                        <div id="lineID" class="left">
                            <div class="widget"><!-- Pie chart 1 -->
                                <div class="head"><h5 id="update" class="iGraph">Leads Per Month</h5></div>
                                <div class="body">
                                	<? echo $report_lineChart; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="right">
                            <div class="widget"><!-- Pie chart 2 -->
                                <div class="head"><h5 class="iChart8">Total Leads</h5></div>
                                <div class="body">
                                    <? echo $report_pieChart; ?>
                                </div>
                            </div>
                        </div>
                        <div class="fix"></div>
                    </div>
                </div>
                <div class="rowElem noborder">
                	<!-- Provider data lists -->
                    <div>
                    	<?php echo $report_leads; ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?php echo  form_close(); ?>
    <script type="text/javascript">		
		$(window).load (function() {
			plotLineChart();
			plotPieChart();
		});
		function plotLineChart() {<? echo $report_lineChart_script; ?>}
		function plotPieChart() {<? echo $report_pieChart_script; ?>}
		$('input#test').click(function() {
			elementToDataURL('#lineID');
		});
		function elementToDataURL(element) {
			$(element).html2canvas({
				onrendered: function (canvas) {
					//Set hidden field's value to image data (base-64 string)
					dataURL = canvas.toDataURL("image/png");
					saveDataURL(dataURL);				}
			});
			function saveDataURL(dataURL) {
				var fileName = "assets/downloads/img.png";
				var imgData = { data: dataURL, destPath: fileName }
				$.ajax({
					type: 'POST',
					url: "<?= base_url(); ?>file/saveDataURL",
					data: imgData,
					success:function(ret) {
						alert(ret);
					}
				});
			}
			
			//dataURL = canvasRecord.toDataURL("image/png");
			//dataURL = dataURL.replace("image/png", "image/octet-stream");
     		//window.open(dataURL);
		}
		
		$('input#excel').click(function() {
			// base64 encode html since XSS filtering will strip certain
			//  tags, like style.
			var form_vars = {
				type: "excel",
				html: "<?php echo str_replace('"','\\"',$report_leads); ?>"
			}
			$.ajax({
				type: "post",
				url: "<?= base_url(); ?>converter",
				data: form_vars,
				success:function(ret) {
					alert(ret);
					//e.preventDefault();  //stop the browser from following
					//window.location.href = filePath;
				}
				//error:function(XMLHttpRequest, textStatus, errorThrown) {
					//alert(errorThrown);	
				//}
			})
			/*.done(function(data) { alert("success: " + data); })
			.fail(function(xhr, textStatus) { alert("error " + xhr.responseText); })
			.always(function() { alert("finished"); });*/
		});
		
		$('input#pdf').click(function() {
			var form_vars = {
				type: "pdf",
				html: "<?php echo str_replace('"', '\\"',$report_leads); ?>"
			}
			$.post('/converter', form_vars, function(data) {alert(data);}, "text");
		});

	</script>
</div>
<div class="fix"></div>