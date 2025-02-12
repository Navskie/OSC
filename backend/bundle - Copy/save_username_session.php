<?php
  // save_username_session.php

  // Start the session
  session_start();

  // Get the username from the POST request
  $username = $_POST['username'];

  // Save the username in the session
  $_SESSION['rsusername'] = $username;

  echo 'success';
?>
