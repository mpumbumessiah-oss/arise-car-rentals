<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

/* =========================
   SAFE INPUT HANDLING
========================= */

$reference = filter_input(INPUT_GET, 'ref', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

/* =========================
   BASIC VALIDATION
========================= */

$is_valid = !empty($reference);
?>

<main>

<section class="py-20">
    <div class="container mx-auto px-6 text-center max-w-2xl">

        <!-- ICON -->
        <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-green-600 text-5xl"></i>
        </div>

        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            Booking Confirmed!
        </h1>

        <p class="text-gray-600 text-lg mb-6">
            Thank you for choosing Arise Car Rentals
        </p>

        <?php if ($is_valid): ?>

            <div class="bg-gray-100 rounded-xl p-4 mb-6">
                <p class="text-gray-600">Your Booking Reference:</p>

                <p class="text-2xl font-bold text-indigo-600">
                    <?= htmlspecialchars($reference) ?>
                </p>
            </div>

        <?php else: ?>

            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                Invalid booking reference. Please check your email.
            </div>

        <?php endif; ?>

        <!-- NEXT STEPS -->
        <div class="bg-blue-50 rounded-xl p-6 text-left mb-8">

            <h3 class="font-bold text-blue-800 mb-3">
                What's Next?
            </h3>

            <ul class="space-y-2 text-blue-700">

                <li>
                    <i class="fas fa-envelope mr-2"></i>
                    Confirmation email sent to your inbox
                </li>

                <li>
                    <i class="fas fa-phone mr-2"></i>
                    Our team will contact you within 2 hours
                </li>

                <li>
                    <i class="fas fa-id-card mr-2"></i>
                    Bring driving license & Emirates ID
                </li>

            </ul>

        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex flex-col md:flex-row gap-4 justify-center">

            <a href="<?= url('index.php') ?>"
               class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Home
            </a>

            <a href="<?= url('cars.php') ?>"
               class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                Browse Cars
            </a>

        </div>

    </div>
</section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>