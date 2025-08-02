<?php
session_start();

require_once './components/define.php';
require_once './components/db_connect.php';
require_once './components/util.php';
require_once './components/animals.php';

$conn = db_connect();
$myUserId = get_my_user_id_from_session();
$myUserData = get_user_data($conn, $myUserId);

$layout = "";
$response = get_all_animals();

if ($response['status'] == 200) {
  $rows = $response['data'];

  foreach ($rows as $animal) {
    $animalPictureUrl = $animal['picture'] ? PICTURE_FOLDER_NAME . '/' . $animal['picture'] : ANIMAL_DEFAULT_PICTURE_URL;

    $layout .= "
    <div style='width: fit-content;'>
      <div class='card mb-4' style='max-width: 20rem; min-height: 30rem'>
        <div class='animal-picture-container'>
          <img src='$animalPictureUrl' class='card-img-top animal-image' alt=''>
        </div>
        <div class='card-body d-flex flex-column justify-content-between align-items-center'>
          <div>
            <h5 class='card-title'>{$animal['name']}</h5>
            <p class='card-text'>{$animal['description']}</p>
          </div>
          <div>
            <a href='#' class='btn btn-primary'>Take me home</a>
          </div>
        </div>
      </div>
    </div>
    ";
  }
} else {
  echo "<div class='alert alert-danger' role='alert'>
          {$response['message']}
        </div>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= WEBSITE_TITLE ?> - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/style.css">
  <style>
    .animal-picture-container {
      width: 100%;
      height: 15rem;
      margin: auto;
      overflow: hidden;
    }

    .animal-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>
</head>

<body>
  <?php require_once './components/navbar.php'; ?>
  <div class="container mt-3 mb-5">
    <h1 class="mb-4">Home</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4 justify-content-center justify-content-md-start">
      <?= $layout ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>