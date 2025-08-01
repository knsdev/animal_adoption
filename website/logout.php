<?php
session_start();

unset($_SESSION["admin"]);
unset($_SESSION["user"]);

session_unset();
session_destroy();

header("location: login.php");
