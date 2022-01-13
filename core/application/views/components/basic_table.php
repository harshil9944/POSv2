<?php
$heading = _get_var('table_heading',[]);
$body = _get_var('table_body',[]);
$template = array(
    'table_open'            => '<table class="table table-hover table-sm table-bordered mb-0">',
);
$this->table->set_template($template);
$this->table->set_heading($heading);
foreach ($body as $row) {
    $this->table->add_row($row);
}
?>
<?php if($heading && $body) { ?>
<div class="row">
    <div class="col-sm">
        <div class="table-wrap">
            <div class="table-responsive">
                <?php echo $this->table->generate(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>