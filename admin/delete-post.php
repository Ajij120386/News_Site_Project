<?php
include "config.php";
include "cache.php"; // Add this to clear cache

$post_id = $_GET['id'];
$cat_id  = $_GET['catid'];

// Delete image from folder
$sql1 = "SELECT * FROM post WHERE post_id = {$post_id}";
$result = mysqli_query($conn, $sql1) or die("Query Failed : Select");
$row = mysqli_fetch_assoc($result);

if(file_exists("upload/".$row['post_img'])){
    unlink("upload/".$row['post_img']);
}

// Delete post & update category count
$sql = "DELETE FROM post WHERE post_id = {$post_id};";
$sql .= "UPDATE category SET post = post - 1 WHERE category_id = {$cat_id}";

if(mysqli_multi_query($conn, $sql)){
    // 🔥 Clear cache after deleting post
    clearCache();

    header("location: {$hostname}/admin/post.php");
} else {
    echo "Query Failed";
}
?>
