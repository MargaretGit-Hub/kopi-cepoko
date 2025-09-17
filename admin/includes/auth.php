<?php
function requireAdmin() {
    require_once __DIR__ . '/../../includes/Session.php';
    Session::start();
    
    if (!Session::isLoggedIn() || !Session::isAdmin()) {
        header('Location: ../login.php');
        exit;
    }
}

function redirectIfLoggedIn() {
    require_once __DIR__ . '/../../includes/Session.php';
    Session::start();
    
    if (Session::isLoggedIn() && Session::isAdmin()) {
        header('Location: dashboard.php');
        exit;
    }
}
?>