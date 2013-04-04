	<?php if(isset($clients)) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/client_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Client_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Client_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Client_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Client_List',$userPermissionLevel);
    ?>
    <?php if($addPriv) { ?><a href="javascript:addClient();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Client</a><?php } ?>
    <?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center;">Tag</th>
                    <th style="text-align:left;width:50px;">Code</th>
                    <th style="text-align:left;width:30%;">Dealership Name</th>
                    <th>Group</th>
                    <th>Status</th>
                    <?php if($editPriv) { ?>
                    <th class="actions">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $client) { ?>
                    <tr class="tagElement <?= $client->ClassName; ?>">
                    	<td class="tags"><div class="<?= $client->ClassName; ?>">&nbsp;</div></td>
                        <td><?= $client->ClientCode; ?></td>
                        <td style="text-align:left;"><?= $client->Name; ?></td>
                        <td><?= $client->GroupName; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($client->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol" style="width:75px;text-align:center;">
                            <a title="Edit Client" href="javascript:editClient('<?= $client->ClientID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Client" href="javascript:viewClient('<?= $client->ClientID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addClient();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Client</a><?php } ?>
    <?php else : ?>
    <div class="nNote nFailure"><p><strong>Error:</strong> No clients found.</p></div>
    <?php endif; ?>
