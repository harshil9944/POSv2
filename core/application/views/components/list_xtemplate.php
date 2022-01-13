<?php
$page = _get_var('page_data',[]);
$singular = $page['singular'];//'Group';
$plural = $page['plural'];//'Groups';
$add_url = $page['add_url']; //_get_var('add_url','#');
$button = (isset($page['button']) && is_array($page['button']))?$page['button']:false;
$vue_add_url = (isset($page['vue_add_url']) && $page['vue_add_url'])?$page['vue_add_url']:false; //_get_var('add_url','#');
$table = $page['table'];
$edit_form = (isset($page['edit_form']) && $page['edit_form'])?$page['edit_form']:false;
$form_inline = (isset($page['form_inline']) && $page['form_inline'])?' class="form-inline"':'';
$search = (isset($page['search']) && $page['search'])?true:false;
$filter_dropdown = (isset($page['filter_dropdown']) && $page['filter_dropdown'] && $search)?true:false;
$paginate = (isset($page['paginate_url']) && $page['paginate_url'])?true:false;
if($paginate) {
    _library('pagination');
    $config = [
        'base_url'              =>  $page['paginate_url'],
        'total_rows'            =>  $page['total_rows'],
        'per_page'              =>  $page['per_page'],
        'page_query_string'     =>  true,
        'query_string_segment'  =>  'offset',
        'reuse_query_string'    =>  true,
        'full_tag_open'         =>  '<ul class="pagination justify-content-center">',
        'full_tag_close'        =>  '</ul>',
        'prev_tag_open'         =>  '<li class="page-item">',
        'prev_tag_close'        =>  '</li>',
        'next_tag_open'         =>  '<li class="page-item">',
        'next_tag_close'        =>  '</li>',
        'cur_tag_open'          =>  '<li class="page-item active"><a href="javascript:void(0);" class="page-link">',
        'cur_tag_close'         =>  '</a></li>',
        'num_tag_open'          =>  '<li class="page-item">',
        'num_tag_close'         =>  '</li>',
        'attributes'            =>  ['class'=>'page-link']
    ];
    $this->pagination->initialize($config);
}
?>
<script type="text/x-template" id="general-list-template">
    <div id="<?php echo str_replace(' ','-',strtolower($singular)); ?>-list-container" class="row">
        <?php if($edit_form) { ?><gen-form></gen-form><?php } ?>
        <?php echo _get_additional_component('inside'); ?>
        <div class="col-md-12">
            <div id="block-list" class="block">
                <?php if($add_url || $button || $vue_add_url || $search){ ?>
                    <div class="block-header block-header-default">
                        <div class="float-left col-4">
                        <?php if($add_url) { ?>
                            <a href="<?php echo $add_url ?>" class="btn btn-primary btn-noborder btn-sm"><i class="fa fa-plus mr-5"></i><span class="btn-text">New <?php echo $singular; ?></span></a>
                        <?php } ?>
                        <?php if($button) { ?>
                            <a href="<?php echo $button['url']; ?>" class="btn btn-primary btn-noborder btn-sm"><i class="<?php echo $button['icon']; ?> mr-5"></i><?php echo $button['label']; ?></a>
                        <?php } ?>
                        <?php if($vue_add_url) { ?>
                            <a href="#" @click.prevent="<?php echo $vue_add_url; ?>" class="btn btn-primary btn-noborder btn-sm"><i class="fa fa-plus mr-5"></i><span class="btn-text">New <?php echo $singular; ?></span></a>
                        <?php } ?>
                        </div>
                        <?php if($search) { ?>
                            <div class="col-8">
                                <form action="" class="form-inline float-right">
                                    <?php if($filter_dropdown) { ?>
                                        <select class="form-control mr-2" v-model="search.filterDropdown.value">
                                            <option :value="search.filterDropdown.defaultValue">{{ search.filterDropdown.defaultTitle }}</option>
                                            <option v-for="single in search.filterDropdown.values" :value="single.id">{{ single.value }}</option>
                                        </select>
                                    <?php } ?>
                                    <div class="input-group">
                                        <input type="text" class="form-control" v-model="search.string" @keyup.enter="handleSearchSubmit" placeholder="Search...">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" @click="handleSearchSubmit"><i class="fa fa-search"></i>&nbsp;Search</button>
                                            <button type="button" class="btn btn-danger" @click="handleSearchClear"><i class="fa fa-close"></i>&nbsp;Clear</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="block-content block-content-full">
                    <div class="table-responsive">
                        <?php echo $table; ?>
                    </div>
                    <?php if($paginate){ echo $this->pagination->create_links(); } ?>
                </div>
            </div>
        </div>
    </div>
</script>
