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
	                        <?php
								// Locate primary.
								foreach ($contact->Phone as $type => $phone) {
									if ($phone == $contact->PrimaryPhoneType) {
										echo '<span class="red"><strong>Direct:</strong></span><br />'.$phone;
										break;
									}
								}
                                // Locate others.
                                foreach ($contact->Phone as $type => $phone) {
                                    if ($phone != $contact->PrimaryPhoneType) {
										echo '<br /><span class="red"><strong>'.$type.':</strong></span><br />'.$phone;
                                    }
                                }
							?>
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