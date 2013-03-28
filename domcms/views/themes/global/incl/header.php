<?php echo  $DocType . "\n" . $HTML . "\n"; ?>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title><?php echo  $Title; ?></title>
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/reset.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/dataTable.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/ui_custom.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/fullcalendar.css" />    
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/icons.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/elfinder.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/wysiwyg.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/css/prettyPhoto.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo  base_url(); ?>assets/<?php echo  $ThemeDir; ?>/css/main.css" />
    <link href="//fonts.googleapis.com/css?family=Cuprum" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/spinner/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/spinner/ui.spinner.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/wysiwyg/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/wysiwyg/wysiwyg.image.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/wysiwyg/wysiwyg.link.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/wysiwyg/wysiwyg.table.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/flot/jquery.flot.pie.js"></script>
    <!-- <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/flot/jquery.flot.canvas.js"></script> -->
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/flot/excanvas.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/tables/colResizable.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/forms.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/autogrowtextarea.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/autotab.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/jquery.validationEngine-en.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/jquery.dualListBox.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/jquery.inputlimiter.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/forms/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/other/calendar.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/other/elfinder.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/uploader/plupload.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/uploader/plupload.html5.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/uploader/plupload.html4.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/uploader/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.progress.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.tipsy.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.alerts.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.colorpicker.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/wizards/jquery.form.wizard.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/wizards/jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.breadcrumbs.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.collapsible.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.ToTop.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.listnav.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.sourcerer.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ui/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $ThemeDir; ?>/js/custom.js"></script>
    <!-- <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/charts/chart.js"></script> -->
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/charts/auto.js"></script>
	<script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/charts/bar.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/charts/hBar.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/charts/pie.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/html2canvas/html2canvas.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/html2canvas/jquery.plugin.html2canvas.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/jquery.fileDownload.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ZeroClipboard/ZeroClipboard.js"></script>
    <script type="text/javascript" src="<?php echo  base_url(); ?>assets/<?php echo  $GlobalDir; ?>/js/plugins/ZClip/jquery.zclip.js"></script>
    <?php if(isset($TagCss)) { echo $TagCss; }; ?>  

</head>
<body>