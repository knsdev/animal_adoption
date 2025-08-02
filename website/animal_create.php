<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
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

if (isset($_POST['create'])) {
  $error = false;
  $picture = image_file_upload($_FILES['picture'], PICTURE_FOLDER_NAME);
  $_POST['picture'] = $picture[0];

  if (!image_file_upload_is_success($picture[1])) {
    $errorPicture = image_file_get_error_message($picture[1]);
    $error = true;
  }

  $response = create_animal($_POST, $error);

  if ($response['status'] == 201) {
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
  <title><?= WEBSITE_TITLE ?> - Create Animal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
  <?php require_once './components/navbar.php'; ?>
  <div class="container mt-3 mb-5">
    <h1>Create Animal</h1>
    <?php
    $submitButtonName = 'create';
    $submitButtonValue = 'Create';
    require_once 'animal_form.php';
    ?>
    <?php create_back_button("dashboard.php"); ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>