<?php
// admin/index.php
session_start();
$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance();
$conn = $db->connection();

// Count total cars
$carCount = $conn->query("SELECT COUNT(*) as count FROM cars WHERE is_available = 1")->fetch_assoc()['count'];

// Count total bookings
$bookingCount = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];

// Count pending bookings
$pendingCount = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'];

// Total revenue (confirmed/completed bookings)
$revenue = $conn->query("SELECT SUM(total_amount) as total FROM bookings WHERE status IN ('confirmed','completed')")->fetch_assoc()['total'] ?? 0;

// Recent bookings (last 5)
$recent = $conn->query("SELECT b.*, c.name as car_name FROM bookings b JOIN cars c ON b.car_id = c.id ORDER BY b.created_at DESC LIMIT 5");
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Cars</p>
                <p class="text-3xl font-bold"><?= $carCount ?></p>
            </div>
            <i class="fas fa-car text-4xl text-indigo-400"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold"><?= $bookingCount ?></p>
            </div>
            <i class="fas fa-calendar-check text-4xl text-green-400"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Pending Requests</p>
                <p class="text-3xl font-bold"><?= $pendingCount ?></p>
            </div>
            <i class="fas fa-clock text-4xl text-yellow-400"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Revenue (AED)</p>
                <p class="text-3xl font-bold"><?= number_format($revenue) ?></p>
            </div>
            <i class="fas fa-dollar-sign text-4xl text-purple-400"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Recent Bookings</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr><th class="px-4 py-2 text-left">Ref</th><th>Customer</th><th>Car</th><th>Dates</th><th>Amount</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php while($row = $recent->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= $row['booking_reference'] ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['car_name']) ?></td>
                    <td class="px-4 py-2"><?= date('d M', strtotime($row['pickup_date'])) ?> - <?= date('d M', strtotime($row['return_date'])) ?></td>
                    <td class="px-4 py-2">AED <?= number_format($row['total_amount']) ?></td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?= $row['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                              ($row['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>