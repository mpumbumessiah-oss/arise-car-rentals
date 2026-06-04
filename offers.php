<?php
$page_title = "Special Offers - Arise Car Rentals";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$offers = [
    [
        'title' => 'Weekend Special',
        'discount' => '20% OFF',
        'code' => 'WEEKEND20',
        'valid' => 'Valid until Mar 31, 2024',
        'icon' => 'fa-gem'
    ],
    [
        'title' => 'Long Term Rental',
        'discount' => '30% OFF',
        'code' => 'MONTHLY30',
        'valid' => 'For 30+ days rental',
        'icon' => 'fa-calendar-alt'
    ],
    [
        'title' => 'First Time Customer',
        'discount' => '15% OFF',
        'code' => 'FIRST15',
        'valid' => 'Valid for new customers',
        'icon' => 'fa-user-plus'
    ],
    [
        'title' => 'Corporate Discount',
        'discount' => '25% OFF',
        'code' => 'CORP25',
        'valid' => 'For business accounts',
        'icon' => 'fa-building'
    ],
];
?>

<!-- HERO -->
<section class="relative bg-gradient-to-r from-indigo-900 to-indigo-800 py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold text-white mb-4">
            Special Offers
        </h1>
        <p class="text-xl text-indigo-200">
            Exclusive deals and discounts
        </p>
    </div>
</section>

<!-- OFFERS -->
<section class="py-20">
<div class="container mx-auto px-6">

    <div class="grid md:grid-cols-2 gap-8">

        <?php foreach ($offers as $i => $offer): ?>

        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition"
             data-aos="fade-up"
             data-aos-delay="<?= $i * 100 ?>">

            <!-- HEADER -->
            <div class="flex items-center gap-4 mb-4">

                <div class="w-14 h-14 bg-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas <?= htmlspecialchars($offer['icon']) ?> text-white text-xl"></i>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-indigo-600">
                        <?= htmlspecialchars($offer['discount']) ?>
                    </h2>
                    <h3 class="text-lg font-semibold">
                        <?= htmlspecialchars($offer['title']) ?>
                    </h3>
                </div>

            </div>

            <p class="text-gray-600 mb-4">
                <?= htmlspecialchars($offer['valid']) ?>
            </p>

            <!-- CODE BOX -->
            <div class="bg-gray-100 rounded-xl p-3 flex justify-between items-center">

                <span class="font-mono text-sm">
                    <?= htmlspecialchars($offer['code']) ?>
                </span>

                <button
                    class="copy-btn text-indigo-600 hover:text-indigo-800 transition"
                    data-code="<?= htmlspecialchars($offer['code']) ?>">
                    <i class="fas fa-copy"></i> Copy
                </button>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</div>
</section>

<!-- COPY SCRIPT -->
<script>
document.querySelectorAll(".copy-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
        try {
            await navigator.clipboard.writeText(btn.dataset.code);

            // modern feedback instead of alert
            btn.innerHTML = "✔ Copied";

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy"></i> Copy';
            }, 1500);

        } catch (err) {
            console.error("Copy failed", err);
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>