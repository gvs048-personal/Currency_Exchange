<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: src/login.php');
    exit;
}
require_once 'config/database.php'; // Use the database connection from the included file

// Determine the sorting column and order
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'currency_code';
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

// Validate the sorting column to prevent SQL injection
$allowed_columns = ['currency_code', 'currency_name', 'buy_price', 'sell_price'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'currency_code';
}

// Ensure EUR and USD rows are always at the top on page load
$static_sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code IN ('EUR', 'USD') ORDER BY FIELD(currency_code, 'EUR', 'USD')";
$static_result = $conn->query($static_sql);

if (!$static_result) {
    die("Error fetching static rows: " . $conn->error);
}

$static_rows = $static_result->fetch_all(MYSQLI_ASSOC);

// Fetch remaining rows sorted dynamically by the selected column and order
$sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code NOT IN ('EUR', 'USD') ORDER BY $sort_column $sort_order";
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
    <title>Currency Converter</title>
    <link rel="stylesheet" href="assets/styles.css">
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <main class="currency-converter">
        <h2 style="text-align: center;">Currency Converter</h2>
        <form id="currency-form" action="src/add_currency.php" method="POST" enctype="multipart/form-data" style="width: 40%; margin: auto;">
            <div class="form-group">
                <label for="currency_code">Currency Code:</label>
                <input type="text" class="form-control" id="currency_code" name="currency_code" required>
            </div>

            <div class="form-group">
                <label for="currency_name">Currency Name:</label>
                <input type="text" class="form-control" id="currency_name" name="currency_name" required>
            </div>

            <div class="form-group">
                <label for="buy_price">Buy Price:</label>
                <input type="number" step="0.00001" class="form-control" id="buy_price" name="buy_price" required>
            </div>

            <div class="form-group">
                <label for="sell_price">Sell Price:</label>
                <input type="number" step="0.00001" class="form-control" id="sell_price" name="sell_price" required>
            </div>

            <div class="form-group">
                <label for="currency_logo">Currency Logo:</label>
                <input type="file" class="form-control-file" id="currency_logo" name="currency_logo" accept="image/png" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Entry</button>
        </form>

        <h3 class="mt-4">Existing Currencies</h3>
        <table id="currency-table" class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Currency Logo</th>
                    <th>
                        <a href="#" id="sort-currency-code" data-sort="currency_code" data-order="asc">
                            Currency Code
                            <span id="sort-arrow">&#9650;</span>
                        </a>
                    </th>
                    <th>Currency Name</th>
                    <th>Buy Price</th>
                    <th>Sell Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="currency-table-body">
                <?php foreach ($static_rows as $row): ?>
                    <tr>
                        <td><img src="uploads/<?= htmlspecialchars($row['currency_logo']) ?>" alt="Logo" width="50"></td>
                        <td><?= htmlspecialchars($row['currency_code']) ?></td>
                        <td><?= htmlspecialchars($row['currency_name']) ?></td>
                        <td><?= htmlspecialchars($row['buy_price']) ?></td>
                        <td><?= htmlspecialchars($row['sell_price']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editCurrency('<?= htmlspecialchars($row['currency_code']) ?>')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCurrency('<?= htmlspecialchars($row['currency_code']) ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="uploads/<?= htmlspecialchars($row['currency_logo']) ?>" alt="Logo" width="50"></td>
                            <td><?= htmlspecialchars($row['currency_code']) ?></td>
                            <td><?= htmlspecialchars($row['currency_name']) ?></td>
                            <td><?= htmlspecialchars($row['buy_price']) ?></td>
                            <td><?= htmlspecialchars($row['sell_price']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editCurrency('<?= htmlspecialchars($row['currency_code']) ?>')">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteCurrency('<?= htmlspecialchars($row['currency_code']) ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No currencies available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="src/logout.php" class="btn btn-danger" style="position: absolute; top: 10px; right: 10px;">Logout</a>
    </main>

    <script>
        async function fetchCurrencies(sort = 'currency_code', order = 'asc') {
            try {
                const response = await fetch(`src/fetch_currencies.php?sort=${sort}&order=${order}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch currencies');
                }
                const currencies = await response.json();
                const tableBody = document.querySelector('#currency-table-body');
                tableBody.innerHTML = '';

                // Append EUR and USD rows first
                currencies.staticRows.forEach(currency => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="uploads/${currency.currency_logo}" alt="Logo" width="50"></td>
                        <td>${currency.currency_code}</td>
                        <td>${currency.currency_name}</td>
                        <td>${currency.buy_price}</td>
                        <td>${currency.sell_price}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editCurrency(${currency.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCurrency(${currency.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                // Append sorted rows
                currencies.sortedRows.forEach(currency => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="uploads/${currency.currency_logo}" alt="Logo" width="50"></td>
                        <td>${currency.currency_code}</td>
                        <td>${currency.currency_name}</td>
                        <td>${currency.buy_price}</td>
                        <td>${currency.sell_price}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editCurrency(${currency.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCurrency(${currency.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load currencies. Please try again.');
            }
        }

        async function deleteCurrency(id) {
            if (confirm('Are you sure you want to delete this currency?')) {
                await fetch('src/delete_currency.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                });
                fetchCurrencies();
            }
        }

        async function editCurrency(id) {
            try {
                // Fetch the currency details by ID
                const response = await fetch(`src/fetch_currencies.php?id=${id}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch currency details');
                }
                const currency = await response.json();

                if (currency.error) {
                    alert(currency.error);
                    return;
                }

                // Populate the modal with the fetched data
                document.getElementById('edit-id').value = currency.id;
                document.getElementById('edit-currency_code').value = currency.currency_code;
                document.getElementById('edit-currency_name').value = currency.currency_name;
                document.getElementById('edit-sell_price').value = currency.sell_price;
                document.getElementById('edit-buy_price').value = currency.buy_price;

                // Show the modal
                document.getElementById('edit-modal').style.display = 'block';
            } catch (error) {
                console.error('Error fetching currency details:', error);
                alert('Failed to fetch currency details. Please try again.');
            }
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sortLink = document.querySelector('#sort-currency-code');
            sortLink.addEventListener('click', function (event) {
                event.preventDefault();

                const currentOrder = this.dataset.order;
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                this.dataset.order = newOrder;

                const sortArrow = document.querySelector('#sort-arrow');
                sortArrow.innerHTML = newOrder === 'asc' ? '&#9650;' : '&#9660;';

                fetchCurrencies(this.dataset.sort, newOrder);
            });

            fetchCurrencies();
        });
    </script>

    <!-- Edit Currency Modal -->
    <div id="edit-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc;">
        <h3>Edit Currency</h3>
        <form id="edit-form" action="src/edit_currency.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit-id" name="id">

            <div class="form-group">
                <label for="edit-currency_code">Currency Code:</label>
                <input type="text" class="form-control" id="edit-currency_code" name="currency_code" required>
            </div>

            <div class="form-group">
                <label for="edit-currency_name">Currency Name:</label>
                <input type="text" class="form-control" id="edit-currency_name" name="currency_name" required>
            </div>

            <div class="form-group">
                <label for="edit-buy_price">Buy Price:</label>
                <input type="number" step="0.00001" class="form-control" id="edit-buy_price" name="buy_price" required>
            </div>

            <div class="form-group">
                <label for="edit-sell_price">Sell Price:</label>
                <input type="number" step="0.00001" class="form-control" id="edit-sell_price" name="sell_price" required>
            </div>

            <div class="form-group">
                <label for="edit-currency_logo">Currency Logo:</label>
                <input type="file" class="form-control-file" id="edit-currency_logo" name="currency_logo" accept="image/png">
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
