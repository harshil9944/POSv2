<?php $cb = $this->brahma; ?>
<?php _view('templates/pos/global'); ?>
<?php _view('templates/pos/backend'); ?>
<?php _eview('templates/pos/components/head_start'); ?>
<?php _eview('templates/pos/components/head_end'); ?>
<?php _eview('templates/pos/components/page_start'); ?>
<div class="content p-2">
<?php echo _get_page_content(); ?>
</div>
<?php _eview('templates/pos/components/page_end'); ?>
<?php _eview('templates/xtemplates'); ?>
<?php _eview('templates/pos/components/footer_start'); ?>
<?php _eview('templates/pos/components/footer_end'); ?>
