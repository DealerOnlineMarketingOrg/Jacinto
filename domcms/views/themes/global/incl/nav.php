<div class="leftNav">
    <ul id="menu">
        <li class="dash"><a <?= ((ACTIVE_BUTTON == 'reports' && SUBNAV_BUTTON == '/reports/dashboard') ? 'class="active"' : ''); ?> href="<?= base_url(); ?>reports/dashboard"><span>Dashboard</span></a></li>
        <?php foreach($nav as $item) { ?>
        <li class="<?= $item['Class']; ?>"><a class="exp <?= ((ACTIVE_BUTTON == $item['Class'] && SUBNAV_BUTTON != '/reports/dashboard') ? 'active' : ''); ?>" href="<?= $item['Href']; ?>"><span><?= $item['Label']; ?></span></a>
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
	
</script>