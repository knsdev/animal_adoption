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
  var_dump($_POST);
  var_dump($response);

  if ($response['status'] == 201) {
    echo "<div class='alert alert-success' role='alert'>
          Successfully created new animal!
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

    <form method="POST" enctype="multipart/form-data" style="max-width: 600px">
      <div>
        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" value="<?= $_POST['name'] ?? '' ?>">
        </div>
        <p class="text-danger fw-bold"><?= $response['data']['error_name'] ?? '' ?></p>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="location">Location</label>
          <input type="text" name="location" id="location" class="form-control" value="<?= $_POST['location'] ?? '' ?>">
        </div>
        <p class="text-danger fw-bold"><?= $response['data']['error_location'] ?? '' ?></p>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="picture">Photo</label>
          <img src="<?= isset($picture[0]) ? $picture[0] : '' ?>" alt="" width="200" style="border-radius: 50%">
          <input type="file" name="picture" id="picture" class="form-control">
        </div>
        <p class="text-danger fw-bold"><?= $errorPicture ?? '' ?></p>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="description">Description</label>
          <textarea name="description" id="description" class="form-control" rows="5"><?= $_POST['description'] ?? '' ?></textarea>
        </div>
        <p class="text-danger fw-bold"><?= $response['data']['error_description'] ?? '' ?></p>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="size">Size</label>
          <select name="size" id="size" class="form-select">
            <?php
            $sizes = get_animal_sizes();
            $selectedSize = $_POST['size'] ?? '';

            for ($i = 0; $i < count($sizes); $i++) {
              $sizeValue = $sizes[$i]['value'];
              $sizeName = $sizes[$i]['name'];

              echo "<option value='$sizeValue'";

              if ($sizeValue == $selectedSize) {
                echo " selected";
              }

              echo ">$sizeName</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="age">Age (years)</label>
          <input type="number" name="age" id="age" class="form-control" value="<?= $_POST['age'] ?? '' ?>">
        </div>
        <p class="text-danger fw-bold"><?= $response['data']['error_age'] ?? '' ?></p>

        <div class="form-group d-flex flex-row justify-content-between gap-2 mt-3">
          <label for="vaccinated" class="form-check-label">Vaccinated</label>
          <input type="checkbox" class="form-check-input" name="vaccinated" id="vaccinated" value="<?= $_POST['vaccinated'] ?? '' ?>">
        </div>
        <p class="text-danger fw-bold"><?= $response['data']['error_vaccinated'] ?? '' ?></p>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="status">Status</label>
          <select name="status" id="status" class="form-select">
            <?php
            $stati = get_animal_status_values();
            $selectedSize = $_POST['size'] ?? '';

            for ($i = 0; $i < count($stati); $i++) {
              $statusValue = $stati[$i]['value'];
              $statusName = $stati[$i]['name'];

              echo "<option value='$statusValue'";

              if ($statusValue == $selectedSize) {
                echo " selected";
              }

              echo ">$statusName</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group d-flex flex-column gap-2 mt-3">
          <label for="breed_id">Breed</label>
          <select name="breed_id" id="breed_id" class="form-select">
            <?php
            $breeds = get_animal_breeds();
            $selectedBreedId = $_POST['breed_id'] ?? '';

            for ($i = 0; $i < count($breeds); $i++) {
              $breedId = $breeds[$i]['id'];
              $breedName = $breeds[$i]['name'];

              echo "<option value='$breedId'";

              if ($breedId == $selectedBreedId) {
                echo " selected";
              }

              echo ">$breedName</option>";
            }
            ?>
          </select>
        </div>

        <div class="mt-3">
          <input type="submit" name="create" value="Create" class="btn btn-primary">
        </div>
      </div>
    </form>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>