<?php
session_start();
include("../include/config.php");
error_reporting(0);

// ลบสินค้า
if (isset($_POST['delete_product'])) {
    $pro_id = $_POST['pro_id'];

    $delete_query = "DELETE FROM product WHERE pro_id = :pro_id";
    $delete_stmt = $dbh->prepare($delete_query);
    $delete_stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        echo '<script>alert("Delete Product Successfully!"); window.location.href="manage_product.php";</script>';
    } else {
        echo '<script>alert("Error Delete!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการสินค้า</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="manage_user.php" class="list-group-item list-group-item-action">Users</a>
                <a href="manage_category.php" class="list-group-item list-group-item-action">Category</a>
                <a href="manage_product.php" class="list-group-item list-group-item-action active">Product</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Product</h2>
                <a href="add-product-api.php" class="btn btn-primary">Add Product</a>
            </div>

            <h5 class="mt-5">List Product</h5>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="table-light">
                        <th>รูป</th>
                        <th>ชื่อ</th>
                        <th>ราคา</th>
                        <th>หมวดหมู่</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT p.*, c.cat_name FROM product p JOIN category c ON p.cat_id = c.cat_id ORDER BY p.pro_id ASC";
                    $stmt = $dbh->prepare($query);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                        echo "<tr>
                            <td><img src='uploads/{$row->pro_img}' width='50' height='50'></td>
                            <td>{$row->pro_name}</td>
                            <td>฿{$row->pro_price}</td>
                            <td>{$row->cat_name}</td>
                            <td>
                                <a href='add-product-api.php?edit_id={$row->pro_id}' class='btn btn-warning btn-sm'>แก้ไข</a>
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"ยืนยันการลบ?\");'>
                                    <input type='hidden' name='pro_id' value='{$row->pro_id}'>
                                    <button type='submit' name='delete_product' class='btn btn-danger btn-sm'>ลบ</button>
                                </form>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>