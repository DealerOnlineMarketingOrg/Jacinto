
<!-- Content -->
<div class="content hideTagFilter" id="container">
    <div class="title"><h5>Admin</h5></div>
    <?php notifyError(); ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    <?php
        $form = array(
            'name' => 'addClient',
            'id' => 'valid',
            'class' => 'mainForm'
        );

        echo form_open('/admin/clients/form_processor/clients/edit',$form);
    ?>
        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Edit Client</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Client Name</label>
                    <div class="formRight">
                        <?php echo  form_input(array('class'=>'required validate[required]','name'=>'ClientName','id'=>'name','value'=>$client->Name)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Address</label>
                    <div class="formRight">
                        <?php echo  form_input(array('name'=>'street','id'=>'address','value'=> ((isset($client->Address['street'])) ? $client->Address['street'] : ''))); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>City</label>
                    <div class="formRight">
                        <?php echo  form_input(array('name'=>'city','id'=>'city','value' => ((isset($client->Address['city'])) ? $client->Address['city'] : ''))); ?>

                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?php echo  showStates(((isset($client->Address['state'])) ? $client->Address['state'] : false)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Zip Code</label>
                    <div class="formRight">
                        <?php echo  form_input(array('name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : ''))); ?>
                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Phone Number</label>
                    <div class="formRight">
                        <?php echo  form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value' => ((isset($client->Phone['main'])) ? $client->Phone['main'] : ''))); ?>
                        <span class="formNote">(999) 999-9999</span>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Notes</label>
                    <div class="formRight">
                        <?php echo  form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>$client->Description)); ?>
                    </div>
                    <div class="fix"></div>

                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Client Code</label>
                    <div class="formRight">
                        <?php echo  form_input(array('class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>$client->Code)); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                	<label>Google Review</label>
                    <div class="formRight">
                    	<?php echo  form_input(array('class'=>'validate[custum[url]]','name'=>'GoogleReviewURL','id'=>'GoogleReview','value'=>$client->Reviews['Google'])); ?>
                        <?php echo  form_hidden('GoogleID',($client->Reviews['GoogleID']) ? $client->Reviews['GoogleID'] : 0); ?>
                        <p class="formNote">The Web Address for the clients Google Review Page</p>
                    </div>
                    <div class="fix"></div>
                </div>
                
                <div class="rowElem noborder">
                	<label>Yelp Review</label>
                    <div class="formRight">
                    	<?php echo  form_input(array('class'=>'validate[custom[url]]','name'=>'YelpReviewURL','id'=>'YelpReview','value'=>$client->Reviews['Yelp'])); ?>
                        <?php echo  form_hidden('YelpID',($client->Reviews['YelpID']) ? $client->Reviews['YelpID'] : 0); ?>
                        <p class="formNote">The Web Address for the clients Yelp Review Page</p>
                    </div>
                    <div class="fix"></div>
                </div>
                
                <div class="rowElem noborder">
                	<label>Yahoo Review</label>
                    <div class="formRight">
                    	<?php echo  form_input(array('class'=>'validate[custom[url]]','name'=>'YahooReviewURL','id'=>'YahooReview','value'=>$client->Reviews['Yahoo'])); ?>
                        <?php echo  form_hidden('YahooID',($client->Reviews['YahooID']) ? $client->Reviews['YahooID'] : 0); ?>
                        <p class="formNote">The Web Address for the clients Yahoo Review Page</p>
                    </div>
                </div>
                
                <div class="rowElem noborder">
                    <label><span class="req">*</span> Tags</label>
                    <div class="formRight searchDrop">
                        <select style="width:200px;" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="9">
                            <option value=""></option>
                            <?php foreach($tags as $tag) : ?>
                            	<option <?php echo  (($tag->ID == $client->Tag) ? 'selected="selected"' : ''); ?> value="<?php echo  $tag->ID; ?>"><?php echo  $tag->Name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Enable/Disable</label>
                    <div class="formRight">
                        <div class="radio" id="uniform-radio1">
                            <input 
                            	type="radio" 
                                id="radio1" 
                                name="Status" 
                                value="1" <?php echo  (($client->Status != 0) ? 'checked="checked"' : ''); ?> 
                                style="opacity:0;" />
                        </div>
                        <label for="radio1">Enabled</label>

                        <div class="radio" id="uniform-radio2">
                            <input 
                            	type="radio" 
                                id="radio2" 
                                name="Status" 
                                value="0" <?php echo  (($client->Status != 1) ? 'checked="checked"' : ''); ?> 
                                style="opacity:0;" />
                        </div>
                        <label for="radio2">Disabled</label>
                        <div class="fix"></div>
                    </div>
                    <div class="fix"></div>
                </div>
                
                
                <div class="submitForm">
               		<input type="hidden" name="ClientID" value="<?php echo  $client->ClientID; ?>" />
                    <input type="submit" value="submit" class="redBtn" />
                    
                </div>
                <div class="fix"></div>
            </div>

        </fieldset>
    <?php echo  form_close(); ?>
    
</div>
<div class="fix"></div>