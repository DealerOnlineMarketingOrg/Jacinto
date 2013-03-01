<?
	$full_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$full_url = explode('/',$full_url);
?>
<div class="leftNav">
    <!-- <div class="searchWidget searchMe">
        <form action="#" method="post">
            <input id="ac" class="ui-autocomplete-input" type="text" placeholder="Enter search text..." name="search" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" />
            <input type="submit" value="" name="find" />
        </form>
    </div> -->
	<div class="fix"></div>
    <ul id="menu">
        <li class="dash"><a <?php echo  (!$active_button) ? 'class="active"' : ''; ?> href="<?php echo  base_url(); ?>reports/dashboard"><span>Dashboard</span></a></li>
        <?php foreach($nav as $item) { ?>
			<?php if($item['Class'] == $active_button) { ?>
                <li class="<?php echo  $item['Class']; ?>"><a class="active" href="<?php echo  $item['Href']; ?>"><span><?php echo  $item['Label']; ?></span></a>
            <?php }else { ?>
                <li class="<?php echo  $item['Class']; ?>"><a class="exp" href="<?php echo  $item['Href']; ?>"><span><?php echo  $item['Label']; ?></span></a>
            <?php } ?>
			<?php if(count($item['Subnav']) > 0) { ?>
                <ul class="sub">
                    <?php foreach($item['Subnav'] as $sub) { ?>
                        <li <?php echo  ('/' . $full_url[3] == $sub->Href) ? 'class="current"' : ''; ?>><a href="<?php echo  $sub->Href; ?>"><?php echo  $sub->Label; ?></a></li>
                    <?php } //end subnav foreach?>
                </ul>
            <?php } //end count?>
        </li>
    <?php } //endforeach ?>
    </ul>
</div>
<!-- Sidebar -->
<script type="text/javascript">
	$(document).ready(function() {
		$('li.<?php echo  $full_url[3]; ?>').find('ul.sub').css({'display':'block'});
	});
</script>