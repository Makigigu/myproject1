<?php 
session_start();
include("../include/config.php");
error_reporting(0);
 
// Delete category
if(isset($_GET['did'])){
    $did = $_GET['did'];
    $sql = "DELETE FROM category WHERE cat_id=:did";
    $query = $dbh->prepare($sql);
    $query->bindParam(':did', $did, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Deleted Category Successfully')</script>";
    echo "<script>window.location.href='manage_category.php'</script>";
}

// Add category
if(isset($_POST['addCategory'])) {
    $cat_name = $_POST['cat_name'];
    $sql = "INSERT INTO category (cat_name) VALUES (:cat_name)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);

    if($query->execute()) {
        echo "<script>alert('Add category successfully!'); window.location.href='manage_category.php';</script>";
    } else {
        echo "<script>alert('Error! Please try again.');</script>";
    }
}

// Update category
if(isset($_POST['updateCategory'])) {
    $cat_id = $_POST['cat_id'];
    $cat_name = $_POST['cat_name'];

    $sql = "UPDATE category SET cat_name=:cat_name WHERE cat_id=:cat_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
    $query->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "<script>alert('Update category successfully!'); window.location.href='manage_category.php';</script>";
    } else {
        echo "<script>alert('Error! Please try again.');</script>";
    }
}


?>
 
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Manage Category | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Manage Category | Admin Panel" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    
    <!-- Third Party CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="css/adminlte.css" />
    
    <!-- Additional CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    
    <!-- Custom styles -->
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .action-buttons .btn {
            margin: 2px;
        }
        .category-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .card-title {
            font-weight: 600;
        }
        .badge-category-count {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            margin-left: 8px;
        }
        .add-button {
            margin-bottom: 15px;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        <?php include("include/navbar.php"); ?>
        
        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="dashboard.php" class="brand-link">
                    <img src="./assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">Fiker Shop</span>
                </a>
            </div>
            <?php include("include/sidebar.php"); ?>
        </aside>
        
        <!-- Main Content -->
        <main class="app-main">
            <!-- Content Header -->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Manage Category</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">List of all product category
                                            <?php
                                            $categoryCount = $dbh->query("SELECT COUNT(*) FROM category")->fetchColumn();
                                            echo '<span class="badge bg-primary badge-category-count">'.$categoryCount.'</span>';
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                            <i class="bi bi-plus-circle"></i> Add category
                                        </button>
                                    </div>

                                    <!-- Modal เพิ่มประเภทสินค้า -->
                                    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addCategoryModalLabel">Add category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                        <div class="mb-3">
                                                            
    </div>
                                                            <label class="form-label">ชื่อประเภทสินค้า</label>
                                                            <input type="text" name="cat_name" class="form-control" required>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" name="addCategory" class="btn btn-primary">บันทึก</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                   <!-- Modal แก้ไขประเภทสินค้า -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">แก้ไขประเภทสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <!-- เพิ่ม input hidden เพื่อเก็บค่า cat_id -->
                    <input type="hidden" name="cat_id" id="edit_cat_id">
                    <div class="mb-3">
                        <label class="form-label">ชื่อประเภทสินค้า</label>
                        <input type="text" id="edit_cat_name" name="cat_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" name="updateCategory" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>


                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover category-table">
                                            <thead>
                                                <tr class="table-light">
                                                    <th style="width: 50px" class="text-center">ลำดับ</th>
                                                    <th>รหัสประเภท</th>
                                                    <th>ชื่อประเภทสินค้า</th>
                                                    <th style="width: 200px" class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM category ORDER BY cat_id ASC";
                                                $query = $dbh->prepare($ret);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                
                                                if($query->rowCount() > 0) {
                                                    foreach($results as $row) { ?>
                                                        <tr class="align-middle">
                                                            <td class="text-center"><?php echo $cnt; ?></td>
                                                            <td><?php echo htmlentities($row->cat_id); ?></td>
                                                            <td><?php echo htmlentities($row->cat_name); ?></td>
                                                            <td class="text-center">
                                                                <div class="action-buttons">
                                                                    <button type="button" 
                                                                        class="btn btn-warning btn-sm edit-btn" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#editCategoryModal"
                                                                        data-id="<?php echo $row->cat_id; ?>"
                                                                        data-name="<?php echo $row->cat_name; ?>">
                                                                        <i class="bi bi-pencil-square"></i> แก้ไข
                                                                    </button>
                                                                    <a href="manage_category.php?did=<?php echo $row->cat_id; ?>" 
                                                                       class="btn btn-danger btn-sm" 
                                                                       onclick="return confirm('คุณต้องการลบประเภทสินค้า <?php echo $row->cat_name; ?> ใช่หรือไม่?');">
                                                                       <i class="bi bi-trash"></i> ลบ
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    $cnt++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">ไม่พบข้อมูลประเภทสินค้า</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pagination (if needed) -->
                                <?php if($query->rowCount() > 10) { ?>
                                <div class="card-footer bg-white clearfix">
                                    <ul class="pagination pagination-sm m-0 float-end">
                                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page<li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <?php include("include/footer.php"); ?>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="../../dist/js/adminlte.js"></script>
    
    <!-- OverlayScrollbars -->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    
    <!-- Edit Category Modal Script -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Get all edit buttons
        const editButtons = document.querySelectorAll('.edit-btn');
        
        // Add click event to all edit buttons
        editButtons.forEach(button => {
          button.addEventListener('click', function() {
            // Get category data from data attributes
            const catId = this.getAttribute('data-id');
            const catName = this.getAttribute('data-name');
            
            // Set values in the edit modal
            document.getElementById('edit_cat_id').value = catId;
            document.getElementById('edit_cat_name').value = catName;
          });
        });
      });
    </script>
</body>
</html>