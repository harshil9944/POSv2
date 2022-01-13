<?php
$cb = $this->brahma;
?>
    <?php if(ENVIRONMENT=='production'){ ?>
    <link rel="stylesheet" href="<?php echo _get_setting('general_font_url','//fonts.googleapis.com/css?family=Muli:300,400,400i,600,700'); ?>">
    <?php } ?>
    <?php echo _get_styles('header'); ?>
    <?php echo _get_scripts('header'); ?>
    <?php _ejs_vars('h'); ?>
    <?php if ($cb->theme) { ?>
    <link rel="stylesheet" id="css-theme" href="<?php _easset_url('assets/css/themes/' . $cb->theme . '.min.css'); ?>">
    <?php } ?>
</head>
<body class="<?php echo _get_var('body_class'); ?>">
