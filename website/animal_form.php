<?= $resultMessage ?? '' ?>
<form method="POST" enctype="multipart/form-data" style="max-width: 600px">
  <div>
    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" class="form-control" value="<?= $_POST['name'] ?? '' ?>">
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_name'] ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="location">Location</label>
      <input type="text" name="location" id="location" class="form-control" value="<?= $_POST['location'] ?? '' ?>">
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_location'] ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="picture">Photo</label>
      <img src="<?= isset($picture[0]) ? PICTURE_FOLDER_NAME . '/' . $picture[0] : '' ?>" alt="" width="200" style="border-radius: 0.25rem">
      <input type="file" name="picture" id="picture" class="form-control">
    </div>
    <p class="text-danger fw-bold"><?= $errorPicture ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="description">Description</label>
      <textarea name="description" id="description" class="form-control" rows="5"><?= $_POST['description'] ?? '' ?></textarea>
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_description'] ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="size">Size</label>
      <select name="size" id="size" class="form-select">
        <?php
        $sizes = get_animal_sizes();
        $selectedSize = $_POST['size'] ?? 'default';

        for ($i = 0; $i < count($sizes); $i++) {
          $sizeValue = $sizes[$i]['value'];
          $sizeName = $sizes[$i]['name'];

          echo "<option value='$sizeValue'";

          if ($sizeValue == $selectedSize) {
            echo " selected";
          }

          echo ">$sizeName</option>";
        }
        ?>
      </select>
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_size'] ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="age">Age (years)</label>
      <input type="number" name="age" id="age" class="form-control" value="<?= $_POST['age'] ?? '' ?>">
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_age'] ?? '' ?></p>

    <div class="form-group d-flex flex-row justify-content-start align-items-center gap-4 mt-3">
      <label for="vaccinated" class="form-check-label">Vaccinated</label>
      <input type="checkbox" class="form-check-input" style="width: 2rem; height: 2rem;" name="vaccinated" id="vaccinated" value="<?= $_POST['vaccinated'] ?? '' ?>">
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_vaccinated'] ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="status">Status</label>
      <select name="status" id="status" class="form-select">
        <?php
        $stati = get_animal_status_values();
        $selectedStatus = $_POST['status'] ?? '';

        for ($i = 0; $i < count($stati); $i++) {
          $statusValue = $stati[$i]['value'];
          $statusName = $stati[$i]['name'];

          echo "<option value='$statusValue'";

          if ($statusValue == $selectedStatus) {
            echo " selected";
          }

          echo ">$statusName</option>";
        }
        ?>
      </select>
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_status'] ?? '' ?></p>

    <div class="form-group d-flex flex-column gap-2 mt-3">
      <label for="breed_id">Breed</label>
      <select name="breed_id" id="breed_id" class="form-select">
        <option value="-1">Select a breed ...</option>
        <?php
        $breeds = get_animal_breeds();
        $selectedBreedId = $_POST['breed_id'] ?? '';

        for ($i = 0; $i < count($breeds); $i++) {
          $breedId = $breeds[$i]['id'];
          $breedName = $breeds[$i]['name'];

          echo "<option value='$breedId'";

          if ($breedId == $selectedBreedId) {
            echo " selected";
          }

          echo ">$breedName</option>";
        }
        ?>
      </select>
    </div>
    <p class="text-danger fw-bold"><?= $response['data']['error_breed_id'] ?? '' ?></p>

    <div class="mt-3">
      <input type="submit" name="<?= $submitButtonName ?>" value="<?= $submitButtonValue ?>" class="btn btn-primary">
    </div>
  </div>
</form>