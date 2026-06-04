<?php
$current_page = basename($_SERVER['PHP_SELF']);
$page_title = "Testimonials - Premium Car Rental Dubai";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$testimonials = [
    [
        'name' => 'Mohammed Al Rashed',
        'role' => 'Business Executive',
        'rating' => 5,
        'text' => 'Excellent service! The car was spotless and delivered on time. Highly recommend!',
        'image' => 'https://randomuser.me/api/portraits/men/1.jpg'
    ],
    [
        'name' => 'Sarah Johnson',
        'role' => 'Tourist',
        'rating' => 5,
        'text' => 'Best car rental experience in Dubai. Professional staff and amazing cars.',
        'image' => 'https://randomuser.me/api/portraits/women/2.jpg'
    ],
    [
        'name' => 'David Chen',
        'role' => 'Expat',
        'rating' => 5,
        'text' => 'Great prices and even better service. Will definitely use again.',
        'image' => 'https://randomuser.me/api/portraits/men/3.jpg'
    ],
    [
        'name' => 'Fatima Al Zahra',
        'role' => 'Business Owner',
        'rating' => 5,
        'text' => 'Luxury cars at affordable prices. The team is very professional.',
        'image' => 'https://randomuser.me/api/portraits/women/4.jpg'
    ],
    [
        'name' => 'Thomas Anderson',
        'role' => 'Tourist',
        'rating' => 4,
        'text' => 'Smooth booking process and excellent car condition.',
        'image' => 'https://randomuser.me/api/portraits/men/5.jpg'
    ],
    [
        'name' => 'Lisa Martinez',
        'role' => 'Frequent Traveler',
        'rating' => 5,
        'text' => 'My go-to car rental in Dubai. Always reliable!',
        'image' => 'https://randomuser.me/api/portraits/women/6.jpg'
    ],
];
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-indigo-900 to-indigo-800 py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold text-white mb-4 animate-fade-up">
            What Our Customers Say
        </h1>
        <p class="text-xl text-indigo-200 animate-fade-up delay-100">
            Join thousands of satisfied customers
        </p>
    </div>
</section>

<!-- Testimonials Grid -->
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php foreach ($testimonials as $index => $t): ?>
                <div class="bg-white rounded-2xl p-8 shadow-lg"
                     data-aos="fade-up"
                     data-aos-delay="<?php echo $index * 100; ?>">

                    <div class="flex items-center gap-4 mb-6">
                        <img src="<?php echo htmlspecialchars($t['image']); ?>"
                             class="w-16 h-16 rounded-full object-cover">

                        <div>
                            <h4 class="font-bold text-gray-800">
                                <?php echo htmlspecialchars($t['name']); ?>
                            </h4>
                            <p class="text-sm text-gray-500">
                                <?php echo htmlspecialchars($t['role']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex text-yellow-400 mb-4">
                        <?php for ($i = 0; $i < (int)$t['rating']; $i++): ?>
                            <i class="fas fa-star"></i>
                        <?php endfor; ?>
                    </div>

                    <p class="text-gray-600 italic">
                        "<?php echo htmlspecialchars($t['text']); ?>"
                    </p>

                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- Add Testimonial Form -->
<section class="bg-gray-100 py-20">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">

            <h2 class="text-3xl font-bold text-center mb-8">
                Share Your Experience
            </h2>

            <form id="testimonialForm" class="bg-white rounded-2xl p-8 shadow-lg">

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <input type="text" placeholder="Your Name" class="form-input" required>
                    <input type="email" placeholder="Email Address" class="form-input" required>
                </div>

                <div class="mb-6">
                    <textarea rows="4" placeholder="Your Testimonial" class="form-input" required></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Your Rating</label>
                    <div class="flex gap-2 text-2xl text-gray-300" id="ratingStars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="far fa-star cursor-pointer hover:text-yellow-400"
                               data-rating="<?php echo $i; ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">
                    Submit Testimonial
                </button>

            </form>
        </div>
    </div>
</section>

<script>
let selectedRating = 0;

const stars = document.querySelectorAll('#ratingStars i');

stars.forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = parseInt(star.dataset.rating);

        stars.forEach((s, index) => {
            if (index < selectedRating) {
                s.classList.remove('far');
                s.classList.add('fas', 'text-yellow-400');
            } else {
                s.classList.remove('fas', 'text-yellow-400');
                s.classList.add('far');
            }
        });
    });
});

document.getElementById('testimonialForm').addEventListener('submit', (e) => {
    e.preventDefault();

    alert('Thank you for your feedback! Your testimonial will be reviewed.');

    e.target.reset();
    selectedRating = 0;

    stars.forEach(s => {
        s.classList.remove('fas', 'text-yellow-400');
        s.classList.add('far');
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>