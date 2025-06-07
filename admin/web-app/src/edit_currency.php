<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $currency_code = $_POST['currency_code'];
    $currency_name = $_POST['currency_name'];
    $sell_price = $_POST['sell_price'];
    $buy_price = $_POST['buy_price'];

    // Handle file upload if a new file is provided
    if (!empty($_FILES['currency_logo']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["currency_logo"]["name"]);
        move_uploaded_file($_FILES["currency_logo"]["tmp_name"], $target_file);
        $currency_logo = $target_file;

        $sql = "UPDATE currencies SET currency_code = ?, currency_name = ?, sell_price = ?, buy_price = ?, currency_logo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddsi", $currency_code, $currency_name, $sell_price, $buy_price, $currency_logo, $id);
    } else {
        $sql = "UPDATE currencies SET currency_code = ?, currency_name = ?, sell_price = ?, buy_price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddi", $currency_code, $currency_name, $sell_price, $buy_price, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Currency updated successfully.'); window.location.href='../index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>