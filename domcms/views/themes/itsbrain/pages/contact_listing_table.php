<?php if(isset($contacts)) : ?>
	<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/contact_popups.js"></script>
    <?php 
        $ci =& get_instance();
        $userPermissionLevel = $ci->user['AccessLevel'];
        $addPriv     		 = GateKeeper('Contact_Add',$userPermissionLevel);
        $editPriv    		 = GateKeeper('Contact_Edit',$userPermissionLevel);
        $disablePriv 		 = GateKeeper('Contact_Disable_Enable',$userPermissionLevel);
        $listingPriv 		 = GateKeeper('Contact_List',$userPermissionLevel);
    ?>
	<?php if($addPriv) { ?><a href="javascript:addContact();" class="greenBtn floatRight button" style="margin-top:-73px;margin-right:3px;">Add New Contact</a><?php } ?>
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
                <?php foreach($contacts as $contact) { ?>
                    <tr>
                        <td style="text-align:left;"><?= $contact->Name; ?></td>
                        <td><?= $contact->AgencyName; ?></td>
                        <td style="width:30px;text-align:center;"><?= (($contact->Status) ? 'Active' : 'Disable'); ?></td>
                        <?php if($editPriv) { ?>
                        <td class="actionsCol" style="width:75px;text-align:center;">
                            <a title="Edit Contact" href="javascript:editContact('<?= $contact->ContactId; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                            <a title="View Contact" href="javascript:viewContact('<?= $contact->ContactId; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/cards-address.png" alt="" /></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <?php if($addPriv) { ?><a href="javascript:addContact();" class="greenBtn floatRight button" style="margin-top:10px;">Add New Contact</a><?php } ?>
<?php else : ?>
    <div class="nNote nFailure"><p><strong>Error:</strong> No contacts found.</p></div>
<?php endif; ?>