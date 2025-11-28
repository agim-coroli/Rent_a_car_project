<?php

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 1) {
        require_once PATH . "/src/view/admins/dashboard.php";
    } elseif ($_SESSION['role'] == 0) {
        require_once PATH . "/src/view/users/dashboard.php";
    } else {
        require_once PATH . "/src/view/guests/dashboard.php";
    }
} else {
    require_once PATH . "/src/view/guests/dashboard.php";
}
