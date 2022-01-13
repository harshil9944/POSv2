<?php
$cb = $this->brahma;
?>
<header id="page-header">
    <div class="content-header">
        <div class="content-header-section">
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-navicon"></i>
            </button>
            <?php if(1==2) { ?>
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="header_search_on">
                <i class="fa fa-search"></i>
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-circle btn-dual-secondary" id="page-header-options-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-wrench"></i>
                </button>
                <div class="dropdown-menu min-width-300" aria-labelledby="page-header-options-dropdown">
                    <h5 class="h6 text-center py-10 mb-10 border-b text-uppercase">Settings</h5>
                    <h6 class="dropdown-header">Color Themes</h6>
                    <div class="row no-gutters text-center mb-5">
                        <div class="col-2 mb-5">
                            <a class="text-default" data-toggle="theme" data-theme="default" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-2 mb-5">
                            <a class="text-elegance" data-toggle="theme" data-theme="<?php echo $cb->assets_folder; ?>/css/themes/elegance.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-2 mb-5">
                            <a class="text-pulse" data-toggle="theme" data-theme="<?php echo $cb->assets_folder; ?>/css/themes/pulse.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-2 mb-5">
                            <a class="text-flat" data-toggle="theme" data-theme="<?php echo $cb->assets_folder; ?>/css/themes/flat.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-2 mb-5">
                            <a class="text-corporate" data-toggle="theme" data-theme="<?php echo $cb->assets_folder; ?>/css/themes/corporate.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                        <div class="col-2 mb-5">
                            <a class="text-earth" data-toggle="theme" data-theme="<?php echo $cb->assets_folder; ?>/css/themes/earth.min.css" href="javascript:void(0)">
                                <i class="fa fa-2x fa-circle"></i>
                            </a>
                        </div>
                    </div>
                    <h6 class="dropdown-header">Header</h6>
                    <div class="row gutters-tiny text-center mb-5">
                        <div class="col-6">
                            <button type="button" class="btn btn-sm btn-block btn-alt-secondary" data-toggle="layout" data-action="header_fixed_toggle">Fixed Mode</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-sm btn-block btn-alt-secondary d-none d-lg-block mb-10" data-toggle="layout" data-action="header_style_classic">Classic Style</button>
                        </div>
                    </div>
                    <h6 class="dropdown-header">Sidebar</h6>
                    <div class="row gutters-tiny text-center mb-5">
                        <div class="col-6">
                            <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="sidebar_style_inverse_off">Light</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="sidebar_style_inverse_on">Dark</button>
                        </div>
                    </div>
                    <div class="d-none d-xl-block">
                        <h6 class="dropdown-header">Main Content</h6>
                        <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="content_layout_toggle">Toggle Layout</button>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row gutters-tiny text-center">
                        <div class="col-6">
                            <a class="dropdown-item mb-0" href="be_layout_api.php">
                                <i class="si si-chemistry mr-5"></i> Layout API
                            </a>
                        </div>
                        <div class="col-6">
                            <a class="dropdown-item mb-0" href="be_ui_color_themes.php">
                                <i class="fa fa-paint-brush mr-5"></i> Color Themes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="content-header-section">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user d-sm-none"></i>
                    <span class="d-none d-sm-inline-block"><?php echo _get_session('short_name'); ?></span>
                    <i class="fa fa-angle-down ml-5"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">

                    <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase">User</h5>
                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="layout" data-action="side_overlay_toggle">
                        <i class="si si-user mr-5"></i> Profile
                    </a>
                    <?php if(1==2) { ?>
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.php">
                        <span><i class="si si-envelope-open mr-5"></i> Inbox</span>
                        <span class="badge badge-primary">3</span>
                    </a>
                    <a class="dropdown-item" href="be_pages_generic_invoice.php">
                        <i class="si si-note mr-5"></i> Invoices
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                        <i class="si si-wrench mr-5"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <?php } ?>
                    <a class="dropdown-item" href="<?php _ebase_url('auth/logout'); ?>">
                        <i class="si si-logout mr-5"></i> Sign Out
                    </a>
                </div>
            </div>
            <top-notification></top-notification>
            <?php if(1==2){ ?>
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                <i class="fa fa-tasks"></i>
            </button>
            <?php } ?>
        </div>
    </div>
    <div id="page-header-search" class="overlay-header">
        <div class="content-header content-header-fullrow">
            <form action="be_pages_generic_search.php" method="post">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
       </div>
    </div>
    <?php if(1==2){ ?>
    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fa fa-sun-o fa-spin text-white"></i>
            </div>
        </div>
    </div>
    <?php } ?>
</header>

