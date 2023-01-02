<?php if(_can('dashboard','page')) { ?>
    <dashboard></dashboard>
<?php }elseif(_can('pos','page')){ ?>
    <?php redirect('pos') ?>
<?php }else{ ?>
    <?php show_404(); ?>
<?php } ?>
