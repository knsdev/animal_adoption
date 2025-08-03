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

function fetch_animals_internal($sql)
{
  global $conn;

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal Server Error: Failed to fetch animals");
  }

  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  return create_response("200", "Successfully fetched animals.", $rows);
}

function get_all_animals()
{
  return fetch_animals_internal("SELECT * FROM `animal`");
}

function get_animals_available()
{
  return fetch_animals_internal("SELECT * FROM `animal` WHERE `status`='available'");
}

function get_animals_age_greater($minAge)
{
  return fetch_animals_internal("SELECT * FROM `animal` WHERE age > $minAge");
}

function get_animals_adopted_by_user($userId)
{
  return fetch_animals_internal(
    "SELECT a.id, a.name, a.picture, a.location, a.description, a.size, a.age, a.vaccinated, a.status, a.breed_id, adopt.adoption_date
     FROM animal AS a
     INNER JOIN pet_adoption AS adopt ON a.id = adopt.pet_id
     WHERE adopt.user_id = $userId"
  );
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

function validate_total_input($conn, $data, &$res, &$error)
{
  validate_input_name($data['name'], "error_name", "Name", ANIMAL_NAME_MIN_LENGTH, ANIMAL_NAME_MAX_LENGTH, $res, $error);
  validate_input_name($data['location'], "error_location", "Location", ANIMAL_LOCATION_MIN_LENGTH, ANIMAL_LOCATION_MAX_LENGTH, $res, $error);

  $breed_id = $data['breed_id'];

  if ($breed_id < 0) {
    $res["error_breed_id"] = "You have to select a breed.";
  } else if (validate_input_int($breed_id, "error_breed_id", "Breed", $res, $error)) {
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
  // is_numeric(age)
  // age >= 0
}

function create_animal(&$data, $error)
{
  global $conn;

  if ($response = require_admin())
    return $response;

  $data['name'] = get_clean_input($data, 'name');
  $data['location'] = get_clean_input($data, 'location');
  $data['picture'] = get_clean_input($data, 'picture');
  $data['description'] = get_clean_input($data, 'description');
  $data['size'] = get_clean_input($data, 'size');
  $data['age'] = get_clean_input($data, 'age');
  $data['vaccinated'] = get_clean_input($data, 'vaccinated');
  $data['status'] = get_clean_input($data, 'status');
  $data['breed_id'] = get_clean_input($data, 'breed_id');

  $res = [];
  validate_total_input($conn, $data, $res, $error);

  if ($error) {
    return create_response("400", "Invalid input.", $res);
  } else {
    $sql = "INSERT INTO `animal` (`name`, `picture`, `location`, `description`, `size`, `age`, `vaccinated`, `status`, `breed_id`)
          VALUES (
          '{$data['name']}',
          '{$data['picture']}',
          '{$data['location']}',
          '{$data['description']}',
          '{$data['size']}',
          '{$data['age']}',
          '{$data['vaccinated']}',
          '{$data['status']}',
          '{$data['breed_id']}'
          )";

    $result = mysqli_query($conn, $sql);

    if ($result) {
      return create_response("201", "Successfully created new animal.");
    } else {
      return create_response("500", "Internal Server Error: Failed to create new animal.");
    }
  }
}

function update_animal($id, &$data, $error)
{
  global $conn;

  if ($response = require_admin())
    return $response;

  if (!isset($id)) {
    return create_response("400", "Invalid input: No id set!");
  }

  $data['name'] = get_clean_input($data, 'name');
  $data['location'] = get_clean_input($data, 'location');
  $data['picture'] = get_clean_input($data, 'picture');
  $data['description'] = get_clean_input($data, 'description');
  $data['size'] = get_clean_input($data, 'size');
  $data['age'] = get_clean_input($data, 'age');
  $data['vaccinated'] = get_clean_input($data, 'vaccinated');
  $data['status'] = get_clean_input($data, 'status');
  $data['breed_id'] = get_clean_input($data, 'breed_id');

  $res = [];
  validate_total_input($conn, $data, $res, $error);

  if ($error) {
    return create_response("400", "Invalid input.", $res);
  } else {
    $sql = "UPDATE `animal` SET
           `name`='{$data['name']}',
           `picture`='{$data['picture']}',
           `location`='{$data['location']}',
           `description`='{$data['description']}',
           `size`='{$data['size']}',
           `age`='{$data['age']}',
           `vaccinated`='{$data['vaccinated']}',
           `status`='{$data['status']}',
           `breed_id`='{$data['breed_id']}'
           WHERE id=$id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
      return create_response("200", "Updated animal successfully.");
    } else {
      return create_response("500", "Internal Server Error: Failed to update the animal.");
    }
  }
}

function delete_animal($id)
{
  global $conn;

  if ($response = require_admin())
    return $response;

  if (!isset($id)) {
    return create_response("400", "Invalid input: No id set!");
  }

  $id = clean_input($id);

  $sql = "DELETE FROM `pet_adoption` WHERE pet_id=$id";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Failed to delete animal.");
  }

  $sql = "DELETE FROM `animal` WHERE id=$id";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Failed to delete animal.");
  }

  return create_response("200", "Animal deleted successfully.");
}

function adopt_animal($animalId, $userId)
{
  global $conn;

  if (!isset($userId))
    return create_response("400", "Invalid input: No user ID set!");

  if (!isset($animalId))
    return create_response("400", "Invalid input: No animal ID set!");

  $sql = "SELECT * FROM `pet_adoption` WHERE `user_id`=$userId AND `pet_id`=$animalId";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal server error: Failed to adopt animal.");
  } else if (mysqli_num_rows($result) > 0) {
    return create_response("406", "You have adopted this animal already.");
  }

  $sql = "SELECT * FROM `pet_adoption` WHERE `pet_id`=$animalId";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal server error: Failed to adopt animal.");
  } else if (mysqli_num_rows($result) > 0) {
    return create_response("406", "Animal has already been adopted by someone.");
  }

  $sql = "INSERT INTO `pet_adoption`(`user_id`, `pet_id`) VALUES ('$userId','$animalId')";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal server error: Failed to adopt animal.");
  }

  $sql = "UPDATE `animal` SET `status`='adopted' WHERE id=$animalId";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return create_response("500", "Internal server error: Failed to adopt animal.");
  }

  return create_response("201", "Animal adopted successfully.");
}

function get_animal_sizes()
{
  return [
    ["value" => "small", "name" => "Small"],
    ["value" => "default", "name" => "Default"],
    ["value" => "big", "name" => "Big"],
  ];
}

function get_animal_status_values()
{
  return [
    ["value" => "available", "name" => "Available"],
    ["value" => "adopted", "name" => "Adopted"],
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

function get_breed_by_id($breed_id)
{
  global $conn;

  $sql = "SELECT * FROM `breed` WHERE id=$breed_id";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    return null;
  }

  $row = mysqli_fetch_assoc($result);
  return $row;
}

function get_animal_picture_url($animalData)
{
  return $animalData['picture'] ? PICTURE_FOLDER_NAME . '/' . $animalData['picture'] : ANIMAL_DEFAULT_PICTURE_URL;
}
