<?php
$code = 'category';
?>
<script type="text/x-template" id="<?php echo $code; ?>-form-template">
    <form id="frm-<?php echo $code; ?>" class="w-100" data-parsley-validate="true">
        <div id="<?php echo $code; ?>-form" class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row mb-20">
                            <div class="col-sm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title&nbsp;<span class="text-danger">*</span></label>
                                            <input class="form-control" id="title" placeholder="Title" type="text" v-model="category.title" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort order">Sort order&nbsp;</label>
                                            <input class="form-control" id="sort_order" placeholder="Sort Order" type="number" v-model="category.sortOrder"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select id="type" class="form-control custom-select d-block w-100" v-model="category.type">
                                               <option v-for="s in masters.types" :value="s.id">{{ s.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pos-status">Show in POS?</label>
                                            <select id="pos-status" class="form-control custom-select d-block w-100" v-model="category.posStatus">
                                               <option v-for="s in masters.posStatuses" :value="s.id">{{ s.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="web-status">Show on Web?</label>
                                            <select id="web-status" class="form-control custom-select d-block w-100" v-model="category.webStatus">
                                               <option v-for="s in masters.webStatuses" :value="s.id">{{ s.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="app-status">Show on APP?</label>
                                            <select id="app-status" class="form-control custom-select d-block w-100" v-model="category.appStatus">
                                               <option v-for="s in masters.appStatuses" :value="s.id">{{ s.value }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content block-content-full">
                        <button class="btn btn-primary" type="button" @click.prevent="submit">Save</button>
                        <button class="btn btn-danger ml-10" type="button" @click.prevent="cancel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</script>
