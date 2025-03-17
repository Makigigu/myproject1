<?php
// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Start session
session_start();

// Include database configuration
include("../include/config.php");

// Disable error reporting for production
error_reporting(0);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get JSON input data
    $input_data = json_decode(file_get_contents("php://input"), true);
    
    // If no data received or JSON is invalid
    if (!$input_data) {
        http_response_code(400);
        echo json_encode(array(
            "status" => "error",
            "message" => "ไม่สามารถรับข้อมูลได้ หรือรูปแบบ JSON ไม่ถูกต้อง"
        ));
        exit();
    }
    
    // Extract category data
    $category_name = $input_data['cat_id'] ?? '';
    $category_code = $input_data['cat_name'] ?? '';
   
    
    // Validate required fields
    if (empty($category_name) || empty($category_code)) {
        http_response_code(400);
        echo json_encode(array(
            "status" => "error",
            "message" => "กรุณากรอกชื่อประเภทสินค้าและรหัสประเภทสินค้า"
        ));
        exit();
    }
    
    try {
        // Check for duplicate category code
        $check = "SELECT COUNT(*) FROM category WHERE category_code = :category_code";
        $check_query = $dbh->prepare($check);
        $check_query->bindParam(':category_code', $category_code, PDO::PARAM_STR);
        $check_query->execute();
        
        if ($check_query->fetchColumn() > 0) {
            http_response_code(409); // Conflict
            echo json_encode(array(
                "status" => "error",
                "message" => "รหัสประเภทสินค้านี้มีอยู่ในระบบแล้ว กรุณาใช้รหัสอื่น"
            ));
            exit();
        }
        
        // Insert new category
        $sql = "INSERT INTO category(cat_id, cat_id, description, status) 
                VALUES(:cat_name, :cat_id, :description, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cat_id', $cat_id, PDO::PARAM_STR);
        $query->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
        
        
        if ($query->execute()) {
            $category_id = $dbh->lastInsertId();
            
            // Get the newly created category
            $get_category = "SELECT * FROM category WHERE id = :id";
            $get_query = $dbh->prepare($get_category);
            $get_query->bindParam(':id', $category_id, PDO::PARAM_INT);
            $get_query->execute();
            $category = $get_query->fetch(PDO::FETCH_ASSOC);
            
            http_response_code(201); // Created
            echo json_encode(array(
                "status" => "success",
                "message" => "เพิ่มประเภทสินค้าสำเร็จ",
                "data" => $category
            ));
        } else {
            throw new Exception("ไม่สามารถบันทึกข้อมูลได้");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            "status" => "error",
            "message" => "เกิดข้อผิดพลาดในระบบ: " . $e->getMessage()
        ));
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array(
        "status" => "error",
        "message" => "Method Not Allowed. Please use POST request."
    ));
}
?>