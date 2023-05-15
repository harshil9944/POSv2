<?php
$code = 'inquiry';
?>
<script type="text/x-template" id="<?php echo $code; ?>-form-template">
    <form id="frm-<?php echo $code; ?>" data-parsley-validate="true" @submit.prevent="handleSubmit">
        <div id="<?php echo $code; ?>-form" class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="block">
                            <div class="block-content block-content-full">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label for="<?php echo $code; ?>-date"
                                                           class="col-sm-3 col-form-label text-danger">Date*</label>
                                                    <div class="col-sm-9">
                                                        <vuejs-datepicker name="<?php echo $code; ?>-date"
                                                                          :format="dtFormat" :bootstrap-styling="true"
                                                                          v-model="entry.date"></vuejs-datepicker>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-12"><?php echo get_text(['id' => 'booking-name', 'title' => 'Booking Name', 'attribute' => 'required', 'vue_model' => 'entry.bookingName']); ?></div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label for="<?php echo $code; ?>-status"
                                                           class="col-sm-3 col-form-label text-danger">Status*</label>
                                                    <div class="col-sm-9">
                                                        <?php echo get_select(['id' => $code . '-status', 'title' => 'Status', 'class' => 'mr-2', 'attribute' => '', 'vue_model' => 'entry.status', 'vue_for' => 'lists.statuses'], [], 'title', 'id', true); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-12"><?php echo get_text(['id' => 'email', 'title' => 'Email', 'vue_model' => 'entry.email']); ?></div>
                                            <div
                                                class="col-md-12"><?php echo get_text(['id' => 'phone', 'title' => 'Phone', 'vue_model' => 'entry.phone']); ?></div>
                                            <div
                                                class="col-md-12"><?php echo get_number(['id' => 'number_of_person', 'title' => 'Number of Persons', 'vue_model' => 'entry.numberOfPerson']); ?></div>
                                            <div
                                                class="col-md-12"><?php echo get_text(['id' => 'advance', 'title' => 'Advance Received', 'vue_model' => 'entry.advance']); ?></div>
                                            <div
                                                class="col-md-12"><?php echo get_textarea(['id' => 'description', 'title' => 'Description', 'vue_model' => 'entry.description']); ?></div>
                                            <div
                                                class="col-md-12"><?php echo get_textarea(['id' => 'menu', 'title' => 'Menu', 'vue_model' => 'entry.menu']); ?></div>
                                            <div
                                                class="col-md-12"><?php echo get_textarea(['id' => 'remark', 'title' => 'Remark', 'vue_model' => 'entry.remark']); ?></div>
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
        </div>
    </form>
</script>
