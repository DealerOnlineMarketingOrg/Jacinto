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
                        <div class="left">
                            <div class="widget"><!-- Pie chart 1 -->
                                <div class="head"><h5 id="update" class="iGraph">Line Graph</h5></div>
                                <div class="body">
                                	<? echo $report_lineChart; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="right">
                            <div class="widget"><!-- Pie chart 2 -->
                                <div class="head"><h5 class="iChart8">Pie Graph</h5></div>
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
			getVisualLayout();
			plotLineChart();
			plotPieChart();
			elementToDataURL();
		});
		function plotLineChart() {<? echo $report_lineChart_script; ?>}
		function plotPieChart() {<? echo $report_pieChart_script; ?>}
		function elementToDataURL() {
			$('#reportBlock_lineChart').html2canvas({
				onrendered: function (canvas) {
					//Set hidden field's value to image data (base-64 string)
					//window.open(canvas.toDataURL("image/png"));
					//Submit the form manually
					document.getElementById("reportDpr").submit();
				}
			});
			
			//dataURL = canvasRecord.toDataURL("image/png");
			//dataURL = dataURL.replace("image/png", "image/octet-stream");
     		//window.open(dataURL);
		}
		
		$('input#test').click(function() {
			alert('begin');
			$layout = getVisualLayout();
			$str = "";
			for (var i = 0; i <$layout.length; i++) {
				$str += $layout[i]['id'] + ",";
			}
			alert($str);
		});
		// Returns the visual layout of the rendered web page, starting from the specified element.
		function getVisualLayout() {
			// The report root object should have an ID of reportBlock
			$str = "";
			// Layout will be an array of objects, each one having id, left and top.
			$Layout = [];
			$("#reportBlock, #reportBlock *").each(function(index, domElem) {
				$id = $(this).attr('id');
				$text = $(this).text();
				if ($text.search("1500") > -1)
					{ alert("found"); }
				try {
					$offset = $(this).offset();
					$Layout.push({
						"id": $id,
						"left": Math.round($offset['left']),
						"top": Math.round($offset['top'])
					});
				} catch(e) {}
			});
			return $Layout;
		}
		
		$(window).resize(function() {
			$offset = $('h5#update').offset();
			//$('h5#update').html('Offset = ' + Math.round($offset['left']) + ',' + Math.round($offset['top']));
		});
		
		$('input#excel').click(function() {
			var form_vars = {
				type: "excel",
				html: "<?php echo str_replace('"','\\"',$providers); ?>"
			}
			$.ajax({
				type: "post",
				url: "<?= base_url(); ?>converter", 
				data: {type:'excel',html:"<?php echo str_replace('"','\\"',$providers); ?>"},
				success:function(data) {
					alert(data);
				}
			})
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
		
		function writeImg() {
			var divobj = document.getElementById("lineChart");
			//var oImg = Canvas2Image.saveAsPNG(divobj.childNodes[0], true);
			var canvas = divobj.childNodes[0];
			var img    = canvas.toDataURL("image/png");
			
			//document.write('<img src="'+img+'"/>');
		}
	</script>
</div>
<div class="fix"></div>