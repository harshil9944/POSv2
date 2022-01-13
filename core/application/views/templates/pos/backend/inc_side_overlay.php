<?php
$cb = $this->brahma;
?>
<aside id="side-overlay">
    <div class="content-header content-header-fullrow">
        <div class="content-header-section align-parent">
            <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                <i class="fa fa-times text-danger"></i>
            </button>
            <div class="content-header-item">
                <a class="img-link mr-5" href="#">
                    <img src="<?php _easset_url('assets/img/user-icon.svg'); ?>" alt="User Icon" class="w-25p">
                </a>
                <a class="align-middle link-effect text-primary-dark font-w600" href="#"><?php echo _get_session('name'); ?></a>
            </div>
        </div>
    </div>

    <div class="content-side">
        <div class="block pull-r-l">
            <div class="block-header bg-body-light">
                <h3 class="block-title">
                    <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Profile
                </h3>
            </div>
            <div class="block-content">
                <user-profile-update></user-profile-update>
            </div>
        </div>
    </div>
</aside>

