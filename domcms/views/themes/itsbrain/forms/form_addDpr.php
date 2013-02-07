<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?= $html; ?>
    <!-- Form begins -->
    <?= form_open('',array('name'=>'addDpr','class'=>'mainForm','id'=>'usualValidate')); ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Add DPR Report</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Provider</label>
                    <div class="formRight">
                        <select class="validate[required]" name="providers" placeholder="Select A Lead Provider...">
                        	<option value="">Select A Lead Provider</option>
                            <option value="1">Dealer.com</option>
                            <option value="2">Cars.com</option>
                            <option value="3">AutoTrader.com</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Agency Description</label>
                    <div class="formRight">
                        <select class="validate[required]" name="type" placeholder="Select A Lead Type...">
                            <option value="">Select A Lead Type</option>
                            <option value="1">Website Unique Visitors</option>
                            <option value="2">Phone Leads</option>
                            <option value="3">Web Leads</option>
                            <option value="4">Kelly Blue Book Leads</option>
                            <option value="5">Combined</option>
                            <option value="6">Leads</option>
                            <option value="7">Dealer Click-Throughs</option>
                            <option value="8">Printable Ads Requested</option>
                            <option value="9">Views Maps to Dealership</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Date</label>
                    <div class="formRight onlyAlpha">
                    	<input type="text" class="validate[required] datepicker" name="date" placeholder="" max-length="12" />
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Total</label>
                    <div class="formRight">
                    	<input type="text" class="validate[required]" name="total" placeholder="The total lead count" max-length="10" />
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="submitForm">
                	<input type="reset" value="Reset" class="basicBtn" />
                    <input type="submit" value="submit" class="redBtn" />
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    <?= form_close(); ?>        
    <script type="text/javascript">
	$( ".datepicker" ).datepicker({ // onClick date picker
		defaultDate: +7,
		autoSize: false,
		appendText: '(yyyy-mm-dd)',
		dateFormat: 'yy-mm-dd',
	});
	</script>
</div>
<div class="fix"></div>