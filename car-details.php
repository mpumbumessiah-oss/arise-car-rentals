<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// =========================
// GET CAR – redirect if not found (BEFORE any output)
// =========================
$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$car = getCarById($car_id);

if (!$car) {
    redirect('cars.php');
}

// =========================
// SAFE TO OUTPUT HTML NOW
// =========================
require_once __DIR__ . '/includes/header.php';

$current_page = basename($_SERVER['PHP_SELF']);

// =========================
// GET SIMILAR CARS
// =========================
$db = Database::getInstance();

$similar_cars = $db->fetchAll(
    "SELECT * FROM cars 
     WHERE brand = ? AND id != ? 
     LIMIT 3",
    [$car['brand'], $car['id']]
);
?>

<!-- CAR DETAILS -->
<section class="py-12">
    <div class="container mx-auto px-6">

        <a href="<?= url('cars.php'); ?>" class="text-indigo-600 flex items-center gap-2 mb-6">
            <i class="fas fa-arrow-left"></i> Back to Fleet
        </a>

        <div class="grid lg:grid-cols-2 gap-12">

            <!-- IMAGE -->
            <div>
                <img src="<?= htmlspecialchars($car['image_main']) ?>"
                     class="w-full h-96 object-cover rounded-2xl shadow-lg">
            </div>

            <!-- DETAILS -->
            <div>

                <h1 class="text-4xl font-bold">
                    <?= htmlspecialchars($car['name']) ?>
                </h1>

                <p class="text-gray-500 mb-2">
                    <?= htmlspecialchars($car['brand']) ?> • <?= (int)$car['year'] ?>
                </p>

                <span class="inline-block bg-green-100 text-green-600 px-3 py-1 rounded-full mb-4">
                    <i class="fas fa-check-circle"></i> Available
                </span>

                <!-- STATS -->
                <div class="grid grid-cols-2 gap-4 my-6">

                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Transmission</p>
                        <p class="font-semibold"><?= ucfirst($car['transmission']) ?></p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Fuel</p>
                        <p class="font-semibold"><?= ucfirst($car['fuel_type']) ?></p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Seats</p>
                        <p class="font-semibold"><?= (int)$car['seats'] ?></p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500">Year</p>
                        <p class="font-semibold"><?= (int)$car['year'] ?></p>
                    </div>

                </div>

                <!-- DESCRIPTION -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-2">Description</h3>
                    <p class="text-gray-600">
                        <?= nl2br(htmlspecialchars($car['description'])) ?>
                    </p>
                </div>

                <!-- PRICES -->
                <div class="bg-indigo-50 p-6 rounded-2xl mb-6 grid grid-cols-3 text-center">

                    <div>
                        <p class="text-sm text-gray-500">Daily</p>
                        <p class="font-bold text-indigo-600">
                            AED <?= number_format((float)$car['price_per_day']) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Weekly</p>
                        <p class="font-bold">
                            AED <?= number_format((float)($car['price_per_week'] ?? $car['price_per_day'] * 6)) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Monthly</p>
                        <p class="font-bold">
                            AED <?= number_format((float)($car['price_per_month'] ?? $car['price_per_day'] * 20)) ?>
                        </p>
                    </div>

                </div>

                <!-- BOOK BUTTON -->
                <a href="<?= url('booking.php?car_id=' . $car['id']); ?>"
                   class="block text-center bg-indigo-600 text-white py-4 rounded-xl font-semibold hover:bg-indigo-700 transition">
                    Book This Car
                </a>

            </div>
        </div>
    </div>
</section>

<!-- SIMILAR CARS -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">

        <h2 class="text-3xl font-bold text-center mb-8">
            You May Also Like
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <?php foreach ($similar_cars as $similar): ?>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    <img src="<?= htmlspecialchars($similar['image_main']) ?>"
                         class="w-full h-48 object-cover">

                    <div class="p-5">

                        <h3 class="font-bold text-lg">
                            <?= htmlspecialchars($similar['name']) ?>
                        </h3>

                        <div class="flex justify-between items-center mt-3">

                            <span class="text-indigo-600 font-bold">
                                AED <?= number_format((float)$similar['price_per_day']) ?>
                            </span>

                            <a href="<?= url('car-details.php?id=' . $similar['id']) ?>"
                               class="text-indigo-600 hover:underline">
                                View →
                            </a>

                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>