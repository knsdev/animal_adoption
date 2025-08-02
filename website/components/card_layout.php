<?php
require_once './components/define.php';

function create_card_layout_for_animals($response)
{
  $rows = $response['data'];
  $layout = "<div class='row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4
                         justify-content-center justify-content-md-start'>";

  foreach ($rows as $animal) {
    $animalPictureUrl = get_animal_picture_url($animal);

    $layout .= "
    <div style='width: fit-content;'>
      <div class='card mb-4' style='max-width: 20rem; min-height: 30rem'>
        <div class='animal-picture-container'>
          <img src='$animalPictureUrl' class='card-img-top animal-image' alt=''>
        </div>
        <div class='card-body d-flex flex-column justify-content-between align-items-center'>
          <div>
            <h5 class='card-title'>{$animal['name']}</h5>
            <p class='card-text'>{$animal['description']}</p>
          </div>";

    if (isset($_SESSION['user'])) {
      $layout .= "<div>
                    <a href='./animal_details.php?id={$animal['id']}' class='btn btn-primary'>Details</a>
                    <a href='#' class='btn btn-success'>Take me home</a>
                  </div>";
    } else if (isset($_SESSION['admin'])) {
      $layout .= "<div>
                    <a href='./animal_details.php?id={$animal['id']}' class='btn btn-primary'>Details</a>
                    <a href='./animal_update.php?id={$animal['id']}' class='btn btn-success'>Update</a>
                    <a href='./animal_delete.php?id={$animal['id']}' class='btn btn-danger'>Delete</a>
                  </div>";
    }

    $layout .= "
        </div>
      </div>
    </div>
    ";
  }

  $layout .= "</div>";

  return $layout;
}
