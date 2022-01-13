<?php
$code = 'printer';
?>
<script type="text/x-template" id="<?php echo $code; ?>-form-template">
    <form id="frm-<?php echo $code; ?>" class="w-100" data-parsley-validate="true">
        <div id="<?php echo $code; ?>-form" class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content block-content-full">
                        <div class="row mb-20">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="title">Title&nbsp;<span class="text-danger">*</span></label>
                                            <input class="form-control" id="title" placeholder="title" type="text" v-model="printer.title" required />
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select id="status" class="form-control custom-select d-block w-100" v-model="printer.status">
                                               <option v-for="s in masters.statuses" :value="s.id">{{ s.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select id="type" class="form-control custom-select d-block w-100" v-model="printer.type">
                                               <option v-for="type in masters.types" :value="type.id">{{ type.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="port">Port&nbsp;<span class="text-danger"></span></label>
                                            <input class="form-control" id="port" placeholder="port" type="text" v-model="printer.port" />
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="address">Address&nbsp;<span class="text-danger"></span></label>
                                            <input class="form-control" id="address" placeholder="address" type="text" v-model="printer.address"  />
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="open-cash-drawer">Open Cash Drawer</label>
                                            <select id="open-cash-drawer" class="form-control custom-select d-block w-100" v-model="printer.openCashDrawer">
                                               <option v-for="s in masters.statuses" :value="s.id">{{ s.value }}</option>
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