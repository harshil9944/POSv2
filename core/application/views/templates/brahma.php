<?php $cb = $this->brahma; ?>
<?php _view('templates/brahma/global'); ?>
<?php _view('templates/brahma/backend'); ?>
<?php _eview('templates/brahma/components/head_start'); ?>
<?php _eview('templates/brahma/components/head_end'); ?>
<?php _eview('templates/brahma/components/page_start'); ?>
<div class="content">
    <?php echo _get_var('message'); ?>
    <h2 class="content-heading"><?php echo _get_page_heading(); ?></h2>
    <?php echo _get_page_content(); ?>
</div>
<?php _eview('templates/brahma/components/page_end'); ?>
<?php _eview('templates/xtemplates'); ?>
<?php _eview('templates/brahma/components/footer_start'); ?>
<?php _eview('templates/brahma/components/footer_end'); ?>
