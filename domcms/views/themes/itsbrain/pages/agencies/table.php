<?php if(isset($agencies)) : ?>
<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/agency_popups.js"></script>
<?php 
	$userPermissionLevel = $this->user['AccessLevel'];
	$addPriv     		 = GateKeeper('Agency_Add',$userPermissionLevel);
	$editPriv    		 = GateKeeper('Agency_Edit',$userPermissionLevel);
	$disablePriv 		 = GateKeeper('Agency_Disable_Enable',$userPermissionLevel);
	$listingPriv 		 = GateKeeper('Agency_List',$userPermissionLevel);
?>
<?php if($addPriv) { ?><a href="javascript:addAgency();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Agency</a><?php } ?>
<?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:30%;text-align:left;">Name</th>
                    <th style="text-align:left;">Description</th>
                    <th>Status</th>
                    <?php if($editPriv) { ?>
                    <th>Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($agencies as $agency) { ?>
                    <tr>
                        <td style="text-align:left;"><?= $agency->Name; ?></td>
                        <td><?= $agency->Description; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($agency->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol" style="width:75px;text-align:center;">
                            <a title="Edit Agency" href="javascript:editAgency('<?= $agency->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
<?php } ?>
<?php if($addPriv) { ?><a href="javascript:addAgency();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Agency</a><?php } ?>
<?php else : ?>
<div class="nNote nFailure"><p><strong>Error:</strong> No agencies found.</p></div>
<?php endif; ?>
