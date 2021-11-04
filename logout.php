<?php

/** Logout Functionality */

session_start();
if (isset($_SESSION['user_info'])) {
  unset($_SESSION['user_info']);
  session_destroy();
}

header("Location: index.php");
exit();

?>

