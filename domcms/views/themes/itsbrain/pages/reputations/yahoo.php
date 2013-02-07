<!-- Content -->
    <div class="content hideTagFilter">
    	<div class="title"><h5>Yahoo Reviews</h5></div>
        <?php if($url) : ?>
            <div id="review" style="display:none;"></div>
            <div id="loader" style="text-align:center;display:none;"><img class="p12" src="<?= base_url(); ?>assets/themes/itsbrain/imgs/loaders/loader8.gif" /></div>
        <? else : ?>
        	<div class="nNote nWarning">
        		<p><strong>Warning: </strong>No Yahoo reviews found for this client.</p>
            </div>
        <? endif; ?>
        <script type="text/javascript">
			$('#loader').fadeIn('fast');
			$.ajax({
				url:'<?= base_url(); ?>scrape/yahooReviews.php',
				type:'POST',
				data:{url:'<?= $url; ?>'},
				success:function(result) {
					$('#loader').fadeOut('fast',function() {
						//$('#review').html(result);
						review = result;
						
						$('#review').append(review);
					});
						
				}
			});
			window.setTimeout(doStuff,3000,true);
			function doStuff() {
				$('#review').fadeIn('fast');
				$('div.content title').remove();
				$('div.content meta').remove();	
				$('div.content div.yl-biz-cont2 a').click(function() { return false });
				$('div.content div.yl-biz-cont2 a').css({'color':'#666666'});
			}
		</script>
        <div class="fix"></div>
    </div>
