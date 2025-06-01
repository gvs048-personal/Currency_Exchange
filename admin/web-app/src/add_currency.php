<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currency_name = $_POST['currency_name'];
    $sell_price = $_POST['sell_price'];
    $buy_price = $_POST['buy_price'];

    // Handle file upload
    $target_dir = __DIR__ . '/../uploads/';
    $target_file = $target_dir . basename($_FILES["currency_logo"]["name"]);

    // Ensure the uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    move_uploaded_file($_FILES["currency_logo"]["tmp_name"], $target_file);

    $currency_logo = basename($_FILES["currency_logo"]["name"]);

    // Insert into database
    $sql = "INSERT INTO currencies (currency_name, sell_price, buy_price, currency_logo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdds", $currency_name, $sell_price, $buy_price, $currency_logo);

    if ($stmt->execute()) {
        echo "<script>alert('Currency added successfully.'); window.location.href='../index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
