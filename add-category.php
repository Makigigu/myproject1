<?php 
session_start();
include("../include/config.php");
error_reporting(0);

// Process form submission
if(isset($_POST['submit'])) {
    // Get form data
    $category_name = $_POST['category_name'];
    $category_code = $_POST['category_code'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 1 : 0;
    
    // Validate inputs
    if(empty($category_name) || empty($category_code)) {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } else {
        // Check for duplicate category code
        $check = "SELECT * FROM category WHERE category_code = :category_code";
        $check_query = $dbh->prepare($check);
        $check_query->bindParam(':category_code', $category_code, PDO::PARAM_STR);
        $check_query->execute();
        
        if($check_query->rowCount() > 0) {
            $error = "รหัสประเภทสินค้านี้มีอยู่ในระบบแล้ว กรุณาใช้รหัสอื่น";
        } else {
            // Insert new category
            $sql = "INSERT INTO category(category_name, category_code, description, status) 
                    VALUES(:category_name, :category_code, :description, :status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':category_name', $category_name, PDO::PARAM_STR);
            $query->bindParam(':category_code', $category_code, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            
            if($query->execute()) {
                $success = "เพิ่มประเภทสินค้าสำเร็จ";
                // Redirect after a short delay
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'manage_category.php';
                    }, 1500);
                </script>";
            } else {
                $error = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>เพิ่มประเภทสินค้า | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="เพิ่มประเภทสินค้า | Admin Panel" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    
    <!-- Third Party CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="css/adminlte.css" />
    
    <style>
        .required-label::after {
            content: " *";
            color: red;
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
                    <span class="brand-text fw-light">Admin Panel</span>
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
                            <h3 class="mb-0">เพิ่มประเภทสินค้า</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="dashboard.php">หน้าหลัก</a></li>
                                <li class="breadcrumb-item"><a href="manage_category.php">จัดการประเภทสินค้า</a></li>
                                <li class="breadcrumb-item active" aria-current="page">เพิ่มประเภทสินค้า</li>
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
                            <?php if(isset($error)) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-exclamation-triangle-fill"></i> ข้อผิดพลาด!</strong> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            
                            <?php if(isset($success)) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-check-circle-fill"></i> สำเร็จ!</strong> <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white">
                                    <h3 class="card-title">กรอกข้อมูลประเภทสินค้า</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" class="needs-validation" novalidate>
                                        <div class="mb-3 row">
                                            <label for="category_name" class="col-sm-2 col-form-label required-label">ชื่อประเภทสินค้า</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="category_name" name="category_name" required>
                                                <div class="invalid-feedback">
                                                    กรุณากรอกชื่อประเภทสินค้า
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label for="category_code" class="col-sm-2 col-form-label required-label">รหัสประเภท</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="category_code" name="category_code" required>
                                                <div class="invalid-feedback">
                                                    กรุณากรอกรหัสประเภทสินค้า
                                                </div>
                                               
                                            </div>
                                        </div>
                                        
                                
                                        <div class="mb-3 row">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="submit" name="submit" class="btn btn-primary">
                                                    <i class="bi bi-save"></i> บันทึกข้อมูล
                                                </button>
                                                <a href="manage_category.php" class="btn btn-secondary">
                                                    <i class="bi bi-x-circle"></i> ยกเลิก
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
    
    <!-- Form validation -->
    <script>
    (() => {
      'use strict'

      // Fetch all forms we want to apply validation styles to
      const forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
    </script>
    
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
</body>
</html>