	<?php if(isset($groups)) : ?>
    <script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/group_popups.js"></script>
    <?php 
		$ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Group_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Group_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Group_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Group_List',$userPermissionLevel);
    ?>
    <?php if($addPriv) { ?><a href="javascript:addGroup();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Group</a><?php } ?>
    <?php if($listingPriv) { ?>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%;">
            <thead>
                <tr>
                    <th style="width:30%;text-align:left;">Name</th>
                    <th style="text-align:left;">Member Of</th>
                    <th class="status">Status</th>
                    <?php if($editPriv) { ?>
                    <th class="actions">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($groups as $group) { ?>
                    <tr>
                        <td style="text-align:left;"><?= $group->Name; ?></td>
                        <td><?= $group->AgencyName; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($group->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol" style="width:75px;text-align:center;">
                            <a title="Edit Group" href="javascript:editGroup('<?= $group->GroupId; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Group" href="javascript:viewGroup('<?= $group->GroupId; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addGroup();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Group</a><?php } ?>
    <?php else : ?>
    <div class="nNote nFailure"><p><strong>Error:</strong> No groups found.</p></div>
    <?php endif; ?>
