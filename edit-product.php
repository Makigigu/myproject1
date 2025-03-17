<?php
if (isset($_GET['id'])) {
    $pro_id = $_GET['id'];

    // ดึงข้อมูลสินค้าที่จะทำการแก้ไข
    $sql = "SELECT * FROM product WHERE pro_id = :pro_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // รับข้อมูลจากฟอร์ม
        $pro_name = $_POST['pro_name'];
        $cat_id = $_POST['cat_id'];
        $pro_price = $_POST['pro_price'];
        $pro_cost = $_POST['pro_cost'];
        $pro_img = $_FILES['pro_img']['name'] ? $_FILES['pro_img']['name'] : $result->pro_img;

        // ย้ายไฟล์รูปภาพ (ถ้ามีการอัพโหลดรูปใหม่)
        if ($_FILES['pro_img']['name']) {
            move_uploaded_file($_FILES['pro_img']['tmp_name'], 'uploads/' . $pro_img);
        }

        // คำสั่ง SQL สำหรับอัพเดตข้อมูลสินค้า
        $sql_update = "UPDATE product SET pro_name = :pro_name, cat_id = :cat_id, pro_price = :pro_price, pro_cost = :pro_cost, pro_img = :pro_img WHERE pro_id = :pro_id";
        $query_update = $dbh->prepare($sql_update);
        $query_update->bindParam(':pro_name', $pro_name);
        $query_update->bindParam(':cat_id', $cat_id);
        $query_update->bindParam(':pro_price', $pro_price);
        $query_update->bindParam(':pro_cost', $pro_cost);
        $query_update->bindParam(':pro_img', $pro_img);
        $query_update->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);

        if ($query_update->execute()) {
            echo "แก้ไขสินค้าสำเร็จ!";
            header("Location: manage_product.php");
        } else {
            echo "เกิดข้อผิดพลาดในการแก้ไขสินค้า!";
        }
    }
}
?>

<!-- ฟอร์มแก้ไขสินค้า -->
<form action="edit_product.php?id=<?php echo $result->pro_id; ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="pro_name">ชื่อสินค้า</label>
        <input type="text" class="form-control" id="pro_name" name="pro_name" value="<?php echo htmlspecialchars($result->pro_name); ?>" required>
    </div>

    <div class="form-group">
        <label for="cat_id">หมวดหมู่สินค้า</label>
        <select class="form-control" id="cat_id" name="cat_id" required>
            <?php
            // ดึงรายชื่อหมวดหมู่จากฐานข้อมูล
            $cat_query = "SELECT * FROM category";
            $stmt = $dbh->prepare($cat_query);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($categories as $category) {
                $selected = ($category->cat_id == $result->cat_id) ? 'selected' : '';
                echo "<option value='" . $category->cat_id . "' $selected>" . htmlspecialchars($category->cat_name) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="pro_price">ราคาสินค้า</label>
        <input type="number" class="form-control" id="pro_price" name="pro_price" step="0.01" value="<?php echo number_format($result->pro_price, 2); ?>" required>
    </div>

    <div class="form-group">
        <label for="pro_cost">ต้นทุนสินค้า</label>
        <input type="number" class="form-control" id="pro_cost" name="pro_cost" step="0.01" value="<?php echo number_format($result->pro_cost, 2); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="pro_img">รูปภาพสินค้า (ถ้ามีการเปลี่ยน)</label>
        <input type="file" class="form-control-file" id="pro_img" name="pro_img">
    </div>

    <button type="submit" class="btn btn-primary">แก้ไขสินค้า</button>
</form>