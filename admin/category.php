<?php
include "header.php";
include "config.php";
include "cache.php"; 
?>

<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1 class="admin-heading">All Categories</h1>
            </div>
            <div class="col-md-2">
                <a class="add-new" href="add-category.php">add category</a>
            </div>
            <div class="col-md-12">

<?php
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$cacheFile = "cache/categories_page_{$page}.html";
$cacheTime = 300; // 5 minutes

if (isCacheValid($cacheFile, $cacheTime)) {
    
    echo file_get_contents($cacheFile);
} else {
    // Fetch categories from DB
    $sql = "SELECT * FROM category ORDER BY category_id DESC LIMIT {$offset}, {$limit}";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        ob_start(); // Start output buffering
        echo '<table class="content-table">';
        echo '<thead>
                <th>S.No.</th>
                <th>Category Name</th>
                <th>No. of Posts</th>
                <th>Edit</th>
                <th>Delete</th>
              </thead>
              <tbody>';

        $serial = $offset + 1;
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>
                    <td class='id'>{$serial}</td>
                    <td>{$row['category_name']}</td>
                    <td>{$row['post']}</td>
                    <td class='edit'><a href='update-category.php?id={$row['category_id']}'><i class='fa fa-edit'></i></a></td>
                    <td class='delete'><a href='delete-category.php?id={$row['category_id']}'><i class='fa fa-trash-o'></i></a></td>
                  </tr>";
            $serial++;
        }

        echo '</tbody></table>';

        // Pagination
        $sql1 = "SELECT COUNT(category_id) FROM category";
        $result1 = mysqli_query($conn, $sql1);
        $row_db = mysqli_fetch_row($result1);
        $total_record = $row_db[0];
        $total_page = ceil($total_record / $limit);

        echo "<ul class='pagination admin-pagination'>";
        if($page > 1){
            echo "<li><a href='category.php?page=".($page-1)."'>Prev</a></li>";
        }
        for($i=1; $i<=$total_page; $i++){
            $cls = ($i == $page) ? "btn-primary active" : "btn-primary";
            echo "<li><a href='category.php?page={$i}' class='{$cls}'>{$i}</a></li>";
        }
        if($total_page > $page){
            echo "<li><a href='category.php?page=".($page+1)."'>Next</a></li>";
        }
        echo "</ul>";

        // Save the output to cache
        $content = ob_get_contents();
        file_put_contents($cacheFile, $content);
        ob_end_flush();
    } else {
        echo "<h3>No Results Found.</h3>";
    }
}
?>

        </div>
    </div>
</div>

<?php include "footer.php"; ?>
