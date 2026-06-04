<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$current_page = basename($_SERVER['PHP_SELF']);

$jobs = [
    [
        'title' => 'Customer Service Representative',
        'type' => 'Full-time',
        'location' => 'Dubai',
        'description' => 'Join our customer support team and assist clients with bookings and inquiries.'
    ],
    [
        'title' => 'Fleet Manager',
        'type' => 'Full-time',
        'location' => 'Dubai',
        'description' => 'Manage vehicle maintenance, scheduling, and fleet operations.'
    ],
    [
        'title' => 'Marketing Specialist',
        'type' => 'Full-time',
        'location' => 'Dubai',
        'description' => 'Lead digital marketing campaigns and grow brand presence.'
    ],
    [
        'title' => 'Driver / Chauffeur',
        'type' => 'Full-time',
        'location' => 'Dubai',
        'description' => 'Provide premium chauffeur services for luxury clients.'
    ],
];
?>

<!-- HERO -->
<section class="bg-gradient-to-r from-indigo-900 to-indigo-800 py-20 text-center">
    <h1 class="text-5xl font-bold text-white">Join Our Team</h1>
    <p class="text-indigo-200 mt-2">Build your career with Arise Car Rentals</p>
</section>

<!-- JOBS -->
<section class="py-20">
    <div class="container mx-auto px-6 max-w-4xl">

        <h2 class="text-3xl font-bold text-center mb-10">Current Openings</h2>

        <?php foreach ($jobs as $job): ?>

            <div class="bg-white shadow-lg rounded-2xl p-6 mb-6">

                <div class="flex justify-between items-start">

                    <div>
                        <h3 class="text-xl font-bold">
                            <?= htmlspecialchars($job['title']) ?>
                        </h3>

                        <div class="text-sm text-gray-500 mt-1 space-x-4">
                            <span>📌 <?= htmlspecialchars($job['location']) ?></span>
                            <span>💼 <?= htmlspecialchars($job['type']) ?></span>
                        </div>
                    </div>

                    <button
                        class="apply-btn bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition"
                        data-title="<?= htmlspecialchars($job['title']) ?>">
                        Apply
                    </button>

                </div>

                <p class="text-gray-600 mt-3">
                    <?= htmlspecialchars($job['description']) ?>
                </p>

            </div>

        <?php endforeach; ?>

    </div>
</section>

<!-- MODAL -->
<div id="applyModal" class="fixed inset-0 hidden bg-black/60 items-center justify-center z-50">

    <div class="bg-white w-full max-w-md p-6 rounded-2xl">

        <h3 class="text-2xl font-bold mb-4">
            Apply for <span id="jobTitle"></span>
        </h3>

        <form id="applicationForm">

            <input type="hidden" id="position" name="position">

            <input type="text" placeholder="Full Name" class="w-full border p-3 mb-3 rounded" required>

            <input type="email" placeholder="Email" class="w-full border p-3 mb-3 rounded" required>

            <input type="tel" placeholder="Phone" class="w-full border p-3 mb-3 rounded" required>

            <input type="file" class="w-full border p-3 mb-3 rounded">

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700">
                Submit Application
            </button>

            <button type="button" id="closeModal"
                class="w-full mt-3 text-gray-500">
                Cancel
            </button>

        </form>

    </div>
</div>

<!-- SCRIPT -->
<script>
const modal = document.getElementById('applyModal');
const title = document.getElementById('jobTitle');
const position = document.getElementById('position');

document.querySelectorAll('.apply-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        title.textContent = btn.dataset.title;
        position.value = btn.dataset.title;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
});

document.getElementById('closeModal').addEventListener('click', () => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
});

document.getElementById('applicationForm').addEventListener('submit', (e) => {
    e.preventDefault();

    alert("Application submitted successfully!");
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    e.target.reset();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>