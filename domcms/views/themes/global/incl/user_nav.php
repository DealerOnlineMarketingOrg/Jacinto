<!-- Top navigation bar -->
<div id="topNav">
    <div class="fixed">
        <div class="wrapper">
        	<?php if(isset($avatar)): ?>
        	<div class="welcome">
            	<a href="<?php echo  base_url(); ?>user/profile"><img style="width:22px;" src="<?php echo  $avatar; ?>" alt="<?php echo  $user['FirstName'] . ' ' . $user['LastName']; ?>" /><span>Welcome, <?php echo  $user['FirstName']; ?></span></a>
            </div>
            <div class="clientInfo" id="clientInformation">
            	<span class="title"><?php echo  get_client_type(); ?></span><span><?php echo  get_client_name(); ?></span>
            </div>
            <div class="userNav">
                <ul>
                    <li><a href="<?php echo  base_url(); ?>logout"><img src="<?php echo  base_url(); ?>assets/themes/global/imgs/icons/topnav/logout.png" alt="" /><span>Logout</a></li>
                </ul>
            </div>
            <div class="dealerDropdowns">
                <div class="rowElem searchDrop" style="float:left;margin-right:5px;">
					<?php echo  dealer_selector(); ?>        
                    <!-- 
                    	<div class="dropRight searchDrop" style="float:right;">
                            <select class="chzn-select" tabindex="7">
                                <option>-- All --</option>
                                <option class="tag_green">Green Team</option>
                                <option class="tag_red">Red Team</option>
                                <option class="tag_blue">Blue Team</option>
                            </select>                      
                    	</div>
                    	<div class="fix"></div>
                    -->
            	</div>
            </div>
            <?php endif; ?>
            <div class="fix"></div>
        </div>
    </div>
</div>

<!-- if the user is idle for an hour, log them out -->
<script type="text/javascript">
	setInterval(function() {
		window.location.href="<?= base_url(); ?>logout";		
	},3600000);
</script>

