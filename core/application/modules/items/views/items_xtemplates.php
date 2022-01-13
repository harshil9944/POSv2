<script type="text/x-template" id="items-import-export-template">
    <div class="col-12">
        <div class="block-header block-header-default">
            <div class="block-content block-content-full d-inline-block py-0">
                <button @click="handleImport" class="float-right btn btn-primary ml-3">Import</button>
                <a :href="exportUrl" target="_blank" class="float-right btn btn-primary">Export</a>
                <items-import></items-import>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="items-import-template">
    <div>
        <b-modal no-fade :id="modal.id" size="md" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="items-import-modal-block" class="block block-themed block-transparent bg-gray-light mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideModal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <div class="col-md-12">
                       <form id="frm-import-file" data-parsley-validate="true">
                            <div class="row d-flex">
                                <div class="col-md-12">
                                    <div class="block">
                                        <div class="block-content block-content-full">
                                            <b-form-file 
                                                accept=".xls,.xlsx"
                                                v-model="file.xlsx"
                                                required
                                                data-parsley-required-message="File is required">
                                            </b-form-file>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="block">
                                        <div class="block-content block-content-full">
                                            <a href="#" @click.prevent="handleSubmit" class="btn btn-primary btn-no-border pull-center">Import</a>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>