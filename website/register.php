<?php
session_start();

if (isset($_SESSION['user'])) {
  header("location: index.php");
  exit();
} else if (isset($_SESSION['admin'])) {
  header("location: dashboard.php");
  exit();
}

require_once __DIR__ . '/components/define.php';
require_once __DIR__ . '/components/db_connect.php';
require_once __DIR__ . '/components/util.php';
require_once __DIR__ . '/components/file_upload.php';

if (isset($_POST['register'])) {
  $conn = db_connect();

  $email = clean_input($_POST['email']);
  $password = clean_input($_POST['password']);
  $confirmPassword = clean_input($_POST['confirm_password']);
  $picture = image_file_upload($_FILES['picture'], PICTURE_FOLDER_NAME);
  $firstName = clean_input($_POST['first_name']);
  $lastName = clean_input($_POST['last_name']);
  $phone = clean_input($_POST['phone']);
  $address = clean_input($_POST['address']);
  $authority = 'user';

  $error = false;

  switch ($picture[1]) {
    case ImageFileUploadResult::Success:
      break;

    case ImageFileUploadResult::NoFileUploaded:
      break;

    default:
      $errorPicture = image_file_get_error_message($picture[1]);
      $error = true;
      break;
  }

  if (empty($email)) {
    $errorEmail = "Email cannot be empty.";
    $error = true;
  } else if (strlen($email) < EMAIL_MIN_LENGTH) {
    $errorEmail = "Email is too short";
    $error = true;
  } else if (strlen($email) > EMAIL_MAX_LENGTH) {
    $errorEmail = "Email is too long";
    $error = true;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorEmail = "Invalid email format.";
    $error = true;
  } else {
    $sql = "SELECT * FROM `user` WHERE `email`='$email'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      $error = true;
      $resultMessageFailure = get_last_sql_error_message($conn);
    } else if (mysqli_num_rows($result) > 0) {
      $error = true;
      $errorEmail = "Email already exists.";
    }
  }

  if (empty($password)) {
    $errorPassword = "Password cannot be empty.";
    $error = true;
  } else if (strlen($password) < PASSWORD_MIN_LENGTH) {
    $errorPassword = "Password is too short (at least " . PASSWORD_MIN_LENGTH . " characters).";
    $error = true;
  } else if (strlen($password) > PASSWORD_MAX_LENGTH) {
    $errorPassword = "Password is too long.";
    $error = true;
  } else if (strcmp($password, $confirmPassword) != 0) {
    $errorConfirmPassword = "Passwords did not match.";
    $error = true;
  }

  if (empty($firstName)) {
    $errorFirstName = "First name cannot be empty.";
    $error = true;
  }

  if (empty($lastName)) {
    $errorLastName = "Last name cannot be empty.";
    $error = true;
  }

  if (empty($phone)) {
    $errorPhone = "Phone number cannot be empty.";
    $error = true;
  }

  if (empty($address)) {
    $errorAddress = "Address cannot be empty.";
    $error = true;
  }

  if (!$error) {
    $password = hash("sha256", $password);

    $sql = "INSERT INTO `user`(`email`, `password`, `first_name`, `last_name`, `phone`, `address`, `picture`, `authority`)
            VALUES ('$email','$password','$firstName','$lastName','$phone','$address','$picture[0]','$authority')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      $resultMessageSuccess = "<div class='alert alert-success' role='alert'>Registered successfully!</div>";
      $email = null;
      $password = null;
      $confirmPassword = null;
      $picture = null;
      $firstName = null;
      $lastName = null;
      $phone = null;
      $address = null;
    } else {
      $resultMessageFailure = "<div class='alert alert-success' role='alert'>" . get_last_sql_error_message($conn) . "</div>";
      image_file_delete($picture, PICTURE_FOLDER_NAME);
    }
  } else {
    image_file_delete($picture, PICTURE_FOLDER_NAME);
  }

  mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= WEBSITE_TITLE ?> - Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
  <?php require_once './components/navbar.php'; ?>
  <div class="container mt-3 mb-5" style="flex-grow: 1;">
    <h1 class="mb-2">Register</h1>
    <p class="text-success"><?= $resultMessageSuccess ?? '' ?></p>
    <p class="text-danger"><?= $resultMessageFailure ?? '' ?></p>
    <?php create_back_button("login.php"); ?>
    <form method="POST" enctype="multipart/form-data" style="max-width: 600px">
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= $email ?? '' ?>">
      </div>
      <p class="text-danger fw-bold"><?= $errorEmail ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <p class="text-danger fw-bold"><?= $errorPassword ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
      </div>
      <p class="text-danger fw-bold"><?= $errorConfirmPassword ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="picture">Profile Picture</label>
        <img src="<?= isset($picture[0]) ? $picture[0] : '' ?>" alt="" width="200" style="border-radius: 50%">
        <input type="file" name="picture" id="picture" class="form-control">
      </div>
      <p class="text-danger fw-bold"><?= $errorPicture ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $firstName ?? '' ?>">
      </div>
      <p class="text-danger fw-bold"><?= $errorFirstName ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $lastName ?? '' ?>">
      </div>
      <p class="text-danger fw-bold"><?= $errorLastName ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="phone">Phone Number</label>
        <input type="text" name="phone" id="phone" class="form-control" value="<?= $phone ?? '' ?>">
      </div>
      <p class="text-danger fw-bold"><?= $errorPhone ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-control" value="<?= $address ?? '' ?>">
      </div>
      <p class="text-danger fw-bold"><?= $errorAddress ?? '' ?></p>
      <div class="form-group d-flex flex-column gap-2 mt-4">
        <input type="submit" name="register" value="Register" class="btn btn-primary">
      </div>
    </form>
  </div>
  <?php require_once './components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>