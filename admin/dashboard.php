<?php
// Admin dashboard
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

require_once 'web-app/config/database.php';

if (isset($_POST['update'])) {
    $currency = $_POST['currency'];
    $rate = $_POST['rate'];

    $stmt = $conn->prepare('INSERT INTO exchange_rates (currency, rate) VALUES (?, ?) ON DUPLICATE KEY UPDATE rate = ?');
    $stmt->bind_param('sdd', $currency, $rate, $rate);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="currency" class="form-label">Currency</label>
            <input type="text" class="form-control" id="currency" name="currency" required>
        </div>
        <div class="mb-3">
            <label for="rate" class="form-label">Exchange Rate</label>
            <input type="number" step="0.01" class="form-control" id="rate" name="rate" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
