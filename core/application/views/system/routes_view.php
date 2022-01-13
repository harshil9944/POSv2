<div class="row">
    <div class="col-xl-12">
        <?php if(1==2){ ?>
        <div class="row">
            <div class="col-12 mb-20">
                <a href="<?php _ebase_url('routes/refresh'); ?>" class="btn btn-primary btn-wth-icon btn-rounded icon-right btn-sm pull-right"><span class="btn-text">Refresh List</span><span class="icon-label"><i class="fa fa-refresh"></i></a>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm">
                <div class="table-wrap mb-30">
                    <div class="table-responsive">
                        <table id="data-list-table" class="table table-sm mb-0">
                            <thead>
                            <                     <th width="50px">
                                <div class="custom-control custom-checkbox checkbox-teal">
                                    <input type="checkbox" class="custom-control-input" id="select-all">
                                    <label class="custom-control-label" for="select-all">&nbsp;</label>
                                </div>
                            </th>
                            <th>Slug</th>
                            tr>
                              <th class="text-center" width="100px" nowrap>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(_get_var('routes',false)) { ?>
                            <?php foreach(_get_var('routes',[]) as $row) { ?>
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
                                <tr><td colspan="3" class="text-center">Routes not found. Try Refresh.</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
