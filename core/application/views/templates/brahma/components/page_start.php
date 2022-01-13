<?php
$cb = $this->brahma;
?>
<?php echo _get_additional_component('outside'); ?>
<div id="page-container"<?php $cb->page_classes(); ?>>
    <?php if(isset($cb->inc_side_overlay) && $cb->inc_side_overlay) { echo $cb->inc_side_overlay; } ?>
    <?php if(isset($cb->inc_sidebar) && $cb->inc_sidebar) { echo $cb->inc_sidebar; } ?>
    <?php if(isset($cb->inc_header) && $cb->inc_header) { echo $cb->inc_header; } ?>
    <main id="main-container">
