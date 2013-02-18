    <div class="content hideTagFilter">
    	<div class="title">
        	<h5>Contact Information</h5>
        </div>
        <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    	<div class="widget" style="margin-top:5px;">
        	<div class="head">
    			<h5 class="iUser"><?= $display->Name; ?></h5>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
            	<tbody>
                	<tr class="noborder">
                    	<td width="10%">Title:</td>
                        <td><?= $display->JobTitle; ?></td>
                    </tr>
                    <tr>
                    	<td width="10%">
                    </tr>
                    <tr>
                    	<td width="10%">Parent Client:</td>
                        <td><?= $display->Dealership; ?></td>
                    </td>
                    <tr>
                    	<td width="10%">Name:</td>
                        <td><?= $display->Name; ?></td>
                    </tr>
                    <tr>
                      	<td width="10%" style="vertical-align:top;">Emails:</td>
                        <td>
                        	<span class="red"><strong>Personal:</strong></span><br /><a href="mailto:<?= $display->Email['home']; ?>"><?= $display->Email['home']; ?></a><br />
                        	<?php if(isset($display->Email['work'])) { ?><span class="red"><strong>Work:</strong></span><br /><a href="mailto:<?= $display->Email['work']; ?>"><?= $display->Email['work']; ?></a><?php } ?>
                        </td>
                    </td>
                    <?php if(isset($display->Address)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Address:</td>
                        <td><?= $display->Address['street'] . ' <br />' . $display->Address['city'] . ', ' . $display->Address['state'] . ' ' . $display->Address['zipcode']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(isset($display->Phone)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Phone:</td>
                        <td>
                        	<span class="red"><strong>Direct:</strong></span><br /><?= $display->Phone['main']; ?>
                            <?php if(isset($display->Phone['home'])) { ?><br /><span class="red"><strong>Personal:</strong></span><br /><?= $display->Phone['home']; ?><?php } ?>
                            <?php if(isset($display->Phone['fax'])) { ?><br /><span class="red"><strong>Fax:</strong></span><br /><?= $display->Phone['fax']; ?><?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if(isset($display->Notes)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Notes:</td>
                        <td><p><?= $display->Notes; ?></p></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>