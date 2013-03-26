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
	?>
		<?php echo form_open('/dpr/reports',$form); ?>
            <!-- Input text fields -->
            <fieldset>
			<?php if ($this->user['DropdownDefault']->SelectedClient == 0) { ?>
	            <div>
                    <div style="width:1;float:left;vertical-align:middle">
                        <input ID="add" class="greenBtn" type="button" value="Add Leads" />
                    </div>
                </div>
   	            <div class="widget" style="margin-top:0px">
                	<div class="head"><h5 class="iList">DPR Leads Report: <span style="color:red">No dealer is selected. Please select a dealer to view the DPR reports.</span></h5></div>
				</div>
            <?php } else { ?>
            	<div>
                    <div style="width:1;float:left;vertical-align:middle">
                        <input ID="add" class="greenBtn" type="button" value="Add Leads" />
                        <input ID="editReport" class="seaBtn" type="button" value="Edit Report" />
                    </div>
                    <div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                    </div>
                </div>
                <div id="reportLive">
                    <div id="fullReportID" class="widget first" style="margin-top:0px !important">
                        <div class="head"><h5 class="iList">DPR Leads Report</h5>
                        </div>
                        <div class="rowElem noborder">
                            <!-- Line graph of year -->
                            <div class="widgets">
                                <div class="left">
                                    <div id="lineID" class="widget" style="margin-top:0px !important">
                                        <div class="head"><h5 id="update" class="iGraph">Leads Per Month</h5></div>
                                        <div class="body">
                                            <? echo $report_lineChart; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pie total overall -->
                                <div class="right">
                                    <div id="pieID" class="widget" style="margin-top:0px !important">
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
                    <div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                    </div>
				</div>
			<?php } ?>
    	</fieldset>
    <?php echo  form_close(); ?>
    <?php
        $form = array(
            'id' => 'reportDprEdit',
			'name' => 'reportDprEdit',
            'class' => 'mainForm valid'
        );
	?>
    <?php echo form_open('/dpr/editReport',$form); ?>
    	<input ID="startMonth" name="startMonth" type="hidden" value="<?php echo $dateRange['startMonth']; ?>" />
        <input ID="startYear" name="startYear" type="hidden" value="<?php echo $dateRange['startYear']; ?>" />
        <input ID="endMonth" name="endMonth" type="hidden" value="<?php echo $dateRange['endMonth']; ?>" />
        <input ID="endYear" name="endYear" type="hidden" value="<?php echo $dateRange['endYear']; ?>" />
    <?php echo  form_close(); ?>
    
    <script type="text/javascript">
		$(window).load (function() {
			plotLineChart();
			plotPieChart();
		});
		
		function plotLineChart() {<?php echo $report_lineChart_script; ?>}
		function plotPieChart() {<?php echo $report_pieChart_script; ?>}
	
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
		
		$('input#add').click(function() {
			window.location.href = "<?= base_url(); ?>dpr/add";
		});
		
		$('input#editReport').click(function() {
			$("form#reportDprEdit").submit();
		});
		
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
			
			// HTMLToImage (lineChartFile)
			$("#lineID").html2canvas({onrendered:function(canvas){
					 dataURL = canvas.toDataURL("image/png");
					 $.ajax({type:"POST",
							url:"<?= base_url(); ?>file/saveDataURL",
							data:{data:dataURL, destPath:lineChartFile},
							success:(function() {
								// HTMLToImage (pieChartFile)
								$("#pieID").html2canvas({onrendered:function(canvas){
										 dataURL = canvas.toDataURL("image/png");
										 $.ajax({type:"POST",
												url:"<?= base_url(); ?>file/saveDataURL",
												data:{data:dataURL, destPath:pieChartFile},
												success:(function() {
													// convertToExcel
													$.ajax({type:"POST",
															url:"<?= base_url(); ?>converter",
															data:{type:"excel", file:"assets/uploads/dprReport.xlsx", html:report},
															success:(function() {
																// saveZip
																$.ajax({type:"POST",
																		url:"<?= base_url(); ?>file/zipFiles",
																		data:{file_list:[ "assets/uploads/dprReport.xlsx" ], zip_file:"assets/uploads/dprReportExcel.zip"},
																		success:(function() {
																			// getZip
																			$.fileDownload("<?= base_url(); ?>" + "assets/uploads/dprReportExcel.zip");
																		})
																});
															})
													});
												})
										 });
								}});
							})
					});
			}});
		});
		
		
		$('input#pdf').click(function() {
			fullReportFile = "assets/uploads/fullReport.png";
			
			// HTMLToImage
			$("#fullReportID").html2canvas({onrendered:function(canvas){
					 dataURL = canvas.toDataURL("image/png");
					 $.ajax({type:"POST",
							url:"<?= base_url(); ?>file/saveDataURL",
							data:{data:dataURL, destPath:fullReportFile},
							success:(function() {
								// convertToPDF
								$.ajax({type:"POST",
										url:"<?= base_url(); ?>converter",
										data:{type:"pdf", file:"assets/uploads/dprReport.pdf", img:fullReportFile, scale:1},
										success:(function(data, textStatus) {
											// saveZip
											$.ajax({type:"POST",
													url:"<?= base_url(); ?>file/zipFiles",
													data:{file_list:[ "assets/uploads/dprReport.pdf" ], zip_file:"assets/uploads/dprReportPDF.zip"},
													success:(function() {
														// getZip
														$.fileDownload("<?= base_url(); ?>" + "assets/uploads/dprReportPDF.zip");
													})
											});
										})
								});
							})
					});
			}});
		});
		
	</script>
</div>
<div class="fix"></div>