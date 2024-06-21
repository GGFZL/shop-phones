<?php if (isset($_SESSION['logged_in']) && $_SESSION['role_id'] == 1): ?>
<div id="adminSidebar">
    <nav>
        <ul class="list-group">
            <?php
            foreach ($menuItems as $menuItem) {
                $url = $menuItem->url == 'index.php' ? $menuItem->url : "index.php?page=" . basename($menuItem->url, ".php");
                $isActive = isset($_GET['page']) && $_GET['page'] == basename($menuItem->url, ".php") ? 'active' : '';
                if (in_array($menuItem->title, ['Dashboard', 'AdminPanel', 'Surveys', 'PageAccess', 'DailyLogins'])) {
                    echo '<li class="list-group-item ' . $isActive . '"><a href="' . $url . '">' . $menuItem->title . '</a></li>';
                }
            }
            ?>
            <li class="list-group-item"><a href="index.php?page=logout" id="logout" class="btn buttons font-weight-bold">Logout</a></li>
        </ul>
    </nav>
</div>
<?php endif; ?>
