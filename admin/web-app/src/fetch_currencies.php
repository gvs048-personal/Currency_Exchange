<?php
require_once '../config/database.php';

$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'currency_code';
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

// Validate the sorting column to prevent SQL injection
$allowed_columns = ['currency_code', 'currency_name', 'buy_price', 'sell_price'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'currency_code';
}

// Debug logging for received parameters
error_log("Sort column: $sort_column");
error_log("Sort order: $sort_order");

// Check if an ID is provided to fetch a specific currency
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM currencies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $currency = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($currency);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Currency not found"]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch EUR and USD rows
$static_sql = "SELECT * FROM currencies WHERE currency_code IN ('EUR', 'USD') ORDER BY FIELD(currency_code, 'EUR', 'USD')";
$static_result = $conn->query($static_sql);
$static_rows = $static_result->fetch_all(MYSQLI_ASSOC);

// Fetch remaining rows sorted dynamically
$sorted_sql = "SELECT * FROM currencies WHERE currency_code NOT IN ('EUR', 'USD') ORDER BY $sort_column $sort_order";
$sorted_result = $conn->query($sorted_sql);
$sorted_rows = $sorted_result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'staticRows' => $static_rows,
    'sortedRows' => $sorted_rows
]);
$conn->close();
?>
