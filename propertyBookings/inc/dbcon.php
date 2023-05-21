<?php
  //Connect to Database     
  $conn = new mysqli('localhost', 'admin1', 'admin123', 'property_booking');
  if (mysqli_connect_errno()) {
    echo "Error: can not connect to the database";
    exit;
  }
