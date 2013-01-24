
<!-- Content -->
<div class="content" id="container">
    <div class="title"><h5>Admin</h5></div>
    <!-- Form begins -->
    <?php
        $form = array(
            'name' => 'addClient',
            'id' => 'addClient',
            'class' => 'mainForm'
        );
        
        echo form_open('/admin/form_processor/clients/add',$form);
    ?>
    <form id="usualValidate" class="mainForm" method="post" action="">

        <!-- Input text fields -->
        <fieldset>
            <div class="widget first">
                <div class="head"><h5 class="iList">Add Client</h5></div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>First Name</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required','name'=>'firstname','id'=>'firstname')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Last Name</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required','name'=>'lastname','id'=>'lastname')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">                    
                    <label>Address</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'address','id'=>'address')); ?>
                    </div>
                    <div class="fix"></div>                    
                </div>
                <div class="rowElem noborder">
                    <label>City</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'city','id'=>'city')); ?>
                        
                    </div>
                    <div class="fix"></div>
                    
                </div>
                <div class="rowElem noborder">
                    <label>State</label>
                    <div class="formRight searchDrop">
                        <?= showStates(); ?>
                    </div>             
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Zip Code</label>
                    <div class="formRight">
                        <?= form_input(array('name'=>'zip','id'=>'zip')); ?>
                    </div>
                    <div class="fix"></div>
                    
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Phone Number</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'maskPhone required','name'=>'phone','id'=>'phone')); ?>
                        <span class="formNote">(999) 999-9999</span>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Notes</label>
                    <div class="formRight">
                        <?= form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes')); ?>
                    </div>
                    <div class="fix"></div>
                    
                </div>
                <div class="rowElem noborder">
                    <label><span class="req">*</span>Client Code</label>
                    <div class="formRight">
                        <?= form_input(array('class'=>'required','name'=>'code','id'=>'code')); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Member of<span class="req">*</span></label>
                    <div class="formRight">
                        <select name="select2" class="required" name="memberof">
                            <option value="opt1">Choose a level...</option>
                            <option value="DDI">DDI</option>
                            <option value="DOM">Dealer Online Marketing</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="rowElem noborder">
                    <label>Tags</label>
                    <div class="formRight">
                        <select name="select2" name="tags">
                            <option value="opt1">Choose a team...</option>
                            <option>Red Team</option>
                            <option>Blue Team</option>
                            <option>Green Team</option>
                            <option>Purple Team</option>
                        </select>
                    </div>
                    <div class="fix"></div>
                </div>
                <div class="submitForm"><input type="submit" value="submit" class="redBtn" /></div>
                <div class="fix"></div>
            </div>

        </fieldset>
    <?= form_close(); ?>       

</div>
<div class="fix"></div>