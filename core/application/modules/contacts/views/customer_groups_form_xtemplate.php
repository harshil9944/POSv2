<?php
$code = 'customer_group';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-title','title'=>'Title','attribute'=>'required','vue_model'=>$code.'.title']],
    ['control'=>'number','code'=>['id'=>$code.'-pos-iscount','title'=>'Pos Discount (%)','attribute'=>'','vue_model'=>$code.'.posDiscount']],
    ['control'=>'number','code'=>['id'=>$code.'-web-iscount','title'=>'Web Discount (%)','attribute'=>'','vue_model'=>$code.'.webDiscount']],
   // ['control'=>'','number'=>['id'=>$code.'-sku','title'=>'App Discount','attribute'=>'required ','vue_model'=>$code.'.appDiscount']],
    ['control'=>'select','code'=>['id'=>$code.'-status','title'=>'Status','attribute'=>'','vue_model'=>$code.'.status','vue_for'=>'statuses']],
];
?>
<script type="text/x-template" id="customer-groups-template">
    <div id="customer-groups-form" class="row">
        <form id="frm-customer-groups" data-parsley-validate="true" @submit.prevent="handleSubmit">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <?php foreach ($group_1 as $item) { ?>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php $func = 'get_'.$item['control'] ?>
                                                            <?php echo $func($item['code']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
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
