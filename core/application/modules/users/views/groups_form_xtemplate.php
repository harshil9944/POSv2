<script type="text/x-template" id="group-form-template">
    <div id="group-form" class="row">
        <div class="col-xl-12">
            <div class="block">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-sm">
                            <form id="frm-group" data-parsley-validate="true">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input class="form-control" id="title" placeholder="Group Title" type="text" v-model="group.title" required />
                                </div>
                                <div class="form-group">
                                    <label for="default-page">Default Page</label>
                                    <select id="default-page" class="form-control custom-select d-block w-100" v-model="group.default_page">
                                        <option value="" selected>None</option>
                                        <option v-for="route in routes" :value="route.slug">{{ route.slug }}</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 mb-20">
                                        <h5>Permissions <a href="#" @click.prevent="selectAllPermissions" class="font-14 ml-10">Select All</a>&nbsp;/&nbsp;<a href="#" @click.prevent="deselectAllPermissions" class="font-14">Deselect All</a></h5>
                                    </div>
                                    <div class="col-sm-3" v-for="route in routes">
                                        <div class="custom-control custom-checkbox mb-15">
                                            <input class="custom-control-input" v-model="group.permissions" :id="'route-'+route.id" :value="route.slug" type="checkbox">
                                            <label class="custom-control-label" :for="'route-'+route.id">{{ route.slug }}</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
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
