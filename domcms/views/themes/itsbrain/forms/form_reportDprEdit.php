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
		
        echo form_open('/dpr/reports',$form);
    ?>
	        <!-- Input text fields -->
        <fieldset>
        	<div class="widget first" style="padding:5px">
               <?php if ($this->user['DropdownDefault']->SelectedClient == 0) { ?>
					<div class="head"><h5 class="iList">Add DPR Lead: <span style="color:red">No dealer is selected. Please select a dealer to input DPR leads.</span></h5></div>
                
				<?php } else { ?>
                    <div class="head"><h5 class="iList">Add DPR Lead</h5></div>
                    <div id="reportEntry">
                    	<div style="position:relative;float:left;margin:5px">
                            <label>Starting Month</label>
							<div class="fix"></div>
                            <label style="margin-top:5px">Starting Year</label>
                            <div class="fix"></div>
                            <label style="margin-top:5px">Ending Month</label>
                            <div class="fix"></div>
                            <label style="margin-top:5px">Ending Year</label>
                        </div>
                    	<div class="noSearch" style="position:relative;float:left;margin:5px">
                            <select id="startMonth" name="startMonth" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a month...">
                                <option value="">Select a Month</option>
                                <option value="1">January</option>
                                <option value="2">Febuary</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <div class="fix"></div>
                            <select id="startYear" name="startYear" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a year...">
                                <option value="">Select a Year</option>
                                <option value="2013">2013</option>
                                <option value="2012">2012</option>
                                <option value="2011">2011</option>
                                <option value="2010">2010</option>
                                <option value="2009">2009</option>
                            </select>
                            <div class="fix"></div>
                            <select id="endMonth" name="endMonth" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a month...">
                                <option value="">Select a Month</option>
                                <option value="1">January</option>
                                <option value="2">Febuary</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <div class="fix"></div>
                            <select id="endYear" name="endYear" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a year...">
                                <option value="">Select a Year</option>
                                <option value="2013">2013</option>
                                <option value="2012">2012</option>
                                <option value="2011">2011</option>
                                <option value="2010">2010</option>
                                <option value="2009">2009</option>
                            </select>
                    	</div>
						<div class="fix"></div>
                    </div>
                <?php } ?>
            </div>
            <div class="submitForm">
            	<input type="submit" value="submit" class="redBtn" />
            </div>
        </fieldset>
    <?php echo  form_close(); ?>
    
    <script type="text/javascript">
		
	</script>
</div>
<div class="fix"></div>