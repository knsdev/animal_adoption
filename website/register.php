<?php
require_once './components/define.php';
require_once './components/db_connect.php';

$picture = './uploads/user.png';

if (isset($_POST['register'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Animal Adoption</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-3 mb-5">
    <h1 class="mb-4">Register</h1>
    <form method="POST" enctype="multipart/form-data" style="max-width: 600px">
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" name="confirm-password" id="confirm-password" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="picture">Profile Picture</label>
        <img src="<?= $picture ?>" alt="" width="200" style="border-radius: 50%">
        <input type="file" name="picture" id="picture" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-3">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-control">
      </div>
      <div class="form-group d-flex flex-column gap-2 mt-4">
        <input type="submit" name="register" value="Register" class="btn btn-primary">
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>