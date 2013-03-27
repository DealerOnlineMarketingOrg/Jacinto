<!-- Header -->
<?php
	$level = $this->session->userdata('valid_user');
	$level = $level['DropdownDefault']->LevelType;
?>
<div id="header" class="wrapper">
    <div class="logo"><a href="/" title=""><img src="<?php echo  base_url(); ?>assets/themes/global/imgs/loginLogo.png"  alt="Dealer Online Marketing" /></a></div>
    <div id="system_tags">
		<fieldset>
			<div class="widget" style="border:none;">
            	<div class="rowElem noborder" style="padding:0;border:0 none #fff !important;"><?php echo  $tagHtml; ?></div>
            </div>
        </fieldset>
    </div>
    <div class="middleNav">
    	<ul>
        	<li class="iFolder" id="changeCount"><a href="<?= base_url(); ?>beta" title=""><span>Changes</span></a></li>
            <?php if($level == 'a' OR $level == 1) { ?><li class="iList"><a href="<?= base_url(); ?>masterlist" title=""><span>Master List</span></a></li><?php } ?>
            <?php if($level == 'c' OR $level == 3) { ?><li class="iLocked2"><a  href="#" title=""><span>Passwords</span></a></li><?php } ?>
            <li class="iUser"><a href="<?php echo  base_url(); ?>contacts" title=""><span>Contacts</span></a></li>
        </ul>
    </div>
    <div class="fix"></div>
</div>
<div class="wrapper">

<script type="text/javascript">
	var changeCount = 0;
	changeCount =+ '<?= getLiveChangesCount(); ?>';
	if(changeCount > 0) {
		var hasSpan = jQuery('#changeCount span.numberMiddle').length;
		if(hasSpan) {
			jQuery('#changeCount span.numberMiddle').text(changeCount);	
		}else {
			jQuery('#changeCount').append('<span class="numberMiddle">' + changeCount + '</span>');		
		}
	}

	setInterval(function() {
		liveCount();
	},500);
	
	function liveCount() {
		var count = 0;
		count =+ '<?= getLiveChangesCount(); ?>';
		if(count > 0) {
			var hasSpan = jQuery('#changeCount span.numberMiddle').length;
			if(hasSpan > 0) {
				jQuery("#changeCount span.numberMiddle").html(count);	
			}else {
				jQuery("#changeCount").append('<span class="numberMiddle">' + count + '</span>');		
			}
		}
	}
</script>