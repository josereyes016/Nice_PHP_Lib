<?php

// http://php.net/manual/en/features.file-upload.post-method.php
/* $path is the location on the sever where you want to store the file
** $file_name is the name you want to give the file on the server
** $extensions is the file types you want to allow
NOTE: YOU MUST GIVE THE NAME "upload" TO THE INPUT ELEMENT
*/
  function upload_file($path, $file_name, $extensions){
    $target_dir = $path;
    $target_filename = basename($_FILES["upload"]["name"]);
    $target_file = $target_dir . $target_filename;
    $uploadOk = 1;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Check if file already exists
    if (file_exists($target_file)) {
        //Sorry, file already exists.
        $uploadOk = 0;
    }

    // Allow certain file formats
    $safeExtensions = $extensions;
    if(in_array($fileType, $safeExtensions)){
      // Sorry, file not allowed.
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // Your file was not uploaded.
        return false;
    }
    // if everything is ok, try to upload file
    else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
        return true;
      }
      else {
          return false;
      }
    }
  }

?>
