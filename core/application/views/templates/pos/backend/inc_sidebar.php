<?php
$cb = $this->brahma;
?>
<nav id="sidebar">
    <div class="sidebar-content">
        <div class="content-header content-header-fullrow px-15">
            <div class="content-header-section sidebar-mini-visible-b">
                <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                    <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                </span>
            </div>
            <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times text-danger"></i>
                </button>
                <div class="content-header-item">
                    <a class="font-w700" href="<?php _ebase_url(); ?>">
                        <img class="img-fluid w-90" src="<?php _easset_url('assets/img/logo.svg'); ?>" alt="<?php echo _get_setting('company_name',CORE_APP_TITLE); ?>"/>
                    </a>
                </div>
            </div>
        </div>
        <div class="content-side content-side-full border-top">
            <ul class="nav-main">
                <?php $cb->build_nav(); ?>
            </ul>
        </div>
    </div>
</nav>

