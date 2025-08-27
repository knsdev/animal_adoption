<?php
session_start();

if (isset($_SESSION['user'])) {
  header("location: home.php");
  exit();
} else if (isset($_SESSION['admin'])) {
  header("location: dashboard.php");
  exit();
}

require_once __DIR__ . '/components/define.php';
require_once __DIR__ . '/components/db_connect.php';
require_once __DIR__ . '/components/util.php';

if (isset($_POST['login'])) {
  $conn = db_connect();

  $email = clean_input($_POST['email']);
  $password = clean_input($_POST['password']);
  $password = hash("sha256", $password);

  $sql = "SELECT * FROM `user` WHERE email='$email' AND password='$password'";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    $resultMessage = "<div class='alert alert-error' role='alert'>
                        Internal Server Error
                      </div>";
  } else if (mysqli_num_rows($result) != 1) {
    $resultMessage = "<div class='alert alert-warning' role='alert'>
                        Wrong credentials!
                      </div>";
  } else {
    $row = mysqli_fetch_assoc($result);

    if ($row['authority'] == 'user') {
      $_SESSION['user'] = $row['id'];
      header("location: home.php");
    } else if ($row['authority'] == 'admin') {
      $_SESSION['admin'] = $row['id'];
      header("location: dashboard.php");
    }
  }

  mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= WEBSITE_TITLE ?> - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
  <?php require_once './components/navbar.php'; ?>
  <div class="container mt-3 mb-5" style="flex-grow: 1;">
    <?= $resultMessage ?? '' ?>
    <h1>Sign In</h1>
    <form method="POST" class="mt-4 mb-3 d-flex flex-column justify-content-start align-items-start">
      <div>
        <div class="d-flex mb-3 gap-3">
          <label for="email" class="form-label" style="flex-basis: 250px">Email:</label>
          <input type="text" name="email" id="email" class="form-control" value="<?= $email ?? "user@user.com" ?>">
          <div id="test-info-email" style="flex-basis: 250px; text-wrap: nowrap; font-style: italic;">Test email</div>
        </div>
        <div class="d-flex mb-3 gap-3">
          <label for="password" class="form-label" style="flex-basis: 250px">Password:</label>
          <input type="password" name="password" id="password" class="form-control" value="1212">
          <div id="test-info-pw" style="flex-basis: 250px; text-wrap: nowrap; font-style: italic;">Test password</div>
        </div>
        <div>
          <input type="submit" name="login" value="Login" class="btn btn-primary">
        </div>
      </div>
    </form>

    <a href="./register.php">Register new Account</a>
  </div>
  <?php require_once './components/footer.php'; ?>

  <script>
    function handleInfoVisibility(inputElementId, infoElementId, testAccountValue) {
      let input = document.getElementById(inputElementId);
      let info = document.getElementById(infoElementId);

      input.addEventListener("input", function(evt) {
        if (input.value != testAccountValue)
          info.style.visibility = "hidden";
        else
          info.style.visibility = "visible";
      });
    }

    handleInfoVisibility("email", "test-info-email", "user@user.com");
    handleInfoVisibility("password", "test-info-pw", "1212");
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>