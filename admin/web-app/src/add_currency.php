<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currency_code = $_POST['currency_code'];
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

    // Debugging: Print the $_FILES array
    print_r($_FILES);

    // Print the full path for debugging
    if (move_uploaded_file($_FILES["currency_logo"]["tmp_name"], $target_file)) {
        echo "File uploaded successfully: " . $target_file . "<br>";
        $currency_logo = basename($_FILES["currency_logo"]["name"]);
    } else {
        echo "File upload failed. Error code: " . $_FILES["currency_logo"]["error"] . "<br>";
        $currency_logo = null; // Set to null if upload fails
    }

    // Validate $currency_logo before database insertion
    if ($currency_logo) {
        $sql = "INSERT INTO currencies (currency_code, currency_name, buy_price, sell_price, currency_logo) VALUES (?, ?, ?, ?, ?)";
        echo "SQL Query: " . $sql . "<br>"; // Debugging: Print the SQL query
        echo "Bound values: " . $currency_code . ", " . $currency_name . ", " . $buy_price . ", " . $sell_price . ", " . $currency_logo . "<br>"; // Debugging: Print bound values

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdds", $currency_code, $currency_name, $buy_price, $sell_price, $currency_logo);
        if ($stmt->execute()) {
            echo "<script>alert('Currency added successfully.'); window.location.href='../index.php';</script>";
        } else {
            echo "Error executing query: " . $stmt->error . "<br>"; // Debugging: Print SQL execution error
        }
        $stmt->close();
    } else {
        echo "Error: Currency logo is invalid or upload failed.";
    }

    $conn->close();
}
?>
