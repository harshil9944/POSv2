<?php
$layout_class = _get_layout_type();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <base href="<?php _ebase_url(); ?>">
    <title><?php echo _get_page_title(); ?></title>

    <?php echo _get_styles('header'); ?>
    <?php echo _get_scripts('header'); ?>
    <?php _ejs_vars('h'); ?>
</head>
<body>
<?php //dump_exit(_get_module('core/menu')); ?>
<?php if(1==2){ ?><div class="preloader-it"><div class="loader-pendulums"></div></div><?php } ?>
<div class="hk-wrapper hk-vertical-nav">
    <?php echo _get_module('core/topbar'); ?>
    <?php echo _get_module('core/sidebar'); ?>
    <div class="hk-pg-wrapper">
        <div class="<?php echo $layout_class; ?> mt-20">
            <?php echo _get_var('message',''); ?>
            <div class="hk-pg-header align-items-top">
                <h3 class="hk-pg-title font-weight-600 mb-10"><?php echo _get_page_heading(); ?></h3>
            </div>
            <?php echo _get_page_content(); ?>
        </div>
    </div>
</div>
<?php _eview('components/image_dialog'); ?>
<?php echo _get_styles('footer'); ?>
<?php _ejs_vars('f'); ?>
<?php echo _get_scripts('footer'); ?>
</body>
</html>
