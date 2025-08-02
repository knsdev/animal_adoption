<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/define.php';
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/util.php';

$isAdmin = isset($_SESSION['admin']);
$conn = db_connect();

function create_response($status, $message, $data = [])
{
  http_response_code($status);

  $response = [
    "status" => $status,
    "message" => $message,
    "data" => $data
  ];

  // echo json_encode($response);
  // exit();

  return $response;
}

function require_admin()
{
  if (!isset($_SESSION['admin'])) {
    return create_response(401, "You are not an admin!");
  }

  return null;
}

// function get_input_data()
// {
//   return json_decode(file_get_contents("php://input"), true);
// }

function get_clean_input($data, $key, $default = null)
{
  return isset($data[$key]) ? clean_input($data[$key]) : $default;
}

function get_all_animals()
{
  global $conn;

  $sql = "SELECT * FROM `animal`";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal Server Error: Failed to fetch all animals");
  }

  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  return create_response("200", "Successfully fetched all animals.", $rows);
}

function get_animal_by_id($id)
{
  global $conn;

  $id = clean_input($id);

  $sql = "SELECT * FROM `animal` WHERE id=$id";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal Server Error: Failed to fetch all animals");
  }

  if (mysqli_num_rows($result) != 1) {
    return create_response("404", "Animal not found.");
  }

  $row = mysqli_fetch_assoc($result);
  return create_response("200", "Successfully fetched animal by id.", $row);
}

function validate_input_name($value, $nameForErrorMessage, $nameForMessage, $minCharacters, $maxCharacters, &$res, &$error)
{
  if (empty($value)) {
    $res[$nameForErrorMessage] = "$nameForMessage cannot be empty.";
    $error = true;
    return false;
  } else if (strlen($value) < $minCharacters) {
    $res[$nameForErrorMessage] = "$nameForMessage must be at least " . $minCharacters . " characters long.";
    $error = true;
    return false;
  } else if (strlen($value) > $maxCharacters) {
    $res[$nameForErrorMessage] = "$nameForMessage is too long. Maximum is " . $maxCharacters . " characters.";
    $error = true;
    return false;
  }

  return true;
}

function validate_input_int($value, $nameForErrorMessage, $nameForMessage, &$res, &$error)
{
  if (empty($value)) {
    $res[$nameForErrorMessage] = "$nameForMessage cannot be empty.";
    $error = true;
    return false;
  } else if (filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) === null) {
    $res[$nameForErrorMessage] = "$nameForMessage is invalid.";
    $error = true;
    return false;
  }

  return true;
}

function validate_total_input($conn, $name, $location, $breed_id, &$res, &$error)
{
  validate_input_name($name, "error_name", "Name", ANIMAL_NAME_MIN_LENGTH, ANIMAL_NAME_MAX_LENGTH, $res, $error);
  validate_input_name($location, "error_location", "Location", ANIMAL_LOCATION_MIN_LENGTH, ANIMAL_LOCATION_MAX_LENGTH, $res, $error);

  if (validate_input_int($breed_id, "error_breed_id", "Breed", $res, $error)) {
    $sql = "SELECT * FROM `breed` WHERE id=$breed_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
      return create_response("500", "Failed to get breed by id");
    }

    if (mysqli_num_rows($result) != 1) {
      $res["error_breed_id"] = "Breed not found.";
    }
  }

  // TODO: Validate other fields
}

function create_animal($data, $error)
{
  global $conn;

  if ($response = require_admin())
    return $response;

  // $data = get_input_data();
  $name = get_clean_input($data, 'name');
  $location = get_clean_input($data, 'location');
  $picture = get_clean_input($data, 'picture');
  $description = get_clean_input($data, 'description');
  $size = get_clean_input($data, 'size');
  $age = get_clean_input($data, 'age');
  $vaccinated = get_clean_input($data, 'vaccinated');
  $status = get_clean_input($data, 'status');
  $breed_id = get_clean_input($data, 'breed_id');

  $_POST['name'] = $name;
  $_POST['location'] = $location;
  $_POST['picture'] = $picture;
  $_POST['description'] = $description;
  $_POST['size'] = $size;
  $_POST['age'] = $age;
  $_POST['vaccinated'] = $vaccinated;
  $_POST['status'] = $status;
  $_POST['breed_id'] = $breed_id;

  $res = [];
  validate_total_input($conn, $name, $location, $breed_id, $res, $error);

  if ($error) {
    return create_response("400", "Invalid input.", $res);
  } else {
    $sql = "INSERT INTO `animal` (`name`, `picture`, `location`, `description`, `size`, `age`, `vaccinated`, `status`, `breed_id`)
          VALUES (
          '$name',
          '$picture',
          '$location',
          '$description',
          '$size',
          '$age',
          '$vaccinated',
          '$status',
          '$breed_id'
          )";

    $result = mysqli_query($conn, $sql);

    if ($result) {
      return create_response("201", "Successfully created new animal.");
    } else {
      return create_response("500", "Internal Server Error: Failed to create new animal.");
    }
  }
}

function update_animal($id, $data)
{
  global $conn;

  if ($response = require_admin())
    return $response;

  if (!isset($id)) {
    return create_response("400", "Invalid input: No id set!");
  }

  // $data = get_input_data();
}

function delete_animal($id)
{
  global $conn;

  require_admin();

  if (!isset($id)) {
    return create_response("400", "Invalid input: No id set!");
  }
}

function get_animal_sizes()
{
  return [
    ["value" => "small", "name" => "Small"],
    ["value" => "default", "name" => "Default"],
    ["value" => "big", "name" => "Big"],
  ];
}

function get_animal_breeds()
{
  global $conn;

  $sql = "SELECT * FROM `breed`";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return null;
  }

  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  return $rows;
}
