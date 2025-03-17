<?php 
session_start();
include("../include/config.php");
error_reporting(0);
 
if(isset($_GET['did'])){
    $did = $_GET['did'];
    $sql = "DELETE FROM userdata WHERE id=:did";
    $query = $dbh->prepare($sql);
    $query->bindParam(':did', $did, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Deleted user successfully')</script>";
    echo "<script>window.location.href='manage_user.php'</script>";
}
if(isset($_POST['addUser'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $loginpassword = $_POST['loginpassword'];

    $sql = "INSERT INTO userdata (fullname, username, useremail, usermobile, loginpassword) VALUES (:fullname, :username, :email, :mobile, :loginpassword)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':loginpassword', $loginpassword, PDO::PARAM_STR);

    if($query->execute()) {
        echo "<script>alert('Add user successfully!'); window.location.href='manage_user.php';</script>";
    } else {
        echo "<script>alert('Error! Please try again.');</script>";
    }
}

?>
 
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Users | Fiker Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Users | Admin Panel" />
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
        .user-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .card-title {
            font-weight: 600;
        }
        .badge-user-count {
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
                            <h3 class="mb-0">Users</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                                        <h3 class="card-title">List of all users 
                                            <?php
                                            $userCount = $dbh->query("SELECT COUNT(*) FROM userdata")->fetchColumn();
                                            echo '<span class="badge bg-primary badge-user-count">'.$userCount.'</span>';
                                            ?>
                                        </h3>
                                        
                                    </div>
                                </div>
                                <div class="mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus"></i> Add users
    </button>
</div>

<!-- Modal เพิ่มผู้ใช้งาน -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้งาน</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">อีเมล</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">เบอร์โทร</label>
                        <input type="text" name="mobile" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="text" name="loginpassword" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" name="addUser" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover user-table">
                                            <thead>
                                                <tr class="table-light">
                                                    <th style="width: 50px" class="text-center">ลำดับ</th>
                                                    <th>ชื่อ-นามสกุล</th>
                                                    <th>ชื่อผู้ใช้งาน</th>
                                                    <th>อีเมล</th>
                                                    <th>เบอร์โทร</th>
                                                    <th>รหัสผ่าน</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM userdata ORDER BY id DESC";
                                                $query = $dbh->prepare($ret);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                
                                                if($query->rowCount() > 0) {
                                                    foreach($results as $row) { ?>
                                                        <tr class="align-middle">
                                                            <td class="text-center"><?php echo $cnt; ?></td>
                                                            <td><?php echo htmlentities($row->fullname); ?></td>
                                                            <td><?php echo htmlentities($row->username); ?></td>
                                                            <td><?php echo htmlentities($row->useremail); ?></td>
                                                            <td><?php echo htmlentities($row->usermobile); ?></td>
                                                            <td><?php echo htmlentities($row->loginpassword); ?></td>
                                                            <td class="text-center">
                                                                <div class="action-buttons">
                                                                    <a href="edit-users.php?id=<?php echo $row->id; ?>" 
                                                                       class="btn btn-warning btn-sm">
                                                                       <i class="bi bi-pencil-square"></i> แก้ไข
                                                                    </a>
                                                                    <a href="manage_user.php?did=<?php echo $row->id; ?>" 
                                                                       class="btn btn-danger btn-sm" 
                                                                       onclick="return confirm('คุณต้องการลบผู้ใช้งาน <?php echo $row->fullname; ?> ใช่หรือไม่?');">
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
                                                        <td colspan="6" class="text-center">ไม่พบข้อมูลผู้ใช้งาน</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pagination (if needed) -->
                                <?php if($query->rowCount() > 0) { ?>
                                <div class="card-footer bg-white clearfix">
                                    <ul class="pagination pagination-sm m-0 float-end">
                                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
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
</body>
</html>