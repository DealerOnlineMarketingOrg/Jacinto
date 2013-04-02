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
<table cellpadding="0" cellspacing="0" border="0" class="tableStatic" width="100%;">
    <thead>
        <tr>
            <td>Name</td>
            <td>Description</td>
            <td>Status</td>
            <?php if($editPriv) { ?>
            <td>Actions</td>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($agencies as $agency) { ?>
            <tr>
                <td><?= $agency->Name; ?></td>
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
