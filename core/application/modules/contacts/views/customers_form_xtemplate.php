<?php
$code = 'customer';
?>
<script type="text/x-template" id="customer-form-template">
    <div id="<?php echo $code; ?>-form" class="row">
        <form id="frm-customer" data-parsley-validate="true" @submit.prevent="handleSubmit">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12"><?php echo get_text(['id'=>'customer-customer-id','title'=>'Customer Id','attribute'=>'disabled','vue_model'=>'customer.customerId']); ?></div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code.'-salutation'; ?>">Customer Name</label>
                                                    <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                        <?php echo get_text(['id'=>$code.'-first-name','title'=>'First Name','placeholder'=>'First Name','class'=>'mr-2','attribute'=>'@blur="onName"','vue_model'=>$code.'.firstName'],'text',true); ?>
                                                        <?php echo get_text(['id'=>$code.'-last-name','title'=>'Last Name','placeholder'=>'Last Name','attribute'=>'@blur="onName"','vue_model'=>$code.'.lastName'],'text',true); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>'customer-display-name','title'=>'Display Name','attribute'=>'required','vue_model'=>'customer.displayName']); ?></div>
                                            <div class="col-md-12"><?php $required=(_get_var('customer_user_field','mobile')=='mobile')?'required':'';echo get_text(['id'=>'customer-phone','title'=>'Mobile','attribute'=>$required.' ref="phone"','vue_model'=>'customer.phone']); ?></div>
                                            <div class="col-md-12"><?php $required=(_get_var('customer_user_field','mobile')=='email')?'required':'';echo get_text(['id'=>'customer-email','title'=>'Email','attribute'=>$required.' ref="email"','vue_model'=>'customer.email'],'email'); ?></div>
                                            <div v-if="isCustomFields('fullVaccinated')" class="col-md-12 mb-3">
                                                <div class="row">
                                                    <label class="col-md-3 col-form-label" for="customer-full-vaccinated">Fully Vaccinated</label>
                                                    <div class="col-md-9"><?php echo get_select(['id'=>$code.'-full-vaccinated','title'=>'Full Vaccinated','attribute'=>'','vue_model'=>$code.'.fullVaccinated','vue_for'=>'vaccination'],[],'value','id',true); ?></div>
                                                </div>
                                            </div>
                                            <div v-if="isCustomFields('memberNumber')" class="col-md-12"><?php echo get_text(['id'=>$code.'-member-number','title'=>'Member Number','attribute'=>'','vue_model'=>$code.'.memberNumber']); ?></div>
                                            <div v-if="allowCustomerGroup" class=" col-md-12">
                                                <div class="row">
                                                    <label class="col-md-3 col-form-label" for="customer-group">Customer Group</label>
                                                    <div class="col-md-9"><?php echo get_select(['id'=>$code.'-groupId','title'=>'Customer Group','attribute'=>'','vue_model'=>$code.'.groupId','vue_for'=>'masters.groups'],[],'value','id',true); ?></div>
                                                </div>
                                            </div>
                                            <div v-if="allowCustomerNotes"  class="col-md-12">
                                                <?php echo get_textarea(['id'=>$code.'-customer-nptes','title'=>'Notes','attribute'=>'','vue_model'=>$code.'.notes']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
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
