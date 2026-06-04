<?php
declare(strict_types=1);

// safer page detection
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
$page_title = "About Us - Premium Car Rental Dubai";

// central includes
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<main>

<!-- HERO -->
<section class="relative bg-gradient-to-r from-indigo-900 to-indigo-800 py-20">
    <div class="container mx-auto px-6 text-center">

        <h1 class="text-5xl font-bold text-white mb-4 animate-fade-up">
            <?= htmlspecialchars($page_title) ?>
        </h1>

        <p class="text-xl text-indigo-200 animate-fade-up delay-100">
            Dubai's Premier Luxury Car Rental Service Since 2015
        </p>

    </div>
</section>

<!-- COMPANY STORY -->
<section class="py-20">
    <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

        <img
            src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2"
            alt="Our Story"
            loading="lazy"
            class="rounded-2xl shadow-2xl"
        >

        <div>

            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wide">
                Our Story
            </span>

            <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">
                A Legacy of Luxury and Excellence
            </h2>

            <p class="text-gray-600 mb-4 leading-relaxed">
                Founded in 2015, Arise Car Rentals delivers premium driving experiences across Dubai.
            </p>

            <p class="text-gray-600 mb-6 leading-relaxed">
                With 50+ luxury vehicles and thousands of happy customers, we focus on trust and quality.
            </p>

            <div class="flex items-center gap-6">

                <div>
                    <div class="text-3xl font-bold text-indigo-600">50+</div>
                    <div class="text-gray-500 text-sm">Premium Cars</div>
                </div>

                <div class="w-px h-10 bg-gray-300"></div>

                <div>
                    <div class="text-3xl font-bold text-indigo-600">5000+</div>
                    <div class="text-gray-500 text-sm">Happy Customers</div>
                </div>

                <div class="w-px h-10 bg-gray-300"></div>

                <div>
                    <div class="text-3xl font-bold text-indigo-600">9+</div>
                    <div class="text-gray-500 text-sm">Years Experience</div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- VALUES -->
<section class="bg-gray-100 py-20">
    <div class="container mx-auto px-6 text-center">

        <h2 class="text-4xl font-bold text-gray-800 mb-4">What Drives Us</h2>
        <p class="text-gray-600 mb-12">Core principles that define our service</p>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="bg-white p-8 rounded-2xl shadow">
                <i class="fas fa-crown text-3xl text-indigo-600 mb-4"></i>
                <h3 class="font-bold text-xl">Quality First</h3>
                <p class="text-gray-600">High-end fleet and premium service.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow">
                <i class="fas fa-handshake text-3xl text-indigo-600 mb-4"></i>
                <h3 class="font-bold text-xl">Trust</h3>
                <p class="text-gray-600">Transparent pricing with no hidden fees.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow">
                <i class="fas fa-headset text-3xl text-indigo-600 mb-4"></i>
                <h3 class="font-bold text-xl">Support</h3>
                <p class="text-gray-600">24/7 customer assistance.</p>
            </div>

        </div>

    </div>
</section>

<!-- CTA -->
<section class="bg-indigo-600 py-16 text-center">

    <h2 class="text-3xl font-bold text-white mb-4">
        Ready to Experience Luxury?
    </h2>

    <p class="text-indigo-200 mb-6">
        Book your dream car today
    </p>

    <a href="<?= url('cars.php') ?>"
       class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:shadow-lg transition">
        Browse Fleet →
    </a>

</section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>