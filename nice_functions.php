<?php function upload_file($path, $input_name,
  $target_dir = "grades/";
  $target_filename = basename($_FILES["fileToUpload"]["name"]);
  $target_file = $target_dir . $target_filename;
  $uploadOk = 1;
  $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

  // Check if file already exists
  if (file_exists($target_file)) {
      //$gradeError = "Sorry, file already exists.";
      $uploadOk = 0;
  }

  // Allow certain file formats
  $safeExtensions = array('.csv');
  if(in_array($fileType, $safeExtensions)){
    $gradeError = "Sorry, file not allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      $gradeError =  $gradeError ." Your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          $gradeSuccess = "The file ". $target_filename . " has been uploaded.\n";

          $handle = fopen($target_file, "r");

          $rows = 0;

          $errors = 0;

          $keys = [];
          if($handle !== FALSE)
          {
              $gradeSuccess = $gradeSuccess. " File opened.\n";
              while(($row = fgetcsv($handle, 0)) != FALSE)
              {
                  if($rows == 0)
                  {
                      $row = preg_replace("/.+\s/", "" , $row);
                      $keys = array_map("strtolower", $row);
                  }
                  if(count($row) == count($keys)  && $rows > 0){
                    //Get Student ID
                    $result = mysqli_query($db, "SELECT id
                                                       FROM users
                                                      WHERE email='$row[1]'");

                    if($result == FALSE){
                      $errors += 1;
                      continue;
                    }
                    /*  fetch row */
                    $arow = $result->fetch_assoc();
                    $studentID = $arow['id'];
                      for( $i = 2, $count = count($keys); $i < $count; $i++){

                          $psetName = $keys[$i];
                          $psetType = $assignments[ucfirst($psetName)]['type'];
                          $psetNumber = $assignments[ucfirst($psetName)]['number'];
                          $psetGrade = $row[$i];

                          $gradeQuery = mysqli_query($db, "SELECT 'id'
                                                             FROM grades
                                                            WHERE id='$studentID'
                                                              AND name='$psetName'");
                          $gradeRows = mysqli_num_rows($gradeQuery);
                          if ($gradeRows == 0) {
                            mysqli_query($db, "INSERT INTO `grades` (`id`, `type`, `number`, `name`, `grade`)
                                                    VALUES ('$studentID', '$psetType', '$psetNumber', '$psetName', '$psetGrade')");
                          }
                          elseif ($gradeRows == 1) {
                            mysqli_query($db, "UPDATE grades
                                                  SET grade='$psetGrade'
                                                WHERE id='$studentID'
                                                  AND name='$psetName'");
                          }
                      }
                  }
                  $rows += 1;
              }

              $gradeSuccess = $gradeSuccess. " Rows processed $rows.\n";
              $gradeSuccess = $gradeSuccess . " Done";

              $gradeError = $gradeError. "$errors emails could not be processed.\n";

              fclose($handle);
          }
          else{
            $gradeError = $gradeError. " Could not open file $target_filename";
          }

          unlink($target_file);
      } else {
          $gradeError = "Sorry, there was an error uploading your file.";
      }
  }
}

?>
