<?php
$host = 'localhost';
$db = 'shop_db1'; // ← sửa chỗ này cho đúng tên database
$user = 'root';   // ← tên tài khoản database của bạn
$pass = '';       // ← mật khẩu database (nếu có)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>
