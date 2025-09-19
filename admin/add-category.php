<?php 
include "header.php"; 
include "config.php";
include "cache.php"; 
?>

<div id="admin-content">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
          <h1 class="admin-heading">Add New Category</h1>
      </div>
      <div class="col-md-offset-3 col-md-6">
          <!-- Form Start -->
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
              <div class="form-group">
                  <label>Category Name</label>
                  <input type="text" name="cat" class="form-control" placeholder="Category Name" required>
              </div>
              <input type="submit" name="save" class="btn btn-primary" value="Save">
          </form>
          <!-- /Form End -->

          <?php
            if(isset($_POST['save'])){
                $category = mysqli_real_escape_string($conn, $_POST['cat']);

                // Check if category already exists
                $sql = "SELECT category_name FROM category WHERE category_name = '{$category}'";
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    echo "<p style='color:red; text-align:center; margin:10px 0;'>Category already exists.</p>";
                } else {
                    // Insert new category
                    $sql = "INSERT INTO category (category_name) VALUES ('{$category}')";
                    if(mysqli_query($conn, $sql)){
                        
                        clearCache();

                        header("Location: {$hostname}/admin/category.php");
                        exit;
                    } else {
                        echo "<p style='color:red; text-align:center; margin:10px 0;'>Query Failed.</p>";
                    }
                }
            }

            mysqli_close($conn);
          ?>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>
