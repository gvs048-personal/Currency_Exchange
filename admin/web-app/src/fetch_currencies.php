<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM currencies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $currency = $result->fetch_assoc();
        echo json_encode($currency);
    } else {
        echo json_encode(["error" => "Currency not found"]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

$sql = "SELECT * FROM currencies";
$result = $conn->query($sql);

$currencies = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $currencies[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($currencies);
$conn->close();
?>
