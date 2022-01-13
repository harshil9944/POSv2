<script type="text/x-template" id="top-notification-template">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-flag"></i>
            <span class="badge badge-primary badge-pill">{{ notifications.length }}</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right min-width-300" aria-labelledby="page-header-notifications">
            <h5 class="h6 text-center py-10 mb-0 border-b text-uppercase">Notifications</h5>
            <ul v-if="notifications.length" class="list-unstyled my-20">
                <li v-for="single in notifications">
                    <a class="text-body-color-dark media mb-15" :href="single.url">
                        <div class="ml-5 mr-15">
                            <i class="fa fa-fw fa-plus text-primary"></i>
                        </div>
                        <div class="media-body pr-10">
                            <p class="mb-0">{{ single.title }}</p>
                            <div class="text-muted font-size-sm font-italic">{{ single.addedAgo }}</div>
                        </div>
                    </a>
                </li>
            </ul>
            <span v-if="!notifications.length" class="my-20 text-center d-block">No Notification</span>
            <div v-if="notifications.length" class="dropdown-divider"></div>
            <a v-if="notifications.length" class="dropdown-item text-center mb-0" href="javascript:void(0)">
                <i class="fa fa-flag mr-5"></i> View All
            </a>
        </div>
    </div>
</script>
<script type="text/x-template" id="user-profile-update-template">
    <form id="frm-user-profile-update" @submit.prevent="handleSubmit" data-parsley-validate="true">
        <div class="form-group mb-15">
            <label for="side-overlay-first-name">First Name</label>
            <div class="input-group">
                <input type="text" class="form-control" id="side-overlay-first-name" name="side-overlay-profile-name" placeholder="Your First Name.." v-model="user.firstName" readonly>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fa fa-user"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group mb-15">
            <label for="side-overlay-last-name">Last Name</label>
            <div class="input-group">
                <input type="text" class="form-control" id="side-overlay-profile-name" name="side-overlay-last-name" placeholder="Your Last Name.." v-model="user.lastName" readonly>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fa fa-user"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group mb-15">
            <label for="side-overlay-email">Email</label>
            <div class="input-group">
                <input type="email" class="form-control" id="side-overlay-profile-email" name="side-overlay-email" placeholder="Your email.." v-model="user.email" autocomplete="no" readonly>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fa fa-envelope"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group mb-15">
            <label for="side-overlay-current-password">Current Password</label>
            <input type="password" class="form-control" id="side-overlay-current-password" name="side-overlay-current-password" v-model="user.currentPassword" placeholder="Current Password.." autocomplete="current-password" required>
        </div>
        <div class="form-group mb-15">
            <label for="side-overlay-new-password">New Password</label>
            <input type="password" class="form-control" id="side-overlay-new-password" name="side-overlay-new-password" placeholder="New Password.." v-model="user.newPassword" autocomplete="new-password" required>
        </div>
        <div class="form-group mb-15">
            <label for="side-overlay-confirm-password">Confirm New Password</label>
            <input type="password" class="form-control" id="side-overlay-confirm-password" data-parsley-equalto="#side-overlay-new-password" name="side-overlay-confirm-password" placeholder="Confirm New Password.." v-model="user.confirmPassword" autocomplete="confirm-password" required>
        </div>
        <div class="form-group row">
            <div class="col-6">
                <button type="submit" class="btn btn-block btn-alt-primary">
                    <i class="fa fa-refresh mr-5"></i> Update
                </button>
            </div>
        </div>
    </form>
</script>
