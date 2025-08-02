<header class='p-0 mb-3 border-bottom title-row'>
  <nav class="navbar navbar-expand-lg" style="min-height: 3.5rem">
    <div class="container">
      <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])) { ?>
        <a href='#' class='d-block me-3'>
          <img src='<?= $myUserData['profile_img_url'] ?? '' ?>' alt='profile image' width='100' height='100' class='rounded-circle' style='object-fit:cover;'>
        </a>
        <a class="navbar-brand" href="#">Hi <?= $myUserData['first_name'] ?? '' ?>!</a>
      <?php } ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <?php if (isset($_SESSION['user'])) { ?>
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href='home.php'>Home</a>
            </li>
          <?php } ?>
          <?php if (isset($_SESSION['admin'])) { ?>
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href="dashboard.php">Dashboard</a>
            </li>
          <?php } ?>
          <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])) { ?>
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href="home.php?senior=true">Senior</a>
            </li>
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href="logout.php">Sign out</a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>