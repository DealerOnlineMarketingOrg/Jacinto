<div class="uDialog" id="GoogleConnect">
    <div id="gconnect" class="dialog-message" title="Connect With Google">
        <p><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/google.jpg" style="float:left;margin:0 5px; width:50px;"  alt="Connect with google" />Give us your google username below.</p>
        <div class="uiForm">
            <?php 
                echo form_open(base_url(), array('id' => 'googleConnect','class'=>'valid')); 
                echo form_input(array('id' => 'openID','name'=>'id','placeHolder'=>'Your Google username','class'=>'validate[required]','style'=>'margin-top:5px;')); 
                echo form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	jQuery('#googleConnect').validationEngine({promptPosition : "right", scroll: true});
	
	jQuery('#googleConnect').submit(function(e) {
		e.preventDefault();
	});

	jQuery("#gconnect").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Submit: function() {
				Authenticate();
			}
		}
	});

	function Authenticate() {
		jQuery.ajax({
			type:'POST',
			url:'<?= base_url(); ?>auth/openid/google_authenticate',
			data:{email:$('#openID').val()},
			success:function(data) {
				alert(data);
			}
		});
	}
	
</script>
