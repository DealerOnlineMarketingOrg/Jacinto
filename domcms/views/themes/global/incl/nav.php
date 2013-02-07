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
        <li class="dash"><a <?= ((ACTIVE_BUTTON == 'reports' && SUBNAV_BUTTON == '/reports/dashboard') ? 'class="active"' : ''); ?> href="<?= base_url(); ?>reports/dashboard"><span>Dashboard</span></a></li>
        <?php foreach($nav as $item) { ?>
        <li class="<?= $item['Class']; ?>"><a class="<?= (($full_url[3] == $item['Class'] && SUBNAV_BUTTON != '/reports/dashboard') ? 'active' : 'exp'); ?>" href="<?= $item['Href']; ?>"><span><?= $item['Label']; ?></span></a>
        <?php if(count($item['Subnav']) > 0) { ?>
            <ul class="sub">
                <?php foreach($item['Subnav'] as $sub) { ?>
                    <li <?= ((SUBNAV_BUTTON == $sub->Href) ? 'class="current"' : ''); ?>><a href="<?= $sub->Href; ?>"><?= $sub->Label; ?></a></li>
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
		$('li.<?= $full_url[3]; ?>').find('ul.sub').css({'display':'block'});
	});
</script>