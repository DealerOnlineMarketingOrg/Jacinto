<!-- Content -->
    <div class="content hideTagFilter">
    	<div class="title"><h5>Yelp Reviews</h5></div>
        <?php if($url) : ?>
            <div id="review" style="display:none;"></div>
            <div id="loader" style="text-align:center;display:none;"><img class="p12" src="<?= base_url(); ?>assets/themes/itsbrain/imgs/loaders/loader8.gif" /></div>
        <? else : ?>
                	<div class="nNote nWarning">

        	<p><strong>Warning:</strong>No Yelp reviews found for this client.</p>
            </div>
        <? endif; ?>
        <script type="text/javascript">
			$('#loader').fadeIn('fast');
			$.ajax({
				url:'<?= base_url(); ?>scrape/yelpReviews.php',
				type:'POST',
				data:{url:'<?= $url; ?>'},
				success:function(result) {
					//alert(result);
					$('#loader').fadeOut('fast',function() {
						$('#review').append(result);
						//$('#review').append(review);
					});
						
				}
			});
			window.setTimeout(doStuff,3000,true);
			function doStuff() {
				jQuery('#review').fadeIn('fast');
				jQuery('div.content title').remove();
				jQuery('div.content meta').remove();	
				jQuery('div.content style').remove();
				jQuery('div.content base').remove();
				jQuery('div.content iframe').remove();
				jQuery('div.content noscript').remove();
				jQuery('div.content script').remove();
				
				jQuery('div.content #review a').each(function() {
					var href = jQuery(this).attr('href');
					var href = jQuery(this).attr('href');
					jQuery(this).attr('href', 'http://yelp.com/' + href);
					jQuery(this).attr('target','_blank');
				});		
			}
		</script>
        <div class="fix"></div>
    </div>
<style type="text/css">
	div#wrap ul.offscreen,div#wrap ul#header-account,div#mastHead,div#side,div#footer{display:none;}
	div#wrap,div#main{width:100%;float:none;}
	div.users{margin-top:15px;}
	div.users div.title{display:none;}
	div#yl-btnviewall{display:none !important;}
	div#doc,div.yl-biz-cont2,div.yl-bd,#doc div.yl-hd{width:100% !important}
</style>
