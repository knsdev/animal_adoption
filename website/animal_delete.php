<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit();
}

require_once './components/define.php';
require_once './components/db_connect.php';
require_once './components/animals.php';

delete_animal($_GET['id']);

header("location: dashboard.php");
