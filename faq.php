<?php
// faq.php - Modern Clean Version

$page_title = "Frequently Asked Questions - Arise Car Rentals";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$faqs = [
    [
        'q' => 'What documents do I need to rent a car?',
        'a' => 'You need a valid driving license, passport or Emirates ID, and a credit card for security deposit.'
    ],
    [
        'q' => 'Is there a minimum age requirement?',
        'a' => 'Yes, drivers must be at least 21 years old with at least 1 year driving experience.'
    ],
    [
        'q' => 'Do you offer delivery and pickup?',
        'a' => 'Yes, we offer free delivery and pickup anywhere in Dubai.'
    ],
    [
        'q' => 'What is your cancellation policy?',
        'a' => 'Free cancellation up to 24 hours before pickup. Late cancellations may incur a fee.'
    ],
    [
        'q' => 'Can I extend my rental period?',
        'a' => 'Yes, contact us at least 24 hours before your return date to extend your booking.'
    ],
    [
        'q' => 'What happens if I get a traffic fine?',
        'a' => 'All traffic fines are the responsibility of the renter and will be charged accordingly.'
    ],
    [
        'q' => 'Is insurance included?',
        'a' => 'Basic insurance is included. Full coverage options are available on request.'
    ],
    [
        'q' => 'Can I drive outside Dubai?',
        'a' => 'Yes, but you must inform us before traveling to other emirates.'
    ],
];
?>

<!-- HERO -->
<section class="bg-gradient-to-r from-indigo-900 to-indigo-800 py-20 text-center">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold text-white">Frequently Asked Questions</h1>
        <p class="text-indigo-200 mt-3">Everything you need to know before renting</p>
    </div>
</section>

<!-- FAQ -->
<section class="py-20">
    <div class="container mx-auto px-6 max-w-3xl">

        <?php foreach ($faqs as $i => $faq): ?>

            <div class="mb-4 bg-white rounded-xl shadow overflow-hidden">

                <!-- QUESTION -->
                <button
                    class="faq-btn w-full flex justify-between items-center p-5 text-left font-semibold hover:bg-gray-50"
                >
                    <?= htmlspecialchars($faq['q']) ?>
                    <span class="transition-transform text-gray-400">⌄</span>
                </button>

                <!-- ANSWER -->
                <div class="faq-answer hidden px-5 pb-5 text-gray-600 border-t">
                    <?= htmlspecialchars($faq['a']) ?>
                </div>

            </div>

        <?php endforeach; ?>

    </div>
</section>

<!-- SCRIPT -->
<script>
document.querySelectorAll('.faq-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        const answer = btn.nextElementSibling;
        const icon = btn.querySelector('span');

        // toggle answer
        answer.classList.toggle('hidden');

        // rotate arrow
        icon.style.transform =
            answer.classList.contains('hidden')
                ? 'rotate(0deg)'
                : 'rotate(180deg)';
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>