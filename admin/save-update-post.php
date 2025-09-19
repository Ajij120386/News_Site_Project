<?php
include "config.php";
include "cache.php"; 

if(empty($_FILES['new-image']['name'])){
  // If no new image uploaded, keep old one
  $new_name = $_POST['old_image'];
}else{
  $errors = array();

  $file_name = $_FILES['new-image']['name'];
  $file_size = $_FILES['new-image']['size'];
  $file_tmp  = $_FILES['new-image']['tmp_name'];
  $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

  $extensions = array("jpeg","jpg","png");

  if(in_array($file_ext, $extensions) === false){
    $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
  }

  if($file_size > 2097152){
    $errors[] = "File size must be 2mb or lower.";
  }

  $new_name = time(). "-".basename($file_name);
  $target = "upload/".$new_name;

  if(empty($errors) == true){
    move_uploaded_file($file_tmp,$target);
  }else{
    print_r($errors);
    die();
  }
}

$sql = "UPDATE post 
        SET title = '{$_POST["post_title"]}',
            description = '{$_POST["postdesc"]}',
            category = {$_POST["category"]},
            post_img = '{$new_name}'
        WHERE post_id = {$_POST["post_id"]};";

// If category changed → update counts
if($_POST['old_category'] != $_POST["category"]){
  $sql .= "UPDATE category SET post = post - 1 WHERE category_id = {$_POST['old_category']};";
  $sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$_POST["category"]};";
}

$result = mysqli_multi_query($conn, $sql);

if($result){
  
  clearCache();

  header("location: {$hostname}/admin/post.php");
}else{
  echo "Query Failed";
}
?>
