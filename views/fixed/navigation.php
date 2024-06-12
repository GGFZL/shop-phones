<header class="container custom-header mt-3 mb-3">
    <div class="row align-items-center h-100 justify-content-between" id="coverForAll">
        <div class="col-lg-3 col-md-2 col-sm-2 h-100 w-100 d-flex align-items-center mb-3" id="omot">
            <a href="index.php" class="h-75">
                <img src="assets/images/phoneSPhere.png" alt="Logo" class="img-fluid imgLogo">
            </a>
            <a href="index.php" class="ml-2 fs-2 bold-link nameSignalSphere">SignalSphere</a>
        </div>
        <div class="col-md-7 col-sm-4 col-lg-5 w-100 text-center h-100 mb-2" id="coverForLinks">
            <nav>
                <ul class="list-inline d-flex justify-content-around">
                    <?php
                    $adminLinks = [];
                    foreach ($menuItems as $menuItem) {
                        $isAdmin = isset($_SESSION['logged_in']) && $_SESSION['role_id'] == 1;

                        if ($isAdmin && ($menuItem->title == 'Contact' || $menuItem->title == 'Author' || $menuItem->title == 'Shop Phones')) {
                            continue;
                        }
                        $url = $menuItem->url == 'index.php' ? $menuItem->url : "index.php?page=" . basename($menuItem->url, ".php");
                        if ($menuItem->title == 'Dashboard' || $menuItem->title == 'AdminPanel' || $menuItem->title == 'Surveys' || $menuItem->title == 'PageAccess' || $menuItem->title == 'DailyLogins') {
                            $adminLinks[] = '<li class="list-inline-item"><a href="' . $url . '">' . $menuItem->title . '</a></li>';
                        } else {
                            echo '<li class="list-inline-item"><a href="' . $url . '">' . $menuItem->title . '</a></li>';
                        }
                    }
                    if ($isAdmin) {
                        echo implode('', $adminLinks);
                    }
                    ?>
                </ul>
            </nav>
        </div>
        <div class="row mb-3 col-md-3 col-lg-3 col-sm-6 w-100 text-right d-flex justify-content-end mr-2" id="coverForButtonLinks">
            <?php if (isset($_SESSION['logged_in'])): ?>
                <a href="index.php?page=logout" id="logout" class="btn buttons font-weight-bold">Logout</a>
            <?php else: ?>
                <a href="index.php?page=login" class="btn buttons w-40 font-weight-bold mr-3">Sign In</a>
                <a href="index.php?page=register" class="btn buttons w-40 font-weight-bold">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
