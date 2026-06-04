<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

$brand = $_GET['brand'] ?? 'all';
$conn = getConnection();

if ($brand === 'all') {
    $query = "SELECT id, name, image_main, price_per_day FROM cars WHERE is_available = 1";
    $result = $conn->query($query);
} else {
    $stmt = $conn->prepare("SELECT id, name, image_main, price_per_day FROM cars WHERE brand = ? AND is_available = 1");
    $stmt->bind_param("s", $brand);
    $stmt->execute();
    $result = $stmt->get_result();
}

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

echo json_encode($cars);
?>