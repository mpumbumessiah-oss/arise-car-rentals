<?php
session_start();
$page_title = 'Manage Bookings';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$conn = $db->connection();
$message = '';
$filter_status = $_GET['status'] ?? 'all';

// CSRF token for status updates
if (empty($_SESSION['admin_csrf_token'])) {
    $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
}

// Update booking status with CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (!hash_equals($_SESSION['admin_csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }
    $booking_id = (int)$_POST['booking_id'];
    $new_status = $_POST['status'];
    $allowed = ['pending','confirmed','active','completed','cancelled'];
    if (in_array($new_status, $allowed)) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $booking_id);
        $stmt->execute();
        $message = "Status updated.";
    }
}

// Build query using prepared statement to avoid SQL injection
$sql = "SELECT b.*, c.name as car_name FROM bookings b JOIN cars c ON b.car_id = c.id";
$params = [];
$types = "";

if ($filter_status !== 'all') {
    $sql .= " WHERE b.status = ?";
    $params[] = $filter_status;
    $types .= "s";
}
$sql .= " ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$bookings = $stmt->get_result();
?>

<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">All Bookings</h2>
        <div>
            <select onchange="window.location.href='bookings.php?status='+this.value" class="border rounded px-3 py-1">
                <option value="all" <?= $filter_status == 'all' ? 'selected' : '' ?>>All Status</option>
                <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="confirmed" <?= $filter_status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="active" <?= $filter_status == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="completed" <?= $filter_status == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $filter_status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
    </div>
    <?php if ($message): ?><div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr><th>Ref</th><th>Customer</th><th>Car</th><th>Pickup</th><th>Return</th><th>Amount</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while($b = $bookings->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= htmlspecialchars($b['booking_reference']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($b['customer_name']) ?><br><small class="text-gray-500"><?= htmlspecialchars($b['customer_email']) ?></small></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($b['car_name']) ?></td>
                    <td class="px-4 py-2"><?= date('d M Y', strtotime($b['pickup_date'])) ?> <br> <?= htmlspecialchars($b['pickup_time']) ?></td>
                    <td class="px-4 py-2"><?= date('d M Y', strtotime($b['return_date'])) ?> <br> <?= htmlspecialchars($b['return_time']) ?></td>
                    <td class="px-4 py-2">AED <?= number_format($b['total_amount']) ?></td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?= $b['status']=='pending'?'bg-yellow-100 text-yellow-800':($b['status']=='confirmed'?'bg-green-100 text-green-800':'bg-gray-100')?>">
                            <?= ucfirst(htmlspecialchars($b['status'])) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <form method="POST" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token']) ?>">
                            <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                            <select name="status" class="border rounded text-sm p-1">
                                <option value="pending" <?= $b['status']=='pending'?'selected':'' ?>>Pending</option>
                                <option value="confirmed" <?= $b['status']=='confirmed'?'selected':'' ?>>Confirmed</option>
                                <option value="active" <?= $b['status']=='active'?'selected':'' ?>>Active</option>
                                <option value="completed" <?= $b['status']=='completed'?'selected':'' ?>>Completed</option>
                                <option value="cancelled" <?= $b['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="text-indigo-600 text-sm ml-1">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>