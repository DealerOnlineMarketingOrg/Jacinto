<div class="content">
    	<div class="title"><h5>Yahoo Reviews</h5></div>

<?php 
	$yahoopage = file_get_contents('http://local.yahoo.com/info-12669458-bill-page-honda-falls-church');
	echo $yahoopage;
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript">
	$('div.type_masthead,div#yl_breadcrumb,div#yl_footer,div.mod_profile-rightbar,div.tabContainer,#yl_biz_links,#yl_biz_cta,#yl_edit_biz,#yl_reviews_sort,ul#yl_map_links,.yl-review-contents,#yl-btnviewall').remove();
	$('div.mod_profile-leftbar').css({'width':'100%','float':'none'});
	$('div.users').css({'marginTop':'15px'});
	$('ul#attrs-ul a.trackLink').replaceWith(function() { return $(this).contents();});
	$('title').remove();
	$('meta').remove();
	$('body script').remove();
	$('html').replaceWith('<div id="html">' + $('html').html() + '</div>');
	$('body').replaceWith('<div id="body">' + $('body').html() + '</div>');
	
</script>
<div class="fix"></div>
</div>