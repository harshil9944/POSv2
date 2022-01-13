<?php
$code = 'vendor';
?>
<script type="text/x-template" id="vendor-form-template">
    <div id="<?php echo $code; ?>-form" class="row" v-cloak>
        <form id="frm-<?php echo $code; ?>">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-12"><?php echo get_text(['id'=>$code.'-customer-id','title'=>'Vendor ID','attribute'=>'readonly','vue_model'=>$code.'.vendorId']); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>$code.'-company-name','title'=>'Company Name','attribute'=>'required @blur="onCompanyName"','vue_model'=>$code.'.companyName']); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>$code.'-display-name','title'=>'Contact Display Name','attribute'=>'required','vue_model'=>$code.'.displayName']); ?></div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code.'-salutation'; ?>">Primary Contact</label>
                                                    <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                        <?php echo get_select(['id'=>$code.'-salutation','title'=>'Salutation','class'=>'mr-2','attribute'=>'v-cloak','vue_model'=>$code.'.salutation','vue_for'=>'salutations'],[],'value','id',true); ?>
                                                        <?php echo get_text(['id'=>$code.'-first-name','title'=>'First Name','placeholder'=>'First Name','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.firstName'],'text',true); ?>
                                                        <?php echo get_text(['id'=>$code.'-last-name','title'=>'Last Name','placeholder'=>'Last Name','attribute'=>'','vue_model'=>$code.'.lastName'],'text',true); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>$code.'-position','title'=>'Contact Position','attribute'=>'','vue_model'=>$code.'.position']); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>$code.'-email','title'=>'Contact Email','attribute'=>'','vue_model'=>$code.'.email'],'email'); ?></div>
                                            <div class="col-md-12"><?php echo get_text(['id'=>$code.'-phone','title'=>'Contact Phone','attribute'=>'','vue_model'=>$code.'.phone']); ?></div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code.'-additional-emails'; ?>">Additional Emails</label>
                                                    <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                        <vue-tags-input
                                                                id="<?php echo $code; ?>-additional-emails"
                                                                :add-on-key="vueTagInput.addOnKey"
                                                                placeholder="Additional Emails..."
                                                                v-model="email"
                                                                :tags="<?php echo $code; ?>.additionalEmails"
                                                                @before-adding-tag="onTagChange"
                                                                @before-deleting-tag="onTagDelete" />
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if(1==2){ ?>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-designation','title'=>'Designation','attribute'=>'','vue_model'=>$code.'.designation']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-department','title'=>'Department','attribute'=>'','vue_model'=>$code.'.department']); ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="block">
                            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab-tax-payment-details">Tax &amp; Payment Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-address">Address</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-additional-contact">Additional Contact</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab-notes">Notes</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <div class="tab-pane active" id="tab-tax-payment-details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-7"><?php echo get_select(['id'=>$code.'-currency','title'=>'Currency','class'=>'mr-2','attribute'=>'required','vue_model'=>$code.'.currencyId','vue_for'=>'currencies'],[],'value','id'); ?></div>
                                        <?php if(1==2){ ?>
                                            <div class="col-md-7"><?php echo get_select(['id'=>$code.'-price-list','title'=>'Group','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.priceListId','vue_for'=>'priceLists'],[],'value','id'); ?></div>
                                            <div class="col-md-7"><?php echo get_select(['id'=>$code.'-payment-terms','title'=>'Payment Terms','class'=>'mr-2','attribute'=>'','vue_model'=>$code.'.paymentTerm','vue_for'=>'paymentTerms'],[],'value','id'); ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-address" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12"><h6>Billing Address</h6></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-attention','title'=>'Attention','attribute'=>'','vue_model'=>$code.'.billing.attention']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-address-1','title'=>'Address 1','attribute'=>'','vue_model'=>$code.'.billing.address1']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-address-2','title'=>'Address 2','attribute'=>'','vue_model'=>$code.'.billing.address2']); ?></div>
                                                <div class="col-md-12"><?php echo get_select(['id'=>$code.'-billing-country','title'=>'Country','attribute'=>'','vue_model'=>$code.'.billing.country','vue_for'=>'masters.countries']); ?></div>
                                                <div class="col-md-12"><?php echo get_select(['id'=>$code.'-billing-state','title'=>'State','attribute'=>'','vue_model'=>$code.'.billing.state','vue_for'=>'masters.states.billing']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-city','title'=>'City','attribute'=>'','vue_model'=>$code.'.billing.city']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-zip-code','title'=>'Zip Code','attribute'=>'','vue_model'=>$code.'.billing.zipCode']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-billing-phone','title'=>'Phone','attribute'=>'','vue_model'=>$code.'.billing.phone']); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12"><h6>Shipping Address<a @click.prevent="copyBillingAddress" href="#" class="float-right font-12">Copy from billing address</a></h6></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-attention','title'=>'Attention','attribute'=>'','vue_model'=>$code.'.shipping.attention']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-address-1','title'=>'Address 1','attribute'=>'','vue_model'=>$code.'.shipping.address1']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-address-2','title'=>'Address 2','attribute'=>'','vue_model'=>$code.'.shipping.address2']); ?></div>
                                                <div class="col-md-12"><?php echo get_select(['id'=>$code.'-shipping-country','title'=>'Country','attribute'=>'','vue_model'=>$code.'.shipping.country','vue_for'=>'masters.countries']); ?></div>
                                                <div class="col-md-12"><?php echo get_select(['id'=>$code.'-shipping-state','title'=>'State','attribute'=>'','vue_model'=>$code.'.shipping.state','vue_for'=>'masters.states.shipping']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-city','title'=>'City','attribute'=>'','vue_model'=>$code.'.shipping.city']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-zip-code','title'=>'Zip Code','attribute'=>'','vue_model'=>$code.'.shipping.zipCode']); ?></div>
                                                <div class="col-md-12"><?php echo get_text(['id'=>$code.'-shipping-phone','title'=>'Phone','attribute'=>'','vue_model'=>$code.'.shipping.phone']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-additional-contact" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-12" v-for="(single,index) in <?php echo $code; ?>.additionalContacts">
                                                    <h6 class="text-corporate">Contact #{{ index + 1 }}</h6>
                                                    <div class="form-group row">
                                                        <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code.'-additional-salutation'; ?>">Name</label>
                                                        <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                            <?php echo get_select(['id'=>$code.'-additional-salutation','title'=>'Salutation','class'=>'mr-2','attribute'=>'v-cloak','vue_model'=>'single.salutationId','vue_for'=>'salutations'],[],'value','id',true); ?>
                                                            <?php echo get_text(['id'=>$code.'-additional-first-name','title'=>'First Name','placeholder'=>'First Name','class'=>'mr-2','attribute'=>'','vue_model'=>'single.firstName'],'text',true); ?>
                                                            <?php echo get_text(['id'=>$code.'-additional-last-name','title'=>'Last Name','placeholder'=>'Last Name','attribute'=>'','vue_model'=>'single.lastName'],'text',true); ?>
                                                        </div>
                                                    </div>
                                                    <?php echo get_text(['id'=>$code.'-additional-position','title'=>'Position','attribute'=>'','vue_model'=>'single.position']); ?>
                                                    <?php echo get_text(['id'=>$code.'-additional-email','title'=>'Email','attribute'=>'','vue_model'=>'single.email']); ?>
                                                    <?php echo get_text(['id'=>$code.'-additional-phone','title'=>'Phone','attribute'=>'','vue_model'=>'single.phone']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-notes" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <?php echo get_textarea(['id'=>$code.'-notes','title'=>'Notes','attribute'=>'','vue_model'=>$code.'.notes']); ?>
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
