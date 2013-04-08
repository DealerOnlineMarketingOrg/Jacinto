<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  	<title>JQuery Form Wizard</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" ></meta>
 		<link rel="stylesheet" type="text/css" href="./css/cupertino/jquery-ui-1.8.2.custom.css" />  
    		<link rel="stylesheet" type="text/css" href="./css/main.css" />
	</head>
	<body>
		<div id="headerwrapper">
			<div id="header">
				<div id="logo">
					<div id="logo_image"></div>
					<div id="logo_tag"><h2>jQuery Formwizard Plugin Demo and Documentation </h2></div>
				</div>
				<div id="navigation">
					<ul>
						<li><a id="contact" href="">Contact</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div id="wrapper">
			<div id="content_wrapper"><hr />
				<div id="right_content" class="border">
				
					<div id="changelog">
						<h3>Changelog</h3>
						<div id="calendar"></div>
					</div>
					<div id="changelog_for_date" class="ui-widget-content ui-corner-all"></div>
					<div id="support_other_projects" >
						
					</div>
				</div>
				<div id="main_content" class="border">
					<div id="demo">
						<div id="demoWrapper">
							<h3>Wizard Demo</h3>
		 					<form id="demoForm" method="post" action="json.html">
		 						<div id="fieldWrapper">
		 						<span class="step" id="first">
		 							<span class="font_normal_07em_black">First step - Enter Lead Source</span><br />
                                    <select class="input_field_12em required" name="leadSource" id="leadSource">
			 							<option value=""></option>
			 							<option value="source1">Source 1</option>
			 							<option value="source2">Source 2</option>
			 							<option value="source3">Source 3</option>
			 						</select><br />
                                    <input type="hidden" class="link" name="link1" id="link1" value="step2" />
		 						</span>
		 						<span id="step2" class="step">
		 							<span>Step 2 - Chose Metric</span><br />
                                    <select class="input_field_12em required" name="metric" id="metric">
			 							<option value=""></option>
			 							<option value="metric1">Metric 1</option>
			 							<option value="metric2">Metric 2</option>
			 							<option value="metric3">Metric 3</option>
			 						</select><br />
                                    <input type="hidden" class="link" name="link2" id="link2" value="step3" /> 						
		 						</span>
		 						<span id="step3" class="step">
		 							<span class="font_normal_07em_black">Step 3 - Chose date range</span><br />
                                    <label for="start">Starting Date</label><br />
                                    <input class="input_field_25em" name="monthStart" id="monthStart" value="MM"/>
                                    <input class="input_field_3em" name="yearStart" id="yearStart" value="YYYY"/> 
                                    <label for="end">Ending Date</label><br />
                                    <input class="input_field_25em" name="monthEnd" id="monthEnd" value="MM"/>
                                    <input class="input_field_3em" name="yearEnd" id="yearEnd" value="YYYY"/> 
                                    <input type="hidden" class="link" name="link2" id="link2" value="step4" /> 						
		 						</span>
		 						<span id="step4" class="step">
		 							<span class="font_normal_07em_black">Last step - Input Data</span><br />
		 							<label for="username">User name</label><br />
			 						<input class="input_field_12em" name="username" id="username"/><br />
			 						<label for="password">Password</label><br />
			 						<input type="password" class="input_field_12em" name="password" id="password"/><br />
			 						<label for="retypePassword">Retype password</label><br />
			 						<input type="password" class="input_field_12em" name="retypePassword" id="retypePassword"/><br />
		 						</span>
		 						</div>
		 						<div id="demoNavigation"> 							
		 							<input type="reset" class="navigation_button" value="Reset Form" />
		 							<input type="submit" class="navigation_button" value="Submit" />
		 						</div>
		 					</form>
		 				</div>
					</div>
				</div>
				<div id="plugin_information" class="border">
					<h3>Documentation</h3>
					<div id="documentation">
						<ul>
							<li><a href="formwizard_overview.html">Overview</a></li>
							<li><a href="formwizard_options_3.0.html">Options</a></li>
							<li><a href="formwizard_events_3.0.html">Events</a></li>
							<li><a href="formwizard_methods_3.0.html">Methods</a></li>
							<li><a href="formwizard_theming.html">Theming</a></li>
							<li><a href="formwizard_example.html">Examples</a></li>
							<li><a href="formwizard_download.html">Download</a></li>
						</ul>
					</div>
				</div>				
			</div>
			
			<div id="footer"><hr /><p>&copy; 2012 Jan Sundman </p></div>
		</div>
	</body>
</html>
