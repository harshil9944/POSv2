<?php
$cb = $this->brahma;
?>
<footer id="page-footer" class="opacity-0">
    <div class="content py-20 font-size-xs clearfix">
        <div class="float-right">
            <?php if($cb->url){ ?>
                <a class="font-w600" href="<?php echo $cb->url; ?>" target="_blank"><?php echo $cb->name . ' v' . $cb->version; ?></a> &copy; <span class="js-year-copy"></span>
            <?php }else{ ?>
                <a class="font-w600" href="javascript:void(0);>" target="_blank"><?php echo $cb->name . ' v' . $cb->version; ?></a> &copy; <span class="js-year-copy"></span>
            <?php } ?>
        </div>
    </div>
</footer>

