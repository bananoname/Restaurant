<?php

session_start();
function isAuthenticated() {
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
      header("Location: login.php");
      die();
  }
}
