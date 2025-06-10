<?php
// Admin login page
session_start();
require_once 'web-app/config/database.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM admins WHERE username = ? AND password = ?');
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header('Location: dashboard.php');
    } else {
        echo '<p>Invalid credentials</p>';
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Login</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>
</div>

<?php
// Fetch static rows (Euro and Dollar) from the database
$static_sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code IN ('EUR', 'USD') ORDER BY FIELD(currency_code, 'EUR', 'USD')";
$static_result = $conn->query($static_sql);

if (!$static_result) {
    die("Error fetching static rows: " . $conn->error);
}

$static_rows = $static_result->fetch_all(MYSQLI_ASSOC);

// Fetch remaining rows sorted by currency_code
$sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code NOT IN ('EUR', 'USD') ORDER BY currency_code ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}
?>

<div class="container mt-5">
    <h2>Currency Exchange Rates</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Currency Code</th>
                <th>Currency Name</th>
                <th>Buy Price</th>
                <th>Sell Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($static_rows as $row): ?>
                <tr>
                    <td>
                        <img src="web-app/uploads/<?= htmlspecialchars($row['currency_logo']) ?>" alt="<?= htmlspecialchars($row['currency_code']) ?>" style="width: 30px; height: auto; margin-right: 10px;">
                        <?= htmlspecialchars($row['currency_code']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['currency_name']) ?></td>
                    <td><?= htmlspecialchars($row['buy_price']) ?></td>
                    <td><?= htmlspecialchars($row['sell_price']) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="web-app/uploads/<?= htmlspecialchars($row['currency_logo']) ?>" alt="<?= htmlspecialchars($row['currency_code']) ?>" style="width: 30px; height: auto; margin-right: 10px;">
                            <?= htmlspecialchars($row['currency_code']) ?>
                        </td>
                        <td><?= htmlspecialchars($row['currency_name']) ?></td>
                        <td><?= htmlspecialchars($row['buy_price']) ?></td>
                        <td><?= htmlspecialchars($row['sell_price']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No currencies available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
