<?php
$groups = _get_var('groups',[]);
$module = _uri_segment(1);
$sub_module = $module;
if(_uri_segment(2)) {
    $sub_module = $module . '/' . _uri_segment(2);
}
?>
<div class="main-menu">
    <div class="scroll">
        <ul class="list-unstyled">
            <?php foreach (_get_var('menus',[]) as $key=>$group) { ?>
            <?php foreach ($group as $menu) { ?>
            <li<?php echo ($menu['module']==$module || $menu['path']==$module)?' class="active"':''; ?>>
                <?php if($menu['children']){ ?>
                <a href="#<?php echo $menu['module']; ?>">
                <?php }else{ ?>
                <a href="<?php _ebase_url($menu['path']); ?>">
                <?php } ?>
                    <i class="simple-icon-<?php echo $menu['icon']; ?>"></i>
                    <span><?php echo $menu['name']; ?></span>
                </a>
            </li>
            <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="sub-menu">
    <div class="scroll">
        <?php foreach (_get_var('menus',[]) as $key=>$group) { ?>
        <?php foreach ($group as $menu) { ?>
        <?php if($menu['children']){ ?>
        <ul class="list-unstyled" data-link="<?php echo $menu['module']; ?>">
            <?php foreach ($menu['children'] as $child) { ?>
                <li<?php echo ($child['path']==$sub_module)?' class="active"':''; ?>>
                    <a href="<?php echo base_url($child['path']); ?>">
                        <i class="simple-icon-<?php echo $child['icon'] ?>"></i> <span class="d-inline-block"><?php echo $child['name']; ?></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <?php } ?>
        <?php } ?>
        <?php } ?>
    </div>
</div>
<?php if(1==2) { ?>
<div class="sub-menu">
    <div class="scroll">
        <ul class="list-unstyled" data-link="dashboard">
            <li>
                <a href="Dashboard.Default.html">
                    <i class="simple-icon-rocket"></i> <span class="d-inline-block">Default</span>
                </a>
            </li>
            <li>
                <a href="Dashboard.Analytics.html">
                    <i class="simple-icon-pie-chart"></i> <span class="d-inline-block">Analytics</span>
                </a>
            </li>
            <li>
                <a href="Dashboard.Ecommerce.html">
                    <i class="simple-icon-basket-loaded"></i> <span class="d-inline-block">Ecommerce</span>
                </a>
            </li>
            <li>
                <a href="Dashboard.Content.html">
                    <i class="simple-icon-doc"></i> <span class="d-inline-block">Content</span>
                </a>
            </li>
        </ul>

        <ul class="list-unstyled" data-link="layouts">
            <li>
                <a href="Pages.List.html">
                    <i class="simple-icon-credit-card"></i> <span class="d-inline-block">Data List</span>
                </a>
            </li>
            <li>
                <a href="Pages.Thumbs.html">
                    <i class="simple-icon-list"></i> <span class="d-inline-block">Thumb List</span>
                </a>
            </li>
            <li>
                <a href="Pages.Images.html">
                    <i class="simple-icon-grid"></i> <span class="d-inline-block">Image List</span>
                </a>
            </li>
            <li>
                <a href="Pages.Details.html">
                    <i class="simple-icon-book-open"></i> <span class="d-inline-block">Details</span>
                </a>
            </li>
            <li>
                <a href="Pages.Mailing.html">
                    <i class="simple-icon-envelope-open"></i> <span class="d-inline-block">Mailing</span>
                </a>
            </li>
            <li>
                <a href="Pages.Invoice.html">
                    <i class="simple-icon-bag"></i> <span class="d-inline-block">Invoice</span>
                </a>
            </li>
            <li>
                <a href="Pages.Search.html">
                    <i class="simple-icon-magnifier"></i> <span class="d-inline-block">Search</span>
                </a>
            </li>
            <li>
                <a href="Pages.Login.html">
                    <i class="simple-icon-user-following"></i> <span class="d-inline-block">Login</span>
                </a>
            </li>
            <li>
                <a href="Pages.Register.html">
                    <i class="simple-icon-user-follow"></i> <span class="d-inline-block">Register</span>
                </a>
            </li>
            <li>
                <a href="Pages.ForgotPassword.html">
                    <i class="simple-icon-user-unfollow"></i> <span class="d-inline-block">Forgot
                                Password</span>
                </a>
            </li>
            <li>
                <a href="Pages.Error.html">
                    <i class="simple-icon-exclamation"></i> <span class="d-inline-block">Error</span>
                </a>
            </li>
        </ul>
        <ul class="list-unstyled" data-link="applications">
            <li>
                <a href="Apps.MediaLibrary.html">
                    <i class="simple-icon-picture"></i> <span class="d-inline-block">Library</span>
                </a>
            </li>
            <li>
                <a href="Apps.Todo.List.html">
                    <i class="simple-icon-check"></i> <span class="d-inline-block">Todo</span>
                </a>
            </li>
            <li>
                <a href="Apps.Survey.List.html">
                    <i class="simple-icon-calculator"></i> <span class="d-inline-block">Survey</span>
                </a>
            </li>
            <li>
                <a href="Apps.Chat.html">
                    <i class="simple-icon-bubbles"></i> <span class="d-inline-block">Chat</span>
                </a>
            </li>
        </ul>
        <ul class="list-unstyled" data-link="ui">
            <li>
                <a href="Ui.Alerts.html">
                    <i class="simple-icon-bell"></i> <span class="d-inline-block">Alerts</span>
                </a>
            </li>
            <li>
                <a href="Ui.Badges.html">
                    <i class="simple-icon-badge"></i> <span class="d-inline-block">Badges</span>
                </a>
            </li>
            <li>
                <a href="Ui.Buttons.html">
                    <i class="simple-icon-control-play"></i> <span class="d-inline-block">Buttons</span>
                </a>
            </li>
            <li>
                <a href="Ui.Cards.html">
                    <i class="simple-icon-layers"></i> <span class="d-inline-block">Cards</span>
                </a>
            </li>

            <li>
                <a href="Ui.Carousel.html">
                    <i class="simple-icon-picture"></i> <span class="d-inline-block">Carousel</span>
                </a>
            </li>
            <li>
                <a href="Ui.Charts.html">
                    <i class="simple-icon-chart"></i> <span class="d-inline-block">Charts</span>
                </a>
            </li>
            <li>
                <a href="Ui.Collapse.html">
                    <i class="simple-icon-arrow-up"></i> <span class="d-inline-block">Collapse</span>
                </a>
            </li>
            <li>
                <a href="Ui.Dropdowns.html">
                    <i class="simple-icon-arrow-down"></i> <span class="d-inline-block">Dropdowns</span>
                </a>
            </li>
            <li>
                <a href="Ui.Editors.html">
                    <i class="simple-icon-book-open"></i> <span class="d-inline-block">Editors</span>
                </a>
            </li>
            <li>
                <a href="Ui.Forms.html">
                    <i class="simple-icon-check mi-forms"></i> <span class="d-inline-block">Forms</span>
                </a>
            </li>
            <li>
                <a href="Ui.FormComponents.html">
                    <i class="simple-icon-puzzle"></i> <span class="d-inline-block">Form Components</span>
                </a>
            </li>
            <li>
                <a href="Ui.Icons.html">
                    <i class="simple-icon-star"></i> <span class="d-inline-block">Icons</span>
                </a>
            </li>
            <li>
                <a href="Ui.InputGroups.html">
                    <i class="simple-icon-note"></i> <span class="d-inline-block">Input Groups</span>
                </a>
            </li>
            <li>
                <a href="Ui.Jumbotron.html">
                    <i class="simple-icon-screen-desktop"></i> <span class="d-inline-block">Jumbotron</span>
                </a>
            </li>
            <li>
                <a href="Ui.Modal.html">
                    <i class="simple-icon-docs"></i> <span class="d-inline-block">Modal</span>
                </a>
            </li>
            <li>
                <a href="Ui.Navigation.html">
                    <i class="simple-icon-cursor"></i> <span class="d-inline-block">Navigation</span>
                </a>
            </li>

            <li>
                <a href="Ui.PopoverandTooltip.html">
                    <i class="simple-icon-pin"></i> <span class="d-inline-block">Popover & Tooltip</span>
                </a>
            </li>
            <li>
                <a href="Ui.Sortable.html">
                    <i class="simple-icon-shuffle"></i> <span class="d-inline-block">Sortable</span>
                </a>
            </li>
            <li>
                <a href="Ui.Tables.html">
                    <i class="simple-icon-grid"></i> <span class="d-inline-block">Tables</span>
                </a>
            </li>
        </ul>
        <ul class="list-unstyled" data-link="menu">
            <li>
                <a href="Menu.Default.html">
                    <i class="simple-icon-control-pause"></i> <span class="d-inline-block">Default</span>
                </a>
            </li>
            <li>
                <a href="Menu.Subhidden.html">
                    <i class="simple-icon-arrow-left mi-subhidden"></i> <span
                            class="d-inline-block">Subhidden</span>
                </a>
            </li>
            <li>
                <a href="Menu.Hidden.html">
                    <i class="simple-icon-control-start mi-hidden"></i> <span
                            class="d-inline-block">Hidden</span>
                </a>
            </li>
            <li>
                <a href="Menu.Mainhidden.html">
                    <i class="simple-icon-control-rewind mi-hidden"></i> <span
                            class="d-inline-block">Mainhidden</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="navbar-nav-wrap">
    <?php foreach (_get_var('menus',[]) as $key=>$group) { ?>
    <?php if(isset($groups[$key]) && $groups[$key]) { ?>
    <div class="nav-header">
        <span><?php echo $groups[$key]; ?></span>
        <span><?php //echo substr($groups[$key],0,1); ?></span>
    </div>
    <?php } ?>
    <ul class="navbar-nav flex-column">
        <?php foreach ($group as $menu) { ?>
            <li class="nav-item<?php echo ($menu['module']==$module)?' active':''; ?>">
                <a class="nav-link" href="<?php echo base_url($menu['path']); ?>"<?php if($menu['children']) { ?> data-toggle="collapse"<?php echo ($menu['module']==$module)?' aria-expanded="true"':''; ?> data-target="#<?php echo $menu['id']; ?>"<?php } ?>>
                    <span class="feather-icon"><i data-feather="<?php echo $menu['icon']; ?>"></i></span>
                    <span class="nav-link-text"><?php echo $menu['name']; ?></span>
                </a>
                <?php if($menu['children']) { ?>
                    <ul id="<?php echo $menu['id']; ?>" class="nav flex-column collapse collapse-level-1<?php echo ($menu['module']==$module)?' show':''; ?>">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                <?php foreach ($menu['children'] as $child) { ?>
                                <li class="nav-item<?php echo ($child['path']==$sub_module)?' active':''; ?>">
                                    <a class="nav-link" href="<?php echo base_url($child['path']); ?>"><?php echo $child['name']; ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <hr class="nav-separator">
    <?php } ?>
</div>
<?php } ?>
