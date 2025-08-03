<?php
$homeUrl = isset($_SESSION['admin']) ? "dashboard.php" : "home.php";
$homeName = isset($_SESSION['admin']) ? "Dashboard" : "Home";
?>

<header class='p-0 mb-3 border-bottom title-row'>
  <nav class="navbar navbar-expand-lg" style="min-height: 3.5rem">
    <div class="container">
      <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])) { ?>
        <a href='<?= $homeUrl ?>' class='d-block me-3'>
          <img src='<?= $myUserData['profile_img_url'] ?? '' ?>' alt='profile image' width='100' height='100' class='rounded-circle shadow-sm' style='object-fit:cover;'>
        </a>
        <a class="navbar-brand me-1" href="<?= $homeUrl ?>">Hi <?= $myUserData['first_name'] ?? '' ?>!</a>
      <?php } ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav gap-2">
          <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])) { ?>
            <li class="nav-item me-5">
              <a class="nav-link link-body-emphasis" href="#"><?= '(' . $myUserData['email'] . ')' ?></a>
            </li>
            <li class="nav-item me-4">
              <a class="btn btn-primary" href="<?= $homeUrl ?>"><?= $homeName ?></a>
            </li>
            <li class="nav-item">
              <a class="btn btn-secondary" href="logout.php">Sign out</a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>