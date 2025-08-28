<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box mt-3">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo.png" alt="" height="52">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo.png" alt="" height="60">
                    </span>
                </a>
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo.png" alt="" height="52">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo.png" alt="" height="60">
                    </span>
                </a>
            </div>

            <!-- Responsive Menu Toggle -->
            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex mt-20">
            <!-- Search -->
            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown">
                    <i class="uil-search"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <form class="p-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search ...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Fullscreen -->
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="uil-minus-path"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="dropdown d-inline-block">
                <button class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown">
                    <i class="uil-bell"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <div class="p-3">
                        <div class="d-flex justify-content-between">
                            <h5 class="m-0 font-size-16">Notifications</h5>
                            <a href="#" class="small">Mark all as read</a>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <!-- Dynamic notifications can be loaded here -->
                    </div>
                    <div class="p-2 border-top text-center">
                        <a href="#" class="btn btn-sm btn-link font-size-14">
                            <i class="uil-arrow-circle-right me-1"></i> View More..
                        </a>
                    </div>
                </div>
            </div>

            <!-- User -->
            <div class="dropdown d-inline-block">
                <button class="btn header-item waves-effect" data-bs-toggle="dropdown">
                    <?php
                    $user = new User($_SESSION['id']);
                    $profileImage = !empty($user->image_name) ? 'upload/users/' . $user->image_name : 'assets/images/users/avatar-4.jpg';
                    ?>
                    <img class="rounded-circle header-profile-user" src="<?php echo $profileImage; ?>" alt="<?php echo htmlspecialchars($user->name); ?>">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15"><?php echo htmlspecialchars($user->name); ?></span>
                    <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="profile.php"><i class="uil uil-user-circle me-1"></i> View Profile</a>
                    <a class="dropdown-item" href="#"><i class="uil uil-lock-alt me-1"></i> Settings </a>
                    <a class="dropdown-item" href="log-out.php"><i class="uil uil-sign-out-alt me-1"></i> Sign out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="container-fluid">
        <div class="topnav">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                <div class="collapse navbar-collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">
                        <?php
                        $PAGE_CATEGORY = new PageCategory(NULL);
                        $USER_PERMISSION = new UserPermission();
                        $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

                        foreach ($PAGE_CATEGORY->getActiveCategory() as $category):
                            $hasCategoryAccess = false;
                            $categoryPages = [];

                            // Get all pages for this category first to check permissions
                            if ($category['id'] != 1) { // Skip dashboard for now
                                $PAGES = new Pages(null);
                                $categoryPages = $PAGES->getPagesByCategory($category['id']);

                                // Check if user has any permission for any page in this category
                                foreach ($categoryPages as $page) {
                                    $permissions = $USER_PERMISSION->hasPermission($user_id, $page['id']);
                                    if (in_array(true, $permissions, true)) {
                                        $hasCategoryAccess = true;
                                        break;
                                    }
                                }
                            }

                            // Skip category if user has no permissions for any page in it
                            if (!$hasCategoryAccess && $category['id'] != 1) {
                                continue;
                            }

                            if ($category['id'] == 1): // Dashboard
                                $dashboardPage = (new Pages(null))->getPagesByCategory($category['id'])[0] ?? null;
                                if ($dashboardPage):
                                    $permissions = $USER_PERMISSION->hasPermission($user_id, $dashboardPage['id']);
                                    if (in_array(true, $permissions, true)): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="<?php echo $dashboardPage['page_url'] . '?page_id=' . $dashboardPage['id']; ?>" target="_blank">
                                                <i class="<?php echo $category['icon']; ?> me-2"></i> <?php echo $category['name']; ?>
                                            </a>
                                        </li>
                                    <?php
                                    endif;
                                endif;
                            elseif ($category['id'] == 4): // Reports Category
                                $hasReportAccess = false;
                                $reportSubmenus = [];
                                $DEFAULT_DATA = new DefaultData();

                                // First check if user has any report access
                                foreach ($DEFAULT_DATA->pagesSubCategory() as $key => $subCategoryTitle) {
                                    $PAGES = new Pages(null);
                                    $subPages = $PAGES->getPagesBySubCategory($key);

                                    foreach ($subPages as $page) {
                                        $permissions = $USER_PERMISSION->hasPermission($user_id, $page['id']);
                                        if (in_array(true, $permissions, true)) {
                                            $hasReportAccess = true;
                                            if (!isset($reportSubmenus[$key])) {
                                                $reportSubmenus[$key] = [
                                                    'title' => $subCategoryTitle,
                                                    'pages' => []
                                                ];
                                            }
                                            $reportSubmenus[$key]['pages'][] = $page;
                                        }
                                    }
                                }

                                if ($hasReportAccess): ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" role="button">
                                            <i class="uil-layers me-2"></i> Reports <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu">
                                            <?php foreach ($reportSubmenus as $key => $submenu):
                                                if (!empty($submenu['pages'])): ?>
                                                    <div class="dropdown">
                                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#">
                                                            <?php echo $submenu['title']; ?>
                                                            <div class="arrow-down"></div>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <?php foreach ($submenu['pages'] as $page):
                                                                $permissions = $USER_PERMISSION->hasPermission($user_id, $page['id']);
                                                                if (in_array(true, $permissions, true)): ?>
                                                                    <a class="dropdown-item"
                                                                        href="<?php echo $page['page_url'] . '?page_id=' . $page['id']; ?>"
                                                                        target="_blank">
                                                                        - <?php echo $page['page_name']; ?>
                                                                    </a>
                                                            <?php endif;
                                                            endforeach; ?>
                                                        </div>
                                                    </div>
                                            <?php endif;
                                            endforeach; ?>
                                        </div>
                                    </li>
                                <?php
                                endif;
                            else: // Other Categories
                                $hasAnyPermission = false;
                                $visiblePages = [];

                                // Filter pages to only those the user has permission for
                                foreach ($categoryPages as $page) {
                                    // Always allow access to profile.php for logged-in users
                                    if (basename($page['page_url']) === 'profile.php') {
                                        $visiblePages[] = $page;
                                        $hasAnyPermission = true;
                                        continue;
                                    }

                                    // Check permissions for other pages
                                    $permissions = $USER_PERMISSION->hasPermission($user_id, $page['id']);
                                    if (in_array(true, $permissions, true)) {
                                        $visiblePages[] = $page;
                                        $hasAnyPermission = true;
                                    }
                                }

                                if ($hasAnyPermission): ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" role="button">
                                            <i class="<?php echo $category['icon']; ?> me-2"></i> <?php echo $category['name']; ?>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl">
                                            <div class="row">
                                                <?php foreach ($visiblePages as $page):
                                                    $permissions = $USER_PERMISSION->hasPermission($user_id, $page['id']);
                                                    if (in_array(true, $permissions, true)): ?>
                                                        <div class="col-lg-3">
                                                            <a class="dropdown-item"
                                                                href="<?php echo $page['page_url'] . '?page_id=' . $page['id']; ?>"
                                                                target="_blank">
                                                                - <?php echo $page['page_name']; ?>
                                                            </a>
                                                        </div>
                                                <?php endif;
                                                endforeach; ?>
                                            </div>
                                        </div>
                                    </li>
                        <?php
                                endif;
                            endif;
                        endforeach; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>