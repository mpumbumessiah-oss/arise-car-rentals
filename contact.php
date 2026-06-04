<?php
// contact.php - Modern Clean Version

$page_title = "Contact Us - Arise Car Rentals Dubai";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$success = '';
$error = '';

// --------------------
// FORM HANDLING
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $phone && $message) {

        // TODO: Save to DB or send email here
        $success = "Thank you for contacting us! We'll respond within 24 hours.";

    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!-- HERO -->
<section class="bg-gradient-to-r from-indigo-900 to-indigo-800 py-20 text-center">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold text-white">Get in Touch</h1>
        <p class="text-indigo-200 mt-3">We are available 24/7 to assist you</p>
    </div>
</section>

<!-- CONTACT SECTION -->
<section class="py-20">
    <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12">

        <!-- FORM -->
        <div>

            <h2 class="text-3xl font-bold mb-6">Send us a Message</h2>

            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">

                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    class="w-full border rounded-lg p-3"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class="w-full border rounded-lg p-3"
                    required
                >

                <input
                    type="tel"
                    name="phone"
                    placeholder="Phone Number"
                    class="w-full border rounded-lg p-3"
                    required
                >

                <textarea
                    name="message"
                    placeholder="Your Message"
                    class="w-full border rounded-lg p-3"
                    rows="5"
                    required
                ></textarea>

                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold"
                >
                    Send Message
                </button>

            </form>
        </div>

        <!-- CONTACT INFO -->
        <div class="bg-gray-900 text-white rounded-2xl p-8">

            <h3 class="text-2xl font-bold mb-6">Contact Information</h3>

            <div class="space-y-5 text-sm">

                <div>
                    <p class="font-semibold">Phone</p>
                    <p class="text-gray-300"><?= htmlspecialchars(SITE_PHONE) ?></p>
                </div>

                <div>
                    <p class="font-semibold">Email</p>
                    <p class="text-gray-300"><?= htmlspecialchars(SITE_EMAIL) ?></p>
                </div>

                <div>
                    <p class="font-semibold">Location</p>
                    <p class="text-gray-300">Sheikh Zayed Road, Dubai</p>
                </div>

                <div>
                    <p class="font-semibold">WhatsApp</p>
                    <p class="text-gray-300">+971 50 123 4567</p>
                </div>

            </div>

            <hr class="my-6 border-gray-700">

            <h4 class="font-bold mb-2">Working Hours</h4>
            <p class="text-gray-300 text-sm">Mon - Sun: 8:00 AM - 10:00 PM</p>

        </div>

    </div>
</section>

<!-- FAQ -->
<section class="bg-gray-100 py-20">
    <div class="container mx-auto px-6 max-w-3xl">

        <h2 class="text-3xl font-bold text-center mb-10">Frequently Asked Questions</h2>

        <div class="space-y-4">

            <div class="bg-white p-5 rounded-xl shadow">
                <h3 class="font-bold">What documents do I need?</h3>
                <p class="text-gray-600">Driving license, passport/ID, and deposit card.</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <h3 class="font-bold">Minimum age?</h3>
                <p class="text-gray-600">21 years with at least 1 year driving experience.</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <h3 class="font-bold">Do you deliver cars?</h3>
                <p class="text-gray-600">Yes, free delivery anywhere in Dubai.</p>
            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>