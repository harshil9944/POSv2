<nav class="navbar fixed-top">
    <div class="d-flex align-items-center navbar-left">
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1" />
                <rect x="0.48" y="7.5" width="7" height="1" />
                <rect x="0.48" y="15.5" width="7" height="1" />
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1" />
                <rect x="1.56" y="7.5" width="16" height="1" />
                <rect x="1.56" y="15.5" width="16" height="1" />
            </svg>
        </a>

        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1" />
                <rect x="0.5" y="7.5" width="25" height="1" />
                <rect x="0.5" y="15.5" width="25" height="1" />
            </svg>
        </a>
        <?php if(1==2) { ?>
        <div class="search" data-search-path="Pages.Search.html?q=">
            <input placeholder="Search...">
            <span class="search-icon">
                    <i class="simple-icon-magnifier"></i>
                </span>
        </div>
        <?php } ?>
    </div>


    <a class="navbar-logo" href="<?php _ebase_url('dashboard'); ?>>">
        <span class="logo d-none d-xs-block"></span>
        <span class="logo-mobile d-block d-xs-none"></span>
    </a>

    <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="d-none d-md-inline-block align-text-bottom mr-3">
                <div class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1"
                     data-toggle="tooltip" data-placement="left" title="Dark Mode">
                    <input class="custom-switch-input" id="switchDark" type="checkbox" checked>
                    <label class="custom-switch-btn" for="switchDark"></label>
                </div>
            </div>

            <div class="position-relative d-none d-sm-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-grid"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3  position-absolute" id="iconMenuDropdown">
                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-equalizer d-block"></i>
                        <span>Settings</span>
                    </a>

                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-male-female d-block"></i>
                        <span>Users</span>
                    </a>

                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-puzzle d-block"></i>
                        <span>Components</span>
                    </a>

                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-bar-chart-4 d-block"></i>
                        <span>Profits</span>
                    </a>

                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-file d-block"></i>
                        <span>Surveys</span>
                    </a>

                    <a href="#" class="icon-menu-item">
                        <i class="iconsminds-suitcase d-block"></i>
                        <span>Tasks</span>
                    </a>

                </div>
            </div>

            <div class="position-relative d-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="notificationButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-bell"></i>
                    <span class="count">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute"
                     id="notificationDropdown">
                    <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                        <a href="#">
                            <img src="assets/img/profile-pic-l.jpg" alt="Notification Image"
                                 class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle" />
                        </a>
                        <div class="pl-3">
                            <a href="#">
                                <p class="font-weight-medium mb-1">Joisse Kaycee just sent a new comment!</p>
                                <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                        <a href="#">
                            <img src="assets/img/profile-pic-l.jpg" alt="Notification Image"
                                 class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle" />
                        </a>
                        <div class="pl-3">
                            <a href="#">
                                <p class="font-weight-medium mb-1">1 item is out of stock!</p>
                                <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                        <a href="#">
                            <img src="assets/img/profile-pic-l.jpg" alt="Notification Image"
                                 class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle" />
                        </a>
                        <div class="pl-3">
                            <a href="#">
                                <p class="font-weight-medium mb-1">New order received! It is total $147,20.</p>
                                <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-row mb-3 pb-3 ">
                        <a href="#">
                            <img src="assets/img/profile-pic-l.jpg" alt="Notification Image"
                                 class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle" />
                        </a>
                        <div class="pl-3">
                            <a href="#">
                                <p class="font-weight-medium mb-1">3 items just added to wish list by a user!</p>
                                <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                <i class="simple-icon-size-fullscreen"></i>
                <i class="simple-icon-size-actual"></i>
            </button>

        </div>

        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                <span class="name"><?php echo _get_session('name'); ?></span>

                <?php if(1==1) { ?>
                    <svg id="user-icon-svg" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 225 212.03"><title>images</title><path class="user-icon" d="M296.21,290.62a64.36,64.36,0,0,1,72.45,51.75,63.24,63.24,0,0,1-5.77,40.89,64.7,64.7,0,0,1-42.83,33.67,63.29,63.29,0,0,1-37.7-2.64,64.93,64.93,0,0,1-34.87-32.2,64.17,64.17,0,0,1-1.24-53,64.86,64.86,0,0,1,50-38.45ZM199.9,466.22A114.59,114.59,0,0,1,253,406.9a74.41,74.41,0,0,0,105,0,114.83,114.83,0,0,1,46.71,45.93c7,12.53,11.18,26.47,13.26,40.64v1.22c-.69,4.76-5.41,7.8-10,7.29q-103,0-205.94,0a8.7,8.7,0,0,1-9-7.34v-1.26a115.37,115.37,0,0,1,6.9-27.13Z" transform="translate(-193 -290)"/></svg>
                <?php }else{ ?>
                    <span><i class="iconsminds-user"></i></span>
                <?php } ?>
            </button>

            <div class="dropdown-menu dropdown-menu-right mt-3">
                <?php if(1==2) { ?>
                <a class="dropdown-item" href="#">Account</a>
                <a class="dropdown-item" href="#">Features</a>
                <a class="dropdown-item" href="#">History</a>
                <a class="dropdown-item" href="#">Support</a>
                <?php } ?>
                <a class="dropdown-item" href="<?php _ebase_url(LOGOUT_ROUTE) ?>">Sign out</a>
            </div>
        </div>
    </div>
</nav>
