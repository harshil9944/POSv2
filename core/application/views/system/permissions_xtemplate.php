<script type="text/x-template" id="permission-form-template">
    <b-modal no-fade id="permission-form-modal" size="md" no-close-on-backdrop no-close-on-esc centered hide-header hide-footer body-class="p-0" v-cloak>
        <div id="permission-form-block" class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">New Permission</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" @click="handleCloseModal(modal.id)" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full min-height-100">
                <form id="frm-permission">
                    <div class="row">
                        <div class="col-md-12 mb-20">
                            <?php echo get_text(['id'=>'permission-code','title'=>'Code','attribute'=>'required','vue_model'=>'permission.code']); ?>
                        </div>
                        <div class="col-md-12 text-center mb-20">
                            <a href="#" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                            <a href="#" @click.prevent="handleCloseModal(modal.id)" class="btn btn-white btn-noborder">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </b-modal>
</script>
