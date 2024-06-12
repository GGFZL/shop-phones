<?php
session_start();
include("config/connection.php");
include("models/functions.php");
include("models/log.php");

$menuItems = getAll('menu');

include "views/fixed/header.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
if(isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
    $user = "Anonymous";
}
$timestamp = date('Y-m-d H:i:s');
logAccess("$timestamp - $user accessed $page");

include "views/fixed/navigation.php";

switch ($page) {
    case 'home':
        include "views/pages/home.php";
        break;
    case 'admin':
        include "views/pages/admin.php";
        break;
    case 'author':
        include "views/pages/author.php";
        break;
    case 'contact':
        include "views/pages/contact.php";
        break;
    case 'dashboard':
        include "views/pages/dashboard.php";
        break;
    case 'login':
        include "views/pages/loginForm.php";
        break;
    case 'logout':
        include "views/pages/logout.php";
        break;
    case 'register':
        include "views/pages/registerForm.php";
        break;
    case 'shop':
        include "views/pages/shop.php";
        break;
    case 'survey':
        include "views/pages/survey.php";
        break;
    case 'pageaccess':
        include "views/pages/pageaccess.php";
        break;
    case 'dailyLogin':
        include "views/pages/dailyLogin.php";
        break;
    default:
        include "views/pages/404.php";
        break;
}

include "views/fixed/footer.php";
?>
