<?php
// Specify the directory where images will be saved
$targetDir = "images/";

// Retrieve the name of the uploaded file
$filename = $_FILES["images"]["name"];

// Generate a unique filename to avoid conflicts
$uniqueFilename = uniqid() . '_' . $filename;

// Construct the path where the image will be saved
$targetPath = $targetDir . $uniqueFilename;

// Move the uploaded file to the target directory
if (move_uploaded_file($_FILES["images"]["tmp_name"], $targetPath)) {
  // Image uploaded successfully

  // TODO: Save the filename to the database
  // Implement your code here to save the $uniqueFilename to your database

  echo "Image uploaded and saved successfully.";
} else {
  // Failed to upload image
  echo "Sorry, there was an error uploading your file.";
}
?>