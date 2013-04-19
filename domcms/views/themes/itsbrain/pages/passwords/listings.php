    <div class="content">
    	<div class="title"><h5><?php echo  $page_id; ?></div>
        <?php notifyError(); ?>
        <?php include 'domcms/views/themes/global/breadcrumb.php'; ?>
    	<div class="table" style="margin-top:1px;">
            <?php echo  $page_html; ?>
            </div>
         </div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
    <style type="text/css">
		div.dataTables_filter{top:-72px;}
	</style>
	<?php if (isset($page_scripts)) {
		echo '<script type="text/javascript">';
		// These are general page scripts for the current view.
		echo $page_scripts;
		echo '</script>';
		}
	?>