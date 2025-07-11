<?php

/* 
  
  NOTE: When using this business object in a "real" webpage using user-supplied data:

  1. Get data from the user
  2. Validation (and sanitisation) - in the class or using FormValidator
  3. Image upload logic (if you have images)
  4. Create new object (from business object class)
  5. Assign property values, e.g. $object->setProperty("value")
  6. Insert/update into database
  7. Get new ID (if inserting) and display message to user

 */

// Common includes such as class definitions and constants
require_once "includes/common.php";

Auth::protect();
// Config
$title = "Add Item";

if (isset($_POST["submitAddItem"])) {
try
{
  
  /* 
     * Photo file upload
     * Reference for the $_FILES array: https://www.php.net/manual/en/features.file-upload.post-method.php
     */

    // File upload settings
    $targetDirectory = ROOT_DIR . "image/";
    $fileUploadOptional = false;


    // Skip file upload if no file given and upload is optional
    if (!($fileUploadOptional && $_FILES["photo"]["error"] === UPLOAD_ERR_NO_FILE)) {

      // Get the filename of the uploaded file (what was it originally called?)
      $fileName = isset($_FILES["photo"]["name"]) ? basename($_FILES["photo"]["name"]) : "";

      // Make sure file is an image (using file extension)
      $validExtensions = ["jpg", "jpeg", "gif", "png"];
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

      if (!in_array($fileExtension, $validExtensions)) {
        $errors["photo"] = "Invalid file extension, must be: " . implode(", ", $validExtensions);
      }
      
      // Check file size (not too big) using php.ini config and MAX_FILE_SIZE set in the form
      // You can also manually check the ["size"] of the file
      if (
        isset($_FILES["photo"]["error"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_FORM_SIZE ||
        isset($_FILES["photo"]["error"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_INI_SIZE
      ) {
        $errors["photo"] = "File is too large.";
      }

      // NODO: Add other file upload validation

      // Make sure there are no file errors detected
      if (empty($errors["photo"])) {

        // OPTIONAL: Change the file name
        // $fileName = "xxxxx.$fileExtension";
        // Check whether file exists before uploading it
        if (file_exists("image/" . $fileName)) {
        throw new Exception(" " . $fileName . " already exists.");
      }
        // Move uploaded file from the temp location into the target location
        $moveFrom = $_FILES["photo"]["tmp_name"];
        $moveTo = $targetDirectory . $fileName;

        // Move uploaded file from the temp location into the target location
        if (move_uploaded_file($moveFrom, $moveTo)) {

          // Success
          $photo = $fileName;

        } else {

          // Error
          $errors["photo"] = "Uploaded file could not be moved.";

        }
      }
    }
  // If there are errors, display them and stop the process
  if (!empty($errors)) {
    include_once TEMPLATES_DIR . "_errorSummary.html.php";
    return;
  }

  // Create new object instance (using the constructor)
  $item = new Item();
  // Set properties using the data from the form
  // Note: You can also use the set methods to set properties
  $item->setPhoto($photo ?? "");
  $item->setItemName($_POST["itemName"] ?? "");
  $item->setPrice($_POST["price"] ?? 0.0);
  $item->setSalePrice($_POST["salePrice"] ?? 0.0);
  $item->setDescription($_POST["description"] ?? "");
  $item->setCategoryId($_POST["categoryId"] ?? 0);
  $newItemId = $item->insertItem();

  // Display success message
  $successMessage = "Item added successfully, new ID: {$newItemId}, Name: {$item->getItemName()}, Description: {$item->getDescription()}";
  include_once TEMPLATES_DIR . "_success.html.php";

} catch (Exception $ex) {

  // "Handle" exception
  $errorMessage = "Catastrophic error: {$ex->getMessage()}</p>";
  include_once TEMPLATES_DIR . "_error.html.php";

}

} else {
  // Display the form to add a new category
 include_once TEMPLATES_DIR . "_addItemPage.html.php";
}

  // Stop output buffering - store output into the $content variable
  $content = ob_get_clean();

  // Include the main layout template
  include_once TEMPLATES_DIR . "_layout.html.php";
