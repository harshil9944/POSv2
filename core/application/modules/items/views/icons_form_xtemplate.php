<?php
$code = 'icon';
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
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="title">Title&nbsp;<span class="text-danger">*</span></label>
                                            <input class="form-control" id="title" placeholder="Title" type="text" v-model="icon.title" required />
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
