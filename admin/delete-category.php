<?php
    include 'config.php';

    //normal user jno ei page open korte na pare normal browse kore o ,ei jnno 
    if($_SESSION["user_role"] == '0'){
      header("Location: {$hostname}/admin/post.php");
    }
    $cat_id = $_GET["id"];

    /*sql to delete a record*/
    $sql = "DELETE FROM category WHERE category_id ='{$cat_id}'";

    if (mysqli_query($conn, $sql)) {
        header("location:{$hostname}/admin/category.php");
    }

    mysqli_close($conn);

?>
