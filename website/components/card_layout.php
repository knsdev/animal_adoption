<?php
require_once __DIR__ . '/define.php';

function create_card_layout_for_animals($response)
{
  $rows = $response['data'];
  $layout = "<div class='row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4
                         justify-content-center justify-content-md-start'>";

  foreach ($rows as $animal) {
    $animalPictureUrl = get_animal_picture_url($animal);
    $vaccinatedText = ($animal['vaccinated']) ? 'Yes' : 'No';
    $breedData = get_breed_by_id($animal['breed_id']);

    $layout .= "
    <div style='width: fit-content;'>
      <div class='card mb-4 d-flex flex-column justify-content-between align-items-center' style='max-width: 20rem; min-height: 30rem'>";

    $layout .= "<div>
                  <div class='animal-picture-container'>
                    <img src='$animalPictureUrl' class='card-img-top animal-image' alt=''>
                  </div>
                  <div class='card-body'>
                    <h5 class='card-title'>{$animal['name']}</h5>
                    <p class='card-text' style='min-height: 48px'>{$animal['description']}</p>
                  </div>
                </div>";

    $layout .= "<div style='width: 100%'>";

    $layout .= "<ul class='list-group list-group-flush'>
                  <li class='list-group-item' style='padding: 0'></li>
                    <li class='list-group-item'>Location: {$animal['location']}</li>
                    <li class='list-group-item'>Breed: {$breedData['name']}</li>
                    <li class='list-group-item'>Status: {$animal['status']}</li>
                  <li class='list-group-item'></li>
                </ul>";

    if (isset($_SESSION['user'])) {
      $layout .= "<div class='d-flex flex-row gap-1 justify-content-around mb-3' style='width:100%'>
                    <a href='./animal_details.php?id={$animal['id']}' class='btn btn-primary'>View Details</a>";
      $layout .= "<form method='POST'>
                      <input type='hidden' name='animal_id_to_adopt' value='{$animal['id']}' />
                      <input" . (($animal['status'] != 'available') ? " disabled" : "") . " type='submit' name='adopt_animal' class='btn btn-success' value='Take me home' />
                  </form>";
      $layout .= "</div>";
    } else if (isset($_SESSION['admin'])) {
      $layout .= "<div class='mb-3'>
                    <a href='./animal_details.php?id={$animal['id']}' class='btn btn-primary'>View Details</a>
                    <a href='./animal_update.php?id={$animal['id']}' class='btn btn-success'>Update</a>
                    <a href='./animal_delete.php?id={$animal['id']}' class='btn btn-danger'>Delete</a>
                  </div>";
    }

    $layout .= "</div>"; // <div style='width: 100%'>

    $layout .=  "</div></div>"; // <div class='card ...> , <div style='width: fit-content;'>
  }

  $layout .= "</div>";

  return $layout;
}
