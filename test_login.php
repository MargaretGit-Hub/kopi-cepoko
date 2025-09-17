<?php
// Simple test to check admin login
require_once 'app/Models/User.php';
require_once 'includes/Session.php';

Session::start();

$userModel = new User();
$user = $userModel->authenticate('admin@kopicepoko.com', 'admin123');

if ($user) {
    echo "Login successful!\n";
    echo "User: " . $user['name'] . "\n";
    echo "Role: " . $user['role'] . "\n";
} else {
    echo "Login failed!\n";
}
?>