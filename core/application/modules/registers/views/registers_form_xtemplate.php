<?php
$code = 'register';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-code','title'=>'Code','attribute'=>'required readonly','vue_model'=>$code.'.code']],
    ['control'=>'text','code'=>['id'=>$code.'-name','title'=>'Register Name','attribute'=>'required','vue_model'=>$code.'.title']],
    ['control'=>'select','code'=>['id'=>$code.'-warehouse-id','title'=>'Outlet','attribute'=>'required','vue_model'=>$code.'.warehouseId','vue_for'=>'masters.warehouses']]
];
$global = ['id'=>1];
?>
<script type="text/x-template" id="registers-form-template">
    <div id="<?php echo $code; ?>-form" class="row" :global='<?php echo json_encode($global); ?>'>
        <form id="frm-<?php echo $code; ?>">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <div class="row">
                                    <?php foreach ($group_1 as $item) { ?>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php $func = 'get_'.$item['control'] ?>
                                                    <?php echo $func($item['code']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <a href="#" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                <a href="#" @click.prevent="cancel" class="btn btn-white btn-noborder">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</script>
