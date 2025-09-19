<?php
include 'config.php';
include 'cache.php'; // Include cache helper

// Only admin can delete category
if($_SESSION["user_role"] == '0'){
    header("Location: {$hostname}/admin/post.php");
    exit;
}

$cat_id = (int) $_GET["id"]; // safe casting

// Delete the category
$sql = "DELETE FROM category WHERE category_id = {$cat_id}";
if (mysqli_query($conn, $sql)){
    // 🔥 Clear cache after deleting category
    clearCache();

    header("Location: {$hostname}/admin/category.php");
    exit;
} else {
    echo "<p style='color:red; text-align:center; margin:10px 0;'>Query Failed.</p>";
}

mysqli_close($conn);
?>
