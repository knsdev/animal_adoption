<?php
session_start();

if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
  header("location: login.php");
  exit();
}

$homeUrl = isset($_SESSION['admin']) ? "dashboard.php" : "home.php";

if (!isset($_GET['id'])) {
  header("location: $homeUrl");
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
  header("location: $homeUrl");
  exit();
}

$animal = $responseGet['data'];
$animalPictureUrl = get_animal_picture_url($animal);
$vaccinatedText = ($animal['vaccinated']) ? 'Yes' : 'No';
$breedData = get_breed_by_id($animal['breed_id']);

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
  <div class="container mt-3 mb-5" style="flex-grow: 1;">
    <?php
    $layout = "<div class='d-flex justify-content-center' style='width: 100%'>
            <div class='card shadow mb-4 d-flex flex-column justify-content-between align-items-center' style='width: 60%'>
            
            <div>
              <div class='animal-picture-container'>
                <img src='$animalPictureUrl' class='card-img-top animal-image' alt='animal'>
              </div>

              <div class='card-body'>
                <h5 class='card-title'>{$animal['name']}</h5>
                <p class='card-text'>{$animal['description']}</p>
              </div>
            </div>

            <div style='width: 100%'>
              <ul class='list-group list-group-flush'>
                <li class='list-group-item'></li>
                <li class='list-group-item'>Location: {$animal['location']}</li>
                <li class='list-group-item'>Breed: {$breedData['name']}</li>
                <li class='list-group-item'>Size: {$animal['size']}</li>
                <li class='list-group-item'>Age: {$animal['age']} years</li>
                <li class='list-group-item'>Vaccinated: $vaccinatedText</li>
                <li class='list-group-item'>Status: {$animal['status']}</li>
                <li class='list-group-item'></li>
              </ul>";

    $layout .= "<div class='d-flex flex-row gap-1 justify-content-around mb-3' style='width:100%'>";

    if (isset($_SESSION['user'])) {
      $layout .= "<form method='POST'>
                      <input type='hidden' name='animal_id_to_adopt' value='{$animal['id']}' />
                      <input" . (($animal['status'] != 'available') ? " disabled" : "") . " type='submit' name='adopt_animal' class='btn btn-success' value='Take me home' />
                  </form>";
    }

    $layout .= "</div>";

    $layout .= "
            </div>

          </div>
        </div>";

    echo $layout;
    ?>

    <?php create_back_button($homeUrl); ?>
  </div>
  <?php require_once './components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>