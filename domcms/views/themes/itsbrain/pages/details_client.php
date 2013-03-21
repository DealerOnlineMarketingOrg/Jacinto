    <div class="content hideTagFilter">
    	<div class="title">
        	<h5>Contact Information</h5>
        </div>
        <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    	<div class="widget" style="margin-top:5px;">
        	<div class="head">
    			<h5 class="iUser"><?php echo  $display->Name; ?></h5>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
            	<tbody>
                    <tr>
                    	<td width="10%">Name:</td>
                        <td><?php echo  $display->Name; ?></td>
                    </tr>
                    <?php if(isset($display->Address)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Address:</td>
                        <td><?php echo  $display->Address['street'] . ' <br />' . $display->Address['city'] . ', ' . $display->Address['state'] . ' ' . $display->Address['zipcode']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(isset($display->Phone)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Phone:</td>
                        <td>
                        	<span class="red"><strong>Direct:</strong></span><br /><?php echo  $display->Phone['main']; ?>
                            <?php if(isset($display->Phone['home'])) { ?><br /><span class="red"><strong>Personal:</strong></span><br /><?php echo  $display->Phone['home']; ?><?php } ?>
                            <?php if(isset($display->Phone['fax'])) { ?><br /><span class="red"><strong>Fax:</strong></span><br /><?php echo  $display->Phone['fax']; ?><?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if(isset($display->Notes)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Notes:</td>
                        <td><p><?php echo  $display->Notes; ?></p></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>