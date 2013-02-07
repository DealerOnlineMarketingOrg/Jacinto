<?php 
	$url = $_POST['url'];
	$google = file_get_contents($url);
	echo $google;
?>
<style type="text/css">
	div.type_masthead,div#yl_breadcrumb,div#yl_footer,div.mod_profile-rightbar,div.tabContainer,#yl_biz_links,#yl_biz_cta,#yl_edit_biz,#yl_reviews_sort,ul#yl_map_links,.yl-review-contents,#yl-btnviewall{display:none;}
	div.mod_profile-leftbar{width:100%;float:none;}
	div.users{margin-top:15px;}
	div.users div.title{display:none;}
	div#yl-btnviewall{display:none !important;}
	div#doc,div.yl-biz-cont2,div.yl-bd,#doc div.yl-hd{width:100% !important}
</style>
