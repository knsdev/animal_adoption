<?php
session_start();

require_once './components/define.php';
require_once './components/db_connect.php';
require_once './components/util.php';
require_once './components/animals.php';
require_once './components/card_layout.php';

$conn = db_connect();
$myUserId = get_my_user_id_from_session();
$myUserData = get_user_data($conn, $myUserId);

if (isset($_GET['senior']) && filter_var($_GET['senior'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) === true) {
  $response = get_animals_age_greater(8);
  $pageTitle = 'Senior pets';
} else {
  $response = get_all_animals();
  $pageTitle = 'All pets';
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
  <div class="container mt-3 mb-5">
    <h1 class="mb-4"><?= $pageTitle ?></h1>
    <div class="d-flex gap-3 mb-3">
      <a class="btn btn-primary" href="home.php">Show All</a>
      <a class="btn btn-primary" href="home.php?senior=true">Show Seniors</a>
    </div>
    <?= $layout ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>