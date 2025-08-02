<?php
require_once 'components/define.php';

enum ImageFileUploadResult
{
  case Success;
  case NoFileUploaded;
  case InvalidFile;
  case TooLarge;
  case UnknownError;
}

function image_file_upload_is_success($result)
{
  return $result === ImageFileUploadResult::Success || $result === ImageFileUploadResult::NoFileUploaded;
}

function image_file_get_error_message($result)
{
  switch ($result) {
    case ImageFileUploadResult::Success:
      return "";
    case ImageFileUploadResult::NoFileUploaded:
      return "You have not uploaded a picture. You can add one later.";
    case ImageFileUploadResult::InvalidFile:
      return "The file type of your picture is not supported.";
    case ImageFileUploadResult::TooLarge:
      return "Your picture file is too large.";
    case ImageFileUploadResult::UnknownError:
    default:
      return "Something went wrong, please try again later.";
  }
}

/**
 * Handles the uploaded image file from a form and returns the new filename
 *
 * @param array $param the array you get from FILES['form-input-name']
 * @param string $folderName the name of the folder for uploaded images without '/'
 * 
 * @return array [0] filename string | null , [1] enum ImageFileUploadResult
 */
function image_file_upload($param, $folderName)
{
  // echo '<pre>';
  // var_dump($param);
  // echo '</pre><br><br>';

  $upload_error = $param['error'];
  $original_name = $param['name'];
  $tmp_name = $param['tmp_name'];

  if ($upload_error == UPLOAD_ERR_NO_FILE) {
    return [null, ImageFileUploadResult::NoFileUploaded];
  } else if ($upload_error == UPLOAD_ERR_INI_SIZE || $upload_error == UPLOAD_ERR_FORM_SIZE) {
    return [null, ImageFileUploadResult::TooLarge];
  } else if ($upload_error != UPLOAD_ERR_OK) {
    return [null, ImageFileUploadResult::UnknownError];
  } else {
    if (!getimagesize($tmp_name)) {
      return [null, ImageFileUploadResult::InvalidFile];
    }

    $filename = uniqid("img_");
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $destination = "$folderName/" . $filename . "." . $ext;

    if (!is_dir("$folderName")) {
      mkdir("$folderName");
    }

    if (!move_uploaded_file($tmp_name, $destination)) {
      return [null, ImageFileUploadResult::InvalidFile];
    }

    return [$filename . "." . $ext, ImageFileUploadResult::Success];
  }
}

function image_file_delete($img, $folderName)
{
  if ($img[0]) {
    unlink($folderName . '/' . $img[0]);
  }
}
