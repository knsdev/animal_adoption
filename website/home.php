<?php
session_start();

if (isset($_SESSION['admin'])) {
  header("location: dashboard.php");
  exit();
}

if (!isset($_SESSION['user'])) {
  header("location: login.php");
  exit();
}

require_once __DIR__ . './components/define.php';
require_once __DIR__ . './components/db_connect.php';
require_once __DIR__ . './components/util.php';
require_once __DIR__ . './components/animals.php';
require_once __DIR__ . './components/card_layout.php';

$conn = db_connect();
$myUserId = get_my_user_id_from_session();
$myUserData = get_user_data($conn, $myUserId);
$layout = "";

if (isset($_POST['adopt_animal'])) {
  $adoptResponse = adopt_animal($_POST['animal_id_to_adopt'], $_SESSION['user']);

  if ($adoptResponse['status'] == 201) {
    $resultMessage = "<div class='alert alert-success' role='alert'>
                        {$adoptResponse['message']}
                      </div>";
  } else {
    $resultMessage = "<div class='alert alert-danger' role='alert'>
                        {$adoptResponse['message']}
                      </div>";
  }
}

if (is_bool_input_true('senior')) {
  $response = get_animals_age_greater(8);
  $pageTitle = 'Senior Pets';
} else if (is_bool_input_true('my_adopted_pets')) {
  $response = get_animals_adopted_by_user($_SESSION['user']);
  $pageTitle = 'My Adopted Pets';
} else if (is_bool_input_true('available_pets')) {
  $response = get_animals_available();
  $pageTitle = 'Available Pets';
} else {
  $response = get_all_animals();
  $pageTitle = 'All Pets';
}

if ($response['status'] == 200) {
  $layout = create_card_layout_for_animals($response);
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
  <title><?= WEBSITE_TITLE . ' - ' . $pageTitle ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/style.css">
  <link rel="stylesheet" href="./styles/card_layout.css">
</head>

<body>
  <?php require_once './components/navbar.php'; ?>
  <div class="container mt-3 mb-5" style="flex-grow: 1;">
    <div class="d-flex gap-3 mb-3">
      <a class="btn btn-primary" href="home.php">All Pets</a>
      <a class="btn btn-primary" href="home.php?available_pets=true">Available Pets</a>
      <a class="btn btn-primary" href="home.php?senior=true">Senior Pets</a>
      <a class="btn btn-primary" href="home.php?my_adopted_pets=true">My Adopted Pets</a>
    </div>
    <?= $resultMessage ?? '' ?>
    <h1 class="mb-4"><?= $pageTitle ?></h1>
    <?= $layout ?>
  </div>
  <?php require_once './components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>