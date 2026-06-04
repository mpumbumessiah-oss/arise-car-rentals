<?php
// cars.php - Modern Clean Version

$page_title = "Our Premium Fleet - Arise Car Rentals";

// Include core infrastructure configurations first
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php'; // Ensure database class file is explicitly pulled in
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Instantiate and extract active database connection safely
$db = Database::getInstance();
$conn = $db->connection();

// -------------------------
// INPUT SANITIZATION
// -------------------------
$brand_filter        = trim($_GET['brand'] ?? '');
// ... rest of the code stays identical
// -------------------------
// INPUT SANITIZATION
// -------------------------
$brand_filter        = trim($_GET['brand'] ?? '');
$transmission_filter = trim($_GET['transmission'] ?? '');
$fuel_filter         = trim($_GET['fuel'] ?? '');
$min_price           = (int)($_GET['min_price'] ?? 0);
$max_price           = (int)($_GET['max_price'] ?? 5000);
$search              = trim($_GET['search'] ?? '');

// -------------------------
// BUILD SAFE QUERY
// -------------------------
$sql = "SELECT * FROM cars WHERE is_available = 1";
$params = [];
$types = "";

if ($brand_filter !== '') {
    $sql .= " AND brand = ?";
    $params[] = $brand_filter;
    $types .= "s";
}

if ($transmission_filter !== '') {
    $sql .= " AND transmission = ?";
    $params[] = $transmission_filter;
    $types .= "s";
}

if ($fuel_filter !== '') {
    $sql .= " AND fuel_type = ?";
    $params[] = $fuel_filter;
    $types .= "s";
}

if ($min_price > 0) {
    $sql .= " AND price_per_day >= ?";
    $params[] = $min_price;
    $types .= "i";
}

if ($max_price > 0 && $max_price < 5000) {
    $sql .= " AND price_per_day <= ?";
    $params[] = $max_price;
    $types .= "i";
}

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR brand LIKE ? OR model LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sss";
}

$sql .= " ORDER BY is_featured DESC, price_per_day ASC";

// -------------------------
// EXECUTE QUERY SAFELY
// -------------------------
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$cars = $stmt->get_result();

// -------------------------
// BRANDS FILTER
// -------------------------
$brands = $conn->query("SELECT DISTINCT brand FROM cars WHERE is_available = 1 ORDER BY brand");
?>

<!-- HERO -->
<section class="bg-gradient-to-r from-indigo-900 to-indigo-800 py-16 text-center">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold text-white mb-3">Our Premium Fleet</h1>
        <p class="text-indigo-200 text-lg">Choose from Dubai's finest luxury vehicles</p>
    </div>
</section>

<!-- FILTERS -->
<section class="bg-white shadow-md py-6 sticky top-16 z-40">
    <div class="container mx-auto px-6">
        <form method="GET" class="grid md:grid-cols-5 gap-4">

            <input
                type="text"
                name="search"
                placeholder="Search cars..."
                value="<?= htmlspecialchars($search) ?>"
                class="border rounded-xl px-4 py-3 w-full"
            >

            <select name="brand" class="border rounded-xl px-4 py-3">
                <option value="">All Brands</option>
                <?php while ($b = $brands->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($b['brand']) ?>"
                        <?= $brand_filter === $b['brand'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['brand']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="transmission" class="border rounded-xl px-4 py-3">
                <option value="">Transmission</option>
                <option value="automatic" <?= $transmission_filter === 'automatic' ? 'selected' : '' ?>>Automatic</option>
                <option value="manual" <?= $transmission_filter === 'manual' ? 'selected' : '' ?>>Manual</option>
            </select>

            <select name="fuel" class="border rounded-xl px-4 py-3">
                <option value="">Fuel Type</option>
                <option value="petrol" <?= $fuel_filter === 'petrol' ? 'selected' : '' ?>>Petrol</option>
                <option value="diesel" <?= $fuel_filter === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                <option value="electric" <?= $fuel_filter === 'electric' ? 'selected' : '' ?>>Electric</option>
                <option value="hybrid" <?= $fuel_filter === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
            </select>

            <button class="bg-indigo-600 text-white rounded-xl font-semibold">
                Filter
            </button>
        </form>
    </div>
</section>

<!-- RESULTS -->
<section class="py-6 bg-gray-50">
    <div class="container mx-auto px-6 text-gray-600">
        Found <strong><?= $cars->num_rows ?></strong> cars available
    </div>
</section>

<!-- CAR GRID -->
<section class="py-12">
    <div class="container mx-auto px-6">

        <?php if ($cars->num_rows > 0): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                <?php while ($car = $cars->fetch_assoc()): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                        <img
                            src="<?= htmlspecialchars($car['image_main']) ?>"
                            class="h-64 w-full object-cover"
                        >

                        <div class="p-6">

                            <h3 class="text-xl font-bold">
                                <?= htmlspecialchars($car['name']) ?>
                            </h3>

                            <p class="text-gray-500 text-sm">
                                <?= htmlspecialchars($car['brand']) ?> • <?= $car['year'] ?>
                            </p>

                            <div class="mt-4 flex justify-between">
                                <span class="font-bold text-indigo-600 text-xl">
                                    AED <?= number_format($car['price_per_day']) ?>
                                </span>

                                <span class="text-sm text-gray-500">
                                    /day
                                </span>
                            </div>

                            <div class="mt-5 flex gap-3">

                                <a href="<?= url('car-details.php?id=' . $car['id']) ?>"
                                   class="flex-1 bg-gray-100 text-center py-2 rounded-xl">
                                    Details
                                </a>

                                <a href="<?= url('booking.php?car_id=' . $car['id']) ?>"
                                   class="flex-1 bg-indigo-600 text-white text-center py-2 rounded-xl">
                                    Book
                                </a>

                            </div>

                        </div>
                    </div>
                <?php endwhile; ?>

            </div>

        <?php else: ?>
            <div class="text-center py-20 text-gray-500">
                No cars found.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>