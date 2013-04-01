<div id="loader_block">
	<div id="client_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
</div>
<div class="content">
    <div class="title"><h5>Clients</h5></div>
    <?php notifyError(); ?>
    <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    <div class="table" id="dataClient"></div>
    <div class="fix"></div>
</div>
<style type="text/css">
    div.dataTables_filter{top:-72px;}
    div#loader_block{position:fixed;width:100%;height:100%;top:0;left:0;background:#fff;opacity:0.8;z-index:2000;display:none;}
    div#client_loader{position:absolute;width:16px;height:16px;top:50%;margin-top:-8px;left:50%;margin-left:-8px;}
    div#dataClient{margin-top:1px;display:none;}
</style>
<div id="addClientsForm"></div>
<script type="text/javascript">
  jQuery('#loader_block').slideDown('fast',function() {
    jQuery.ajax({
      type:"GET",
      url:'/admin/clients/load_table',
      success:function(data) {
        if(data) {
          jQuery('#dataClient').html(data);
          jQuery('#loader_block').slideUp('fast',function() {
            jQuery('#dataClient').slideDown('fast');
          });
        }else {
          jQuery('#dataClient').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
        }
      }
    });
  });
 </script>