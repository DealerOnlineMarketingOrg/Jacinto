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


		// All function calls defined here (for deferreds).
		
		// Gets a dataURL from the element's html (includes children),
		//  and saves that to the server as an image.
		function HTMLToImage(imgFile,element)
			{$(element).html2canvas({onrendered:function(canvas){
					 dataURL = canvas.toDataURL("image/png");
					 return $.ajax({type:"POST",
									url:"<?= base_url(); ?>file/saveDataURL",
									data:{data:dataURL, destPath:imgFile}});
			}});}
							
		// Converts a html piece into an excel file, saving it to
		//  destFile (on the server).
		function convertToExcel(destFile,reportHtml)
			{return $.ajax({type:"POST",
							url:"<?= base_url(); ?>converter",
							data:{type:"excel", file:destFile, html:reportHtml}});
			}
							
		// Converts a html piece into an PDF file, saving it to
		//  destFile (on the server).
		function convertToPDF(destFile,image,imageScale)
			{return $.ajax({type:"POST",
							url:"<?= base_url(); ?>converter",
							data:{type:"PDF", file:destFile, img:image, scale:imageScale}});
			}
							
		// Zips up the files in fileList and saves as zipFile (on the server).
		function saveZip(zipFile,fileList)
			{return $.ajax({type:"POST",
							url:"<?= base_url(); ?>file/zipFiles",
							data:{file_list:fileList, zip_file:zipFile}});
			}
		
		// Retrieves the zip file (as a download-file option).
		function getZip(zipFile)
			{$.fileDownload("<?= base_url(); ?>" + zipFile);
			}
		
		
		$('input#excel').click(function() {
			// Compile the report.
			var lineChartFile = "assets/uploads/lineChart.png";
			var pieChartFile = "assets/uploads/pieChart.png";
			var report_leads = "<?php echo str_replace('"','\\"',$report_leads); ?>";
			var report = report_leads.replace(/(<table[^>]*?>)/i, "$1" +
				"<tr>" +
					"<td><img src=\"" + lineChartFile + "\" /></td>" +
					"<td></td><td></td><td></td><td></td><td></td>" +
					"<td><img src=\"" + pieChartFile + "\" /></td>" +
				"</tr>");			
			
			// Convert the line and pie charts into images and save (to server).
			$.when(HTMLToImage(lineChartFile,"#lineID"), HTMLToImage(pieChartFile,"#pieID"))
				.then(function(ret) {});

			// Convert the report into excel and save (uses chart images from above).
			$.when(convertToExcel("assets/uploads/dprReport.xlsx", report))
				.then(function(ret) {});
				
			// Create the zip file which will contain the excel report.
			$.when(saveZip("assets/uploads/dprReport.zip", [ "assets/uploads/dprReport.xlsx" ]))
				.then(function(ret) {});
				
			// Give the user the chance to save the zip file.
			$.when(getZip("assets/uploads/dprReport.zip"))
				.then(function(ret) {});
		});
		
		
		$('input#pdf').click(function() {
			fullReportFile = "assets/uploads/fullReport.png";
			
			// Convert the whole report into an image and save (to server).
			$.when(HTMLToImage(fullReportFile,"#fullReportID"))
				.then(function(ret) {});

			// Convert the report into pdf and save (uses chart images from above).
			$.when(convertToPDF("assets/uploads/dprReport.pdf", fullReportFile, 1))
				.then(function(ret) {});
				
			// Create the zip file which will contain the pdf report.
			$.when(saveZip("assets/uploads/dprReport.zip", [ "assets/uploads/dprReport.pdf" ]))
				.then(function(ret) {});
				
			// Give the user the chance to save the zip file.
			$.when(getZip("assets/uploads/dprReport.zip"))
				.then(function(ret) {});
		});
		
	</script>
</div>
<div class="fix"></div>