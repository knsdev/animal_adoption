<?php
require_once 'components/define.php';

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
      return [null, null];
    }

    $row = mysqli_fetch_assoc($result);

    if (empty($row['picture'])) {
      $profileImgUrl = './' . PICTURE_FOLDER_NAME . '/user.png';
    } else {
      $profileImgUrl = './' . PICTURE_FOLDER_NAME . '/' . $row['picture'];
    }
  } else {
    return [null, null];
  }

  return [$row, $profileImgUrl];
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
