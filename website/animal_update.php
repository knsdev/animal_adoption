<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit();
}

if (!isset($_GET['id'])) {
  header("location: dashboard.php");
  exit();
}

require_once './components/define.php';
require_once './components/db_connect.php';
require_once './components/util.php';
require_once './components/animals.php';
require_once './components/file_upload.php';

$conn = db_connect();
$myUserId = get_my_user_id_from_session();
$myUserData = get_user_data($conn, $myUserId);

$responseGet = get_animal_by_id($_GET['id']);

if ($responseGet['status'] != 200) {
  header("location: dashboard.php");
  exit();
}

$animalData = $responseGet['data'];

// Apply values from the database if we have not sent a POST request yet.
if (!isset($_POST['update'])) {
  $_POST['name'] = $animalData['name'];
  $_POST['location'] = $animalData['location'];
  $_POST['picture'] = $animalData['picture'];
  $_POST['description'] = $animalData['description'];
  $_POST['size'] = $animalData['size'];
  $_POST['age'] = $animalData['age'];
  $_POST['vaccinated'] = $animalData['vaccinated'];
  $_POST['status'] = $animalData['status'];
  $_POST['breed_id'] = $animalData['breed_id'];
}

if (!empty($animalData['picture'])) {
  $picture = [$animalData['picture'], ImageFileUploadResult::Success];
}

if (isset($_POST['update'])) {
  $error = false;
  $pictureNew = image_file_upload($_FILES['picture'], PICTURE_FOLDER_NAME);

  if (!image_file_upload_is_success($pictureNew[1])) {
    $errorPicture = image_file_get_error_message($pictureNew[1]);
    $error = true;
  } else if ($pictureNew[1] === ImageFileUploadResult::NoFileUploaded) {
    // keep the current picture
  } else {
    $picture = $pictureNew;
    $_POST['picture'] = $picture[0];
  }

  $response = update_animal($_GET['id'], $_POST, $error);

  if ($response['status'] == 200) {
    $resultMessage = "<div class='alert alert-success' role='alert'>
                        {$response['message']}
                      </div>";
  } else {
    image_file_delete($picture, PICTURE_FOLDER_NAME);
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= WEBSITE_TITLE ?> - Update Animal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
  <?php require_once './components/navbar.php'; ?>
  <div class="container mt-3 mb-5">
    <h1>Update Animal</h1>
    <?php
    $submitButtonName = 'update';
    $submitButtonValue = 'Update';
    require_once 'animal_form.php';
    ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>