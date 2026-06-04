<?php
$page_title = "Locations - Arise Car Rentals";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$locations = [
    [
        'name' => 'Dubai Airport',
        'address' => 'Terminal 3, Dubai International Airport',
        'hours' => '24/7'
    ],
    [
        'name' => 'Downtown Dubai',
        'address' => 'Sheikh Zayed Road, Near Burj Khalifa',
        'hours' => '8 AM - 10 PM'
    ],
    [
        'name' => 'Dubai Marina',
        'address' => 'Marina Mall, Level 1',
        'hours' => '9 AM - 9 PM'
    ],
    [
        'name' => 'JBR',
        'address' => 'The Beach, JBR',
        'hours' => '10 AM - 10 PM'
    ],
    [
        'name' => 'Palm Jumeirah',
        'address' => 'Golden Mile Galleria',
        'hours' => '9 AM - 8 PM'
    ],
    [
        'name' => 'Business Bay',
        'address' => 'Bay Square',
        'hours' => '8 AM - 8 PM'
    ],
];
?>

<!-- HERO -->
<section class="relative bg-gradient-to-r from-indigo-900 to-indigo-800 py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold text-white mb-4">
            Our Locations
        </h1>
        <p class="text-xl text-indigo-200">
            Convenient pickup points across Dubai
        </p>
    </div>
</section>

<!-- MAIN -->
<section class="py-20">
<div class="container mx-auto px-6">

    <div class="grid lg:grid-cols-2 gap-12">

        <!-- MAP -->
        <div class="rounded-2xl overflow-hidden shadow-xl h-96">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3610.1788989872737!2d55.270783!3d25.197197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f43348a67e24b%3A0xff45e502e1ceb7e2!2sBurj%20Khalifa"
                width="100%"
                height="100%"
                style="border:0;"
                loading="lazy"
                allowfullscreen>
            </iframe>
        </div>

        <!-- LOCATIONS -->
        <div>
            <h2 class="text-3xl font-bold mb-6">
                Find a Location Near You
            </h2>

            <div class="space-y-4">

                <?php foreach ($locations as $loc): ?>
                    <div class="bg-white rounded-xl p-5 shadow hover:shadow-lg transition">

                        <div class="flex gap-4">

                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-indigo-600"></i>
                            </div>

                            <div>
                                <h3 class="font-bold text-lg">
                                    <?= htmlspecialchars($loc['name']) ?>
                                </h3>

                                <p class="text-gray-500 text-sm">
                                    <?= htmlspecialchars($loc['address']) ?>
                                </p>

                                <p class="text-indigo-600 text-sm mt-1">
                                    <i class="far fa-clock"></i>
                                    <?= htmlspecialchars($loc['hours']) ?>
                                </p>
                            </div>

                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    </div>

</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>