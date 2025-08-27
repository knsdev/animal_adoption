<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit();
}

require_once __DIR__ . './components/define.php';
require_once __DIR__ . './components/db_connect.php';
require_once __DIR__ . './components/animals.php';

delete_animal($_GET['id']);

header("location: dashboard.php");
