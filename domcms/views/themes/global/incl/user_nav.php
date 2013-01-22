<!-- Top navigation bar -->
<div id="topNav">
    <div class="fixed">
        <div class="wrapper">
        	<?php if(isset($avatar)): ?>
        	<div class="welcome">
            	<a href="<?= base_url(); ?>user/profile"><img style="width:22px;" src="<?= $avatar; ?>" alt="<?= $user['FirstName'] . ' ' . $user['LastName']; ?>" /><span>Welcome, <?= $user['FirstName']; ?></span></a>
            </div>
            <div class="clientInfo" id="clientInformation">
            	<span class="title"><?= get_client_type(); ?></span><span><?= get_client_name(); ?></span>
            </div>
            <div class="userNav">
                <ul>
                    <li><a href="#" title=""><img src="<?= base_url(); ?>assets/icons/topnav/profile.png" alt="" /><span>Profile</span></a></li>
                    <li><a href="#" title=""><img src="<?= base_url(); ?>assets/icons/topnav/settings.png" alt="" /><span>Settings</span></a></li>
                    <li><a href="<?= base_url(); ?>logout"><img src="<?= base_url(); ?>assets/icons/topnav/logout.png" alt="" /><span>Logout</a></li>
                </ul>
            </div>
            <div class="dealerDropdowns">
                <div class="rowElem">
                    <div class="dropLeft searchDrop" style="float:left;margin-right:5px;">
                        <?= dealer_selector(); ?>        
                        <script type="text/javascript">
                            $("select#client_dd>option").each( function(){
                                var $option = $(this);  
                                if($(this).prev().attr('data-level') == $option.attr('data-level')) {
                                    if(!$(this).hasClass('agency')) {
                                        $(this).prev().remove();
                                        $(this).removeClass('double-indent').addClass('single-indent');
                                    }
                                }
                            })
                        </script>    
                    </div>
                    <div class="dropRight searchDrop" style="float:right;">
                        <select class="chzn-select" tabindex="7">
                            <option>-- All --</option>
                            <option class="tag_green">Green Team</option>
                            <option class="tag_red">Red Team</option>
                            <option class="tag_blue">Blue Team</option>
                        </select>                      
                    </div>
                    <div class="fix"></div>
                </div>
            </div>
            <?php endif; ?>
            <div class="fix"></div>
        </div>
    </div>
</div>

