<div id="loader_block">
	<div id="client_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
</div>
<div class="content">
    <div class="title"><h5>Clients</h5></div>
    <?php notifyError(); ?>
    <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    <div class="table" id="dataClient">
        <div class="head" style="margin-top:5px;"><h5 class="iView">View All Clients</h5></div>
        <div id="clientTableHolder">
			<?= ClientsListingTable(); ?>
        </div>
    </div>
    <div class="fix"></div>
</div>
<style type="text/css">
    div#loader_block{position:fixed;width:100%;height:100%;top:0;left:0;background:#fff;opacity:0.8;z-index:2000;display:none;}
    div#client_loader{position:absolute;width:16px;height:16px;top:50%;margin-top:-8px;left:50%;margin-left:-8px;}
    div#dataClient{margin-top:1px;}
</style>
<div id="editClientsForm"></div> 
<div id="addWebsiteForm"></div>
<div id="editContactPop"></div>
<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/client_popups.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/websites_popups.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/themes/itsbrain/js/contactInfo_popups.js"></script>
<script type="text/javascript"></script>