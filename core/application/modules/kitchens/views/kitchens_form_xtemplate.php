<?php
$code = 'kitchen';
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
                                            <input class="form-control" id="title" placeholder="title" type="text" v-model="kitchen.title" required />
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="printer-id">Printer</label>
                                            <select id="printer-id" class="form-control custom-select d-block w-100" v-model="kitchen.printerId" >
                                                <option v-for="printer in masters.printers" :value="printer.id">{{ printer.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="template-id">Template</label>
                                            <select id="template-id" class="form-control custom-select d-block w-100" v-model="kitchen.templateId" >
                                                <option v-for="template in masters.templates" :value="template.id">{{ template.value }}</option>
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