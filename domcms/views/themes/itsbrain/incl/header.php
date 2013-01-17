<!-- Header -->
<div id="header" class="wrapper">
    <div class="logo"><a href="/" title=""><img src="<?= base_url(); ?>assets/themes/global/imgs/loginLogo.png" style="width:60%;" alt="" /></a></div>
    <div class="middleNav">
    	<ul>
        	<li class="iMes"><a href="#" title=""><span>Support tickets</span></a><span class="numberMiddle">9</span></li>
            <li class="iStat"><a href="#" title=""><span>Statistics</span></a></li>
            <li class="iUser"><a href="<?= base_url(); ?>admin/users" title=""><span>User list</span></a></li>
            <li class="iOrders"><a href="#" title=""><span>Billing panel</span></a></li>
        </ul>
    </div>
    <div class="fix"></div>
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
                    <option>Denver Broncos</option>
                    <option>Kansas City Chiefs</option>
                    <option>Oakland Raiders</option>
                    <option>San Diego Chargers</option>
                </select>                      
            </div>
            <div class="fix"></div>
        </div>
    </div>
</div>
<div class="wrapper">
	