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
	<script type="text/javascript" src="http://phil.com/domcms/third-party/jquery.flot.text.js"></script>
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
                                <div class="head"><h5 id="update" class="iGraph">Line Graph</h5></div>
                                <div class="body">
                                    <div id="lineChart" class="chart" style="width:900px; height:200px"></div>
                                    <div id="lineChartLegend" style="display:inline;float:right"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="right">
                            <div class="widget"><!-- Pie chart 2 -->
                                <div class="head"><h5 class="iChart8">Pie Graph</h5></div>
                                <div class="body">
                                    <div id="pieChart" class="pieWidget" style="width:200px; height:200px"></div>
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
		
		jQuery(window).load (function() {plotGraphs(); writeImg();});
		
		// Gets the visual flow of the rendered web page, starting from the specified element.
		function getVisualFlow($rootElement) {
			getVisualFlowRec($rootElement);
		}
		
		// Recursively goes through the elements to get the flow.
		function getVisualFlowRec($element) {
			// Go through the child nodes recursively.
			$node = (Node) $element;
			if ($node.hasChildNodes()) {
				$children = $node->childNodes;
				foreach ($children as $child)
					getVisualFlowRec($child);
			}
		}
		
		jQuery(window).resize(function() {
			$offset = jQuery('h5#update').offset();
			jQuery('h5#update').html('Offset = ' + Math.round($offset['left']) + ',' + Math.round($offset['top']));
		});
		
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
		/*
		var series1 = {
		            label: "series 1",
		            data: [ [0,0], [1,1], [2,2], [3,3], [4,4] ]
		            };
		var series2 = {
		            label: "series 2",
		            data: [ [0,4], [1,3], [2,2], [3,1], [4,0] ]
		            };
		var data = [ series1, series2 ];
		var options = {
					grid: { 
						canvasText: {show: true, font: "sans 9px"}, 
						hoverable: true, 
						clickable: true },
					legend: {
						position: "se",
						backgroundColor: "#ff4",
						backgroundOpacity: 0.35	},
					series: { bar: {show: true} }
					};
		var linePlot = jQuery.plot(jQuery("#lineChart"), data, options);
		*/
		
		function plotGraphs() {
		    var d1 = [];
			for (var i = 0; i < Math.PI * 2; i += 0.25)
				d1.push([i, Math.sin(i)]);
		
			var d2 = [];
			for (var i = 0; i < Math.PI * 2; i += 0.25)
				d2.push([i, Math.cos(i)]);
	
			var d3 = [];
			for (var i = 0; i < Math.PI * 2; i += 0.1)
				d3.push([i, Math.tan(i)]);
		
			$.plot($("#lineChart"), [
				{ label: "sin(x)",  data: d1},
				{ label: "cos(x)",  data: d2},
				{ label: "tan(x)",  data: d3}
			], {
				series: {
					lines: { show: true },
					points: { show: true }
				},
				xaxis: {
					ticks: [0, [Math.PI*.5, "pi/2"], [Math.PI, "pi"], [Math.PI * 3*.5, "3pi/2"], [Math.PI * 2, "2pi"]]
				},
				yaxis: {
					ticks: 10,
					min: -2,
					max: 2
				},
				grid: {
					backgroundColor: { colors: ["#fff", "#eee"]},
				},
				legend: {
					position: "se",
					backgroundColor: "#ff4",
					backgroundOpacity: 0.35
				}
			});	
		}
		
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