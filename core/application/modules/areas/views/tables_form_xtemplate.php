<?php
$code = 'table';
?>
<script type="text/x-template" id="table-form-template">
    <div id="<?php echo $code; ?>-form" class="row">
        <div class="col-xl-12">
            <div class="block">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-sm">
                            <form id="frm-<?php echo $code; ?>" data-parsley-validate="true">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input class="form-control" id="title" placeholder="Title" type="text" v-model="table.title" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input class="form-control" id="description" placeholder="Description" type="text" v-model="table.description"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max-seat">Max seat</label>
                                            <input class="form-control" id="max-seat" placeholder="Max seat" type="number" v-model="table.max_seat" required />
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="short-name">Short Name</label>
                                            <input class="form-control" id="short-name" placeholder="Short name" type="text" v-model="table.short_name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="area-id">Area</label>
                                            <select id="area-id" class="form-control custom-select d-block w-100" v-model="table.area_id" required>
                                                <option value="" selected>None</option>
                                                <option v-for="area in masters.areas" :value="area.id">{{ area.title }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="table-status">Status</label>
                                            <select id="table-status" class="form-control custom-select d-block w-100" v-model="table.status">
                                               <option v-for="status in masters.statuses" :value="status.id">{{ status.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort-order">Sort Order&nbsp;<span class="text-danger"></span></label>
                                            <input class="form-control" id="sort-order" placeholder="sort order" type="number" v-model="table.sort_order"  />
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="button" @click.prevent="submit">Save</button>
                                <button class="btn btn-danger ml-10" type="button" @click.prevent="cancel">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
