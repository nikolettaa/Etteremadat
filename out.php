<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

  if (isset($_SESSION['user_id'])) {
    $_SESSION = array();
    session_destroy();
  }
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/enter.php';
  header('Location: ' . $home_url);
?>