<?php
$singular = 'Group';
$plural = 'Groups';
$loop_var = 'groups';
?>
<div id="group-list-container" class="row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper shadow-lg">
            <?php _eview('components/list_add_button',['url'=>base_url('users/groups/add'),'title'=>"New {$singular}"]); ?>
            <div class="row">
                <div class="col-12 mb-20">
                    <a href="<?php _ebase_url('users/groups/add'); ?>" class="btn btn-primary btn-wth-icon btn-rounded icon-right btn-sm pull-right"><span class="btn-text">New Group</span><span class="icon-label"><i class="fa fa-plus"></i></a>
                </div>
            </div>
            <?php echo _get_var('list_table','') ?>
        </section>
    </div>
</div>
