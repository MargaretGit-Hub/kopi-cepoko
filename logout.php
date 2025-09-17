<?php
require_once 'includes/Session.php';

Session::start();
Session::logout();
Session::destroy();

header('Location: index.php');
exit;
?>