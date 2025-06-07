<?php
require_once 'config/database.php';

$sql = "SELECT currency_name, buy_price, sell_price, currency_logo FROM currencies";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rates</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
        }
        .currency-table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .currency-table th, .currency-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .currency-table th {
            background-color: #007bff;
            color: white;
        }
        h1.text-center {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Exchange Rates</h1>
    <table class="currency-table">
        <thead>
        <tr>
            <th>Currency Name</th>
            <th>Buy Price</th>
            <th>Sell Price</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($row['currency_logo']) ?>" alt="<?= htmlspecialchars($row['currency_name']) ?>" style="width: 30px; height: auto; margin-right: 10px;">
                        <?= htmlspecialchars($row['currency_name']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['buy_price']) ?></td>
                    <td><?= htmlspecialchars($row['sell_price']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No currencies available.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
