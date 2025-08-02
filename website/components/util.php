<?php
require_once __DIR__ . '/define.php';

function clean_input($str)
{
  $result = trim($str);
  $result = strip_tags($result);
  $result = htmlspecialchars($result);
  return $result;
}

function get_my_user_id_from_session()
{
  if (isset($_SESSION["user"])) {
    return $_SESSION["user"];
  } else if (isset($_SESSION["admin"])) {
    return $_SESSION["admin"];
  } else {
    return null;
  }
}

function get_user_data($conn, $userId)
{
  $sql = "SELECT * FROM `user` WHERE `id`='$userId'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    if (mysqli_num_rows($result) != 1) {
      return null;
    }

    $row = mysqli_fetch_assoc($result);

    $defaultImgUrl = './' . PICTURE_FOLDER_NAME . '/user.png';

    if (empty($row['picture'])) {
      $row['profile_img_url'] = $defaultImgUrl;
    } else {
      $pictureUrl = './' . PICTURE_FOLDER_NAME . '/' . $row['picture'];

      if (!file_exists($pictureUrl)) {
        $row['profile_img_url'] = $defaultImgUrl;
      } else {
        $row['profile_img_url'] = $pictureUrl;
      }
    }
  }

  return $row;
}

function date_format_for_database($date)
{
  $defaultValue = "1970-01-01";

  if (empty($date) || $date == "0000-00-00") {
    return $defaultValue;
  }

  $dateObj = date_create($date);

  if (!$dateObj)
    return $defaultValue;

  return date_format($dateObj, "Y-m-d");
}

function date_format_for_display($date)
{
  if (!empty($date) && $date != "0000-00-00") {
    $dateObj = date_create($date);

    if (!$dateObj)
      return "";

    return date_format($dateObj, "d.m.Y");
  }

  return "";
}

function get_last_sql_error_message($conn)
{
  return ERROR_MESSAGE_GENERAL . ' Error: ' . mysqli_error($conn);
}

function create_back_button($url)
{
  echo "<a href='$url' class='btn btn-secondary mt-3'>Back</a>";
}

function is_bool_input_true($varName)
{
  return isset($_GET[$varName]) && filter_var($_GET[$varName], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) === true;
}
