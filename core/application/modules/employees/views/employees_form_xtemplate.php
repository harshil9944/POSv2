<?php
$code = 'employee';
?>
<script type="text/x-template" id="employee-form-template">
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
                                            <label for="first-name">First Name</label>
                                            <input class="form-control" id="first-name" placeholder="First Name" type="text" v-model="user.first_name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last-name">Last Name</label>
                                            <input class="form-control" id="last-name" placeholder="Last Name" type="text" v-model="user.last_name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input class="form-control" id="email" placeholder="Email Address" type="email" v-model="user.email"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <input class="form-control" id="mobile" placeholder="Mobile" type="text" v-model="user.mobile"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Code</label>
                                            <input class="form-control" id="code" placeholder="Code" type="Number" v-model="user.code" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user-status">Status</label>
                                            <select id="user-status" class="form-control custom-select d-block w-100" v-model="user.status">
                                                <option v-for="status in statuses" :value="status.id">{{ status.value }}</option>
                                            </select>
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
