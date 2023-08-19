<?php
session_start();
include './connection.php';


if (isset($_POST['bt_submit'])) {
  $userName = $_POST['user_name'];
  $userPass = $_POST['user_pass'];

  if (empty($userName) || empty($userPass)) {
    $_SESSION['error'] = "Username or Password is Empty.";
    // return;
  } else {
    $sql = "SELECT * FROM user WHERE name LIKE '$userName' AND password LIKE '$userPass'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
      $_SESSION['success'] = "Login Successful.";
      header('location: meter_reading.php');
    } else {
      $_SESSION['error'] = "Invalide Username or Password.";
      // header('location: index.php');
    }
  }
}