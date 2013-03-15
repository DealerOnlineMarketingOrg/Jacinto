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
		
        echo form_open('/reportdpr/',$form);
    ?>
	        <!-- Input text fields -->
        <fieldset>
            <div id="reportID" class="widget first">
                <div class="head"><h5 class="iList">DPR Report</h5>
                	<div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                        <!-- <input ID="test" class="greyishBtn" type="button" value="Test" /> -->
                    </div>
                </div>
                <div class="rowElem noborder">
                    <!-- Line graph of year -->
                    <div class="widgets">
                        <div class="left">
                            <div id="lineID" class="widget"><!-- Pie chart 1 -->
                                <div class="head"><h5 id="update" class="iGraph">Leads Per Month</h5></div>
                                <div class="body">
                                	<? echo $report_lineChart; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="right">
                            <div id="pieID" class="widget"><!-- Pie chart 2 -->
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
			elementToDataURL();
		});
		function plotLineChart() {<? echo $report_lineChart_script; ?>}
		function plotPieChart() {<? echo $report_pieChart_script; ?>}
		
		$('input#test').click(function() {
			elementToDataURL('#reportID');
		});
		function elementToDataURL($element) {
			$($element).html2canvas({
				onrendered: function (canvas) {
					//Set hidden field's value to image data (base-64 string)
					dataURL = canvas.toDataURL("image/png");
					saveDataURL(dataURL);
				}
			});
		}
		function saveDataURL(dataURL) {
			var fileName = "domcms/cache/img.png";
			var imgData = { data: dataURL, destPath: fileName }
			$.ajax({
				type: 'POST',
				url: "<?= base_url(); ?>file/saveDataURL",
				data: imgData,
				success:function(data) {
					alert(data);
				}
			});
		}
		
		$('input#excel').click(function() {
			var form_vars = {
				type: "excel",
				html: "<?php echo str_replace('"','\\"',$providers); ?>"
			}
			$.ajax({
				type: "post",
				url: "<?= base_url(); ?>converter", 
				data: form_vars,
				success:function(data) {
					//alert(data);
					//e.preventDefault();  //stop the browser from following
					//window.location.href = filePath;
				},
				//error:function(XMLHttpRequest, textStatus, errorThrown) {
				//	alert(errorThrown);	
				//}
			});
			/*.done(function(data) { alert("success: " + data); })
			.fail(function(xhr, textStatus) { alert("error " + xhr.responseText); })
			.always(function() { alert("finished"); });*/
		});
		
		$('input#pdf').click(function() {
			var form_vars = {
				type: "pdf",
				html: "<?php echo str_replace('"', '\\"',$providers); ?>"
			}
			$.post('/converter', form_vars, function(data) {alert(data);}, "text");
		});
		
		function saveImg() {
			var divobj = document.getElementById("lineChart");
			//var oImg = Canvas2Image.saveAsPNG(divobj.childNodes[0], true);
			var canvas = divobj.childNodes[0];
			var img    = canvas.toDataURL("image/png");
			
			//document.write('<img src="'+img+'"/>');
		}
	</script>
</div>
<div class="fix"></div>