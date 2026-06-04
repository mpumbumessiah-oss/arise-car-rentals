<?php
session_start();
$page_title = 'Customers';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$conn = $db->connection();

$customers = $conn->query("
    SELECT customer_name, customer_email, customer_phone, 
           COUNT(*) as total_bookings, 
           SUM(total_amount) as total_spent,
           MAX(created_at) as last_booking
    FROM bookings 
    GROUP BY customer_email 
    ORDER BY total_spent DESC
");
?>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-6">Customer List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr><th>Name</th><th>Email</th><th>Phone</th><th>Bookings</th><th>Total Spent</th><th>Last Booking</th></tr>
            </thead>
            <tbody>
                <?php while($c = $customers->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= htmlspecialchars($c['customer_name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($c['customer_email']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($c['customer_phone']) ?></td>
                    <td class="px-4 py-2"><?= (int)$c['total_bookings'] ?></td>
                    <td class="px-4 py-2">AED <?= number_format($c['total_spent'] ?? 0) ?></td>
                    <td class="px-4 py-2"><?= date('d M Y', strtotime($c['last_booking'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>