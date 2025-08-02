<?php
require_once './components/define.php';

function create_card_layout_for_animals($response)
{
  $rows = $response['data'];
  $layout = "<div class='row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4
                         justify-content-center justify-content-md-start'>";

  foreach ($rows as $animal) {
    $animalPictureUrl = $animal['picture'] ? PICTURE_FOLDER_NAME . '/' . $animal['picture'] : ANIMAL_DEFAULT_PICTURE_URL;

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
          </div>
          <div>
            <a href='#' class='btn btn-primary'>Take me home</a>
          </div>
        </div>
      </div>
    </div>
    ";
  }

  $layout .= "</div>";

  return $layout;
}
