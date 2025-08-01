<?php
session_start();



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Animal Adoption - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <h1>Login</h1>
    <form method="POST" class="mt-4 mb-3 d-flex flex-column justify-content-start align-items-start">
      <div>
        <div class="d-flex mb-3">
          <label for="email" class="form-label" style="flex-basis: 250px">Email:</label>
          <input type="text" name="email" id="email" class="form-control" value="<?= $email ?? "" ?>">
        </div>
        <div class="d-flex mb-3">
          <label for="password" class="form-label" style="flex-basis: 250px">Password:</label>
          <input type="password" name="password" id="password" class="form-control">
        </div>
        <div>
          <input type="submit" name="login" value="Login" class="btn btn-primary">
        </div>
      </div>
    </form>

    <a href="./user_register.php">Register new Account</a>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>