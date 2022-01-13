<?php
$heading = _get_var('table_heading',[]);
$body = _get_var('table_body',[]);

if(!$body) {
    $not_found_cell = [
        'class'     =>  'text-center',
        'data'      =>  'Data not Found',
        'colspan'   =>  count($heading)
    ];
    $body[] = [$not_found_cell];
    $template = array(
        'table_open'            => '<table class="table">',
    );
}else{
    $template = array(
        'table_open'            => '<table id="data-list-table" class="table table-bordered table-striped table-vcenter js-dataTable-full">',
        'thead_open'            => '<thead class="font-11 font-weight-700">',
        'tbody_open'            => '<tbody class="font-12">',
    );
}
foreach ($body as $row) {
    $this->table->add_row($row);
}

$this->table->set_template($template);
$this->table->set_heading($heading);


?>
<?php if($heading && $body) { ?>
    <?php
    echo $this->table->generate();
    ?>
<?php } ?>
