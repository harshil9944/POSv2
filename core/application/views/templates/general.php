<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php _ebase_url(); ?>">
    <title><?php echo _get_page_title(); ?></title>
    <?php echo _get_styles('header'); ?>
    <?php echo _get_scripts('header'); ?>
    <?php _ejs_vars('h'); ?>
</head>
<body id="app-container" class="menu-default show-spinner">
<?php echo _get_module('core/topbar'); ?>
<?php echo _get_module('core/sidebar'); ?>
<main>
    <div class="<?php echo _get_layout_type(); ?>">
        <div class="row">
            <div class="col-12">
                <h1 class="pb-0 medium"><?php echo _get_page_heading(); ?></h1>
                <?php echo _get_page_content(); ?>
            </div>
        </div>
    </div>
</main>
<?php echo _get_styles('footer'); ?>
<?php _ejs_vars('f'); ?>
<?php echo _get_scripts('footer'); ?>
</body>

</html>
