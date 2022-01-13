<?php
$singular = 'areas';
$plural = 'areas';
$loop_var = 'areas';
?>
<div class="row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <?php _eview('components/list_add_button',['url'=>base_url('areas/add'),'title'=>'New User']); ?>
            <div class="row">
                <div class="col-sm">
                    <div class="table-wrap mb-30">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                <tr>
                                    <th width="50px">
                                        <div class="custom-control custom-checkbox checkbox-teal">
                                            <input type="checkbox" class="custom-control-input" id="select-all">
                                            <label class="custom-control-label" for="select-all">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Slug</th>
                                    <th class="text-center w-70p" nowrap>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(_get_var('users',false)) { ?>
                                    <?php foreach(_get_var('users',[]) as $row) { ?>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox checkbox-teal">
                                                    <input type="checkbox" class="custom-control-input" id="check-<?php echo $row['id']; ?>">
                                                    <label class="custom-control-label" for="check-<?php echo $row['id']; ?>">&nbsp;</label>
                                                </div>
                                            </td>
                                            <td><?php echo $row['slug']; ?></td>
                                            <td><?php echo ($row['status'])?'Enabled':'Disabled'; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr><td colspan="3" class="text-center"><?php echo $plural; ?> not found. Try Refresh.</td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
