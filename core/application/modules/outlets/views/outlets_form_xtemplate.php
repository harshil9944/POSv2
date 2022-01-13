<?php
$code = 'outlet';
$group_1 = [
    ['control'=>'text','code'=>['id'=>$code.'-code','title'=>'Code','attribute'=>'required readonly','vue_model'=>$code.'.code']],
    ['control'=>'select','code'=>['id'=>$code.'-vendor-id','title'=>'Vendor','vue_model'=>$code.'.vendorId','vue_for'=>'masters.vendors']],
    ['control'=>'select','code'=>['id'=>$code.'-customer-id','title'=>'Customer','vue_model'=>$code.'.customerId','vue_for'=>'masters.customers']],
    ['control'=>'text','code'=>['id'=>$code.'-name','title'=>'Outlet Name','attribute'=>'required','vue_model'=>$code.'.name']],
    ['control'=>'text','code'=>['id'=>$code.'-address-1','title'=>'Address 1','vue_model'=>$code.'.address1']],
    ['control'=>'text','code'=>['id'=>$code.'-address-2','title'=>'Address 2','vue_model'=>$code.'.address2']],
    ['control'=>'select','code'=>['id'=>$code.'-country','title'=>'Country','vue_model'=>$code.'.countryId','vue_for'=>'masters.countries']],
    ['control'=>'select','code'=>['id'=>$code.'-state','title'=>'State','attribute'=>'required','vue_model'=>$code.'.stateId','vue_for'=>'masters.states']],
    ['control'=>'text','code'=>['id'=>$code.'-city','title'=>'City','vue_model'=>$code.'.city']],
    ['control'=>'text','code'=>['id'=>$code.'-zipcode','title'=>'Zip Code','vue_model'=>$code.'.zipCode']],
    ['control'=>'text','code'=>['id'=>$code.'-phone','title'=>'Phone','vue_model'=>$code.'.phone']],
    ['control'=>'text','code'=>['id'=>$code.'-email','title'=>'Email Address','vue_model'=>$code.'.email']],
];
$global = ['id'=>1];
?>
<script type="text/x-template" id="outlet-form-template">
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
