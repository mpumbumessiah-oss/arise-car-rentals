<?php
$page_title = "Our Services - Arise Car Rentals";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-indigo-900 to-indigo-800 py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold text-white mb-4">
            Our Premium Services
        </h1>
        <p class="text-xl text-indigo-200">
            Experience luxury beyond just car rental
        </p>
    </div>
</section>

<?php
$services = [
    ['icon' => 'fa-car', 'title' => 'Chauffeur Service', 'desc' => 'Professional drivers for your luxury experience', 'color' => 'indigo'],
    ['icon' => 'fa-plane', 'title' => 'Airport Transfer', 'desc' => 'Convenient pickup and dropoff at DXB Airport', 'color' => 'blue'],
    ['icon' => 'fa-calendar-check', 'title' => 'Long Term Rental', 'desc' => 'Special rates for monthly and yearly rentals', 'color' => 'green'],
    ['icon' => 'fa-shield-alt', 'title' => 'Full Insurance', 'desc' => 'Comprehensive coverage for peace of mind', 'color' => 'purple'],
    ['icon' => 'fa-road', 'title' => 'Roadside Assistance', 'desc' => '24/7 support anywhere in UAE', 'color' => 'red'],
    ['icon' => 'fa-gem', 'title' => 'Luxury Fleet', 'desc' => 'Access to exclusive supercars and limousines', 'color' => 'pink'],
    ['icon' => 'fa-tachometer-alt', 'title' => 'Unlimited Miles', 'desc' => 'Drive without mileage restrictions', 'color' => 'orange'],
    ['icon' => 'fa-wifi', 'title' => 'WiFi Enabled Cars', 'desc' => 'Stay connected on the road', 'color' => 'teal'],
    ['icon' => 'fa-child', 'title' => 'Child Seats', 'desc' => 'Safety seats available upon request', 'color' => 'yellow'],
];
?>

<!-- Services Grid -->
<section class="py-20">
    <div class="container mx-auto px-6">

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php foreach ($services as $index => $service): ?>

                <div class="bg-white rounded-2xl p-8 shadow-lg text-center hover:shadow-xl transition"
                     data-aos="fade-up"
                     data-aos-delay="<?= $index * 100 ?>">

                    <!-- ICON BOX (SAFE VERSION) -->
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">

                        <i class="fas <?= htmlspecialchars($service['icon']) ?> text-3xl text-indigo-600"></i>

                    </div>

                    <h3 class="text-xl font-bold mb-3">
                        <?= htmlspecialchars($service['title']) ?>
                    </h3>

                    <p class="text-gray-600">
                        <?= htmlspecialchars($service['desc']) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>