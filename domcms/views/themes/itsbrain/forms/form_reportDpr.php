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
            <div id="fullReportID" class="widget first">
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
                            <div id="lineID" class="widget">
                                <div class="head"><h5 id="update" class="iGraph">Leads Per Month</h5></div>
                                <div class="body">
                                	<? echo $report_lineChart; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pie total overall -->
                        <div class="right">
                            <div id="pieID" class="widget">
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
                    <div id="tableID">
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
	
		// Use base64 encoding if XSS filtering is active, since
		//  XSS will strip certain tags, like style.

		$('input#excel').click(function() {
			// Convert charts to images and prepend to report.
			var report_leads = "<?php echo str_replace('"','\\"',$report_leads); ?>";
			var lineName = "assets/uploads/lineChart.png";
			saveHtmlImage("#lineID", lineName);
			var pieName = "assets/uploads/pieChart.png";
			saveHtmlImage("#pieID", pieName);
			// Prepend images into report.
			// We'll float the images together.
			var report = report_leads.replace(/(<table[^>]*?>)/i, "$1" +
				"<tr>" +
					"<td>" +
						"<img src=\"" + lineName + "\" />" +
					"</td>" +
					"<td></td><td></td><td></td><td></td>" +
					"<td>" +
						"<img src=\"" + pieName + "\" />" +
					"</td>" +
				"</tr>");
			var form_vars = {
				type: "excel",
				file: "assets/uploads/dprReport.xlsx",
				html: report
			}
			$.ajax({
				type: "post",
				url: "<?= base_url(); ?>converter",
				data: form_vars,
				success:function(ret) {
					// Do any extra success stuff here.
					$.fileDownload("<?= base_url(); ?>" + form_vars['file']);
					alert(ret);
				}
			})
		});
		
		$('input#pdf').click(function() {
			fullReportName = "assets/uploads/fullReport.png";
			saveHtmlImage("#fullReportID", fullReportName);
			var form_vars = {
				type: "pdf",
				file: "assets/uploads/dprReport.pdf",
				img: fullReportName,
				scale: .25
			}
			$.ajax({
				type: "post",
				url: "<?= base_url(); ?>converter",
				data: form_vars,
				success:function(ret) {
					// Do any extra success stuff here.
					$.fileDownload("<?= base_url(); ?>" + form_vars['file']);
					alert(ret);
				}
			})
		});
		
		function saveDataURL(dataURL, fileName) {
			var imgData = { data: dataURL, destPath: fileName }
			$.ajax({
				type: 'POST',
				url: "<?= base_url(); ?>file/saveDataURL",
				data: imgData,
				success:function(ret) {
					// Do any extra success stuff here.
				}
			});
		}

		function saveHtmlImage(element, fileName) {		
			$(element).html2canvas({
				onrendered: function (canvas) {
					//Set hidden field's value to image data (base-64 string)
					dataURL = canvas.toDataURL("image/png");
					saveDataURL(dataURL, fileName);
				}
			});
		}

	</script>
</div>
<div class="fix"></div>