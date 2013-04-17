<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editUser" title="User Information">
        <div class="uiForm">
			<style type="text/css">
				#editUser label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editUser .chzn-container,textarea{margin-top:12px;}
				div.tab_content div.title {border:1px solid #d5d5d5;padding:5px;margin-bottom:5px;background:url('<?= base_url(); ?>assets/themes/itsbrain/imgs/leftNavBg.png') repeat-x scroll 0 0 transparent;}
				div.tab_content div.title h5{padding-left:30px;margin-top:3px;}
				div.tab_content div.profileRight{margin-left:125px;}
				div.tab_content img.profileAvatar{float:left;border:1px solid #d5d5d5;}
				div.tab_content table.profile{margin-right:10px;border:1px solid #d5d5d5;margin-bottom:5px;}
				div.tab_content table.profile td.icon{text-align:center;width:20px;vertical-align:middle;border-right:none;}
				div.tab_content table.profile td.info{vertical-align:middle;}
				div.tab_content table.profile td.icon img {float:left;margin:7px;}
				div.tab_content table.profile td.info span {float:left;margin-right:5px;font-weight:bold;margin-left:-35px;}
				div.tab_content table.profile td a {color:#2B6893;}
				div.tab_content table.profile td a:hover{color:#666;}
				div#userInfo a.actions_link{float:right;margin-top:-19px;margin-right:3px;}
				div.password_buttons{text-align:right;margin-top:10px;}
				div.password_buttons a {color:#fff;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="userInfo">User Details</a></li>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <li><a href="javascript:void(0);" rel="contacts">Contact Info</a></li>
                    <li><a href="javascript:void(0);" rel="modules">Modules</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="userInfo" class="tab_content">
                    	<div class="title">
                        	<h5 class="iUsers2"><?= $user->FirstName . ' ' . $user->LastName; ?></h5>
                            <?php if(($this->user['AccessLevel'] >= 600000 || $this->user['UserID'] == $user->ID) AND !isset($view)) { ?>
                            	<a title="Edit Contact" href="javascript:editUserInfo('<?= $user->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <?php } ?>
                        </div>
                        <img class="profileAvatar" src="<?= $avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" style="width:120px;" />
                        <div class="profileRight">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="profile">
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/user.png" alt="" /></td>
                                    <td class="info"><span>Username:</span> <a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></td>
                                </tr>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/building.png" alt="" /></td>
                                    <td class="info"><span>Company:</span> <?= $user->Dealership; ?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/home.png" alt="" /></td>
                                    <td class="info"><span>Address:</span> <?= $user->Address['street'] . ' ' . $user->Address['city'] . ', ' . $user->Address['state'] . ' ' . $user->Address['zipcode']; ?></td> 
                                </tr>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/locked2.png" alt="" /></td>
                                    <td class="info"><span>Security:</span> <?= $user->AccessName; ?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/dayCalendar.png" alt="" /></td>
                                    <td class="info"><span>Member Since:</span> <?= date('m/d/Y',strtotime($user->JoinDate)); ?></td>
                                </td>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/mail.png" alt="" /></td>
                                    <td class="info">
										<?php
                                            // Locate primary.
                                            foreach ($user->Emails as $type => $email) {
                                                if ($email == $user->PrimaryEmailType) {
                                                    echo '<span>Primary Email:</span><a href="mailto:'.$email.'">'.$email.'</a>';
                                                    break;
                                                }
                                            }
                                        ?>
                                     </td>
                                </td>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>assets/themes/itsbrain/imgs/icons/dark/phone.png" alt="" /></td>
                                    <td class="info">
                                    	<?php
                                            // Locate primary.
                                            foreach ($user->Phones as $type => $phone) {
                                                if ($phone == $user->PrimaryPhoneType) {
                                                    echo '<span>Primary Phone:</span><a href="mailto:'.$phone.'">'.$phone.'</a>';
                                                    break;
                                                }
                                            }
                                        ?>
                                	</td>
                                </td>
                            </table>
                            <div class="fix"></div>
                        </div>
                        <?php if((!isset($view)) AND ($this->user['AccessLevel'] >= 600000 || $user->ID == $this->user['UserID'])) { ?>
                            <div class="password_buttons">
                            	<a href="javascript:changeMyPass('<?= $user->ID; ?>')" class="greenBtn button">Change Password</a>
                                <?php if(($this->user['AccessLevel'] >= 600000) AND ($user->ID != $this->user['UserID'])) { ?>
                                	<a href="javascript:resetMyPass('<?= $user->ID; ?>')" class="blueBtn button">Reset Password</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
    				</div>
    				<div id="websites" class="tab_content" style="display:none;">
    				</div>
                    <div id="contacts" class="tab_content" style="display:none;">
                    </div>
                    <div id="modules" class="tab_content" style="display:none;">
                    
                    </div>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;
	
	<?php if(isset($view)) { ?>
	
	<?php }else { ?>
		$('#tagChanger').change(function() {
			var ele = $(this).find('option:selected');
			var classname = ele.attr('rel');
			$('#tagThumb').attr('class',classname);
		});
	<?php } ?>

	$.mask.definitions['~'] = "[+-]";
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	$(".maskIntPhone").mask("+33 999 999 999");
	$(".maskTin").mask("99-9999999");
	$(".maskSsn").mask("999-99-9999");
	$(".maskProd").mask("a*-999-a999", { placeholder: " " });
	$(".maskEye").mask("~9.99 ~9.99 999");
	$(".maskPo").mask("PO: aaa-999-***");
	$(".maskPct").mask("99%");

	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});
	
	$('form.editClient,form.addClient').submit(function(e) {
		e.preventDefault();
		<?php if(isset($view)) { ?>
		<?php }else { ?>
			var formData = $(this).serialize();
			$.ajax({
				type:'POST',
				data:formData,
				url:'',
				success:function(resp) {
				}
			});
		<?php } ?>
	});

	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editUser div.tab_container div.tab_content').hide();
		$('#editUser div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		<?php if(isset($view)) { ?>
		
		<?php }else { ?>
		
		<?php } ?>
		
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	$(".chzn-select").chosen();
	$("#editUser").dialog({
		minWidth:300,
		width:875,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close')}
			},
		] 
	});
</script>
