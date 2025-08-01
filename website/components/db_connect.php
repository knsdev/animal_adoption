<?php

define("DB_HOSTNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", null);
define("DB_NAME", "be25_exam5_animal_adoption_kimschlueter");

function db_connect()
{
  $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if (!$conn) {
    die("Database connection failed!");
  }

  return $conn;
}
