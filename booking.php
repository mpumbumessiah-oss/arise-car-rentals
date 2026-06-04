<?php
declare(strict_types=1);
// 1. No output before this line
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// 2. Process everything BEFORE any HTML output
$car_id = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;
$car = getCarById($car_id);

if (!$car) {
    redirect('cars.php');  // safe – no output yet
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$form_data = []; // for repopulating

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("Invalid request");
    }

    // Collect input
    $form_data = [
        'customer_name'   => trim($_POST['customer_name'] ?? ''),
        'customer_email'  => trim($_POST['customer_email'] ?? ''),
        'customer_phone'  => trim($_POST['customer_phone'] ?? ''),
        'driver_license'  => trim($_POST['driver_license'] ?? ''),
        'pickup_date'     => $_POST['pickup_date'] ?? '',
        'return_date'     => $_POST['return_date'] ?? '',
        'pickup_time'     => $_POST['pickup_time'] ?? '',
        'return_time'     => $_POST['return_time'] ?? '',
        'pickup_location' => trim($_POST['pickup_location'] ?? ''),
        'special_requests'=> trim($_POST['special_requests'] ?? ''),
    ];

    // Validation
    if (empty($form_data['customer_name'])) $errors[] = "Full name is required.";
    if (!filter_var($form_data['customer_email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($form_data['customer_phone'])) $errors[] = "Phone number is required.";
    if (empty($form_data['driver_license'])) $errors[] = "Driver license is required.";

    try {
        $pickup = new DateTime($form_data['pickup_date']);
        $return = new DateTime($form_data['return_date']);
        if ($return <= $pickup) $errors[] = "Return date must be after pickup date.";
        if ($pickup < new DateTime('today')) $errors[] = "Pickup date cannot be in the past.";
    } catch (Exception $e) {
        $errors[] = "Invalid date format.";
    }

    if (empty($form_data['pickup_time']) || empty($form_data['return_time'])) {
        $errors[] = "Please select pickup and return times.";
    }
    if (empty($form_data['pickup_location'])) $errors[] = "Pickup location is required.";

    // If no errors, check availability and create booking
    if (empty($errors)) {
        if (!checkCarAvailability($car_id, $form_data['pickup_date'], $form_data['return_date'])) {
            $errors[] = "Car not available for selected dates.";
        } else {
            $total_amount = calculateTotalAmount($car_id, $form_data['pickup_date'], $form_data['return_date']);
            $booking_data = [
                'customer_name'     => $form_data['customer_name'],
                'customer_email'    => $form_data['customer_email'],
                'customer_phone'    => $form_data['customer_phone'],
                'car_id'            => $car_id,
                'pickup_date'       => $form_data['pickup_date'],
                'return_date'       => $form_data['return_date'],
                'pickup_time'       => $form_data['pickup_time'],
                'return_time'       => $form_data['return_time'],
                'pickup_location'   => $form_data['pickup_location'],
                'return_location'   => $form_data['pickup_location'],
                'total_amount'      => $total_amount,
                'special_requests'  => $form_data['special_requests'],
                'driver_license'    => $form_data['driver_license']
            ];

            $booking = createBooking($booking_data);
            if ($booking) {
                sendBookingConfirmation($booking_data, $car, $booking['reference']);
                $_SESSION['last_booking_ref'] = $booking['reference'];
                redirect('booking-success.php?ref=' . urlencode($booking['reference']));
            } else {
                $errors[] = "Failed to create booking. Please try again.";
            }
        }
    }
}

// 3. Now it's safe to start output – include header
$page_title = "Book Your Car - Arise Car Rentals";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-900 to-indigo-800 py-12">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold text-white">
            Book Your <?= htmlspecialchars($car['name']) ?>
        </h1>
        <p class="text-indigo-200">Complete your reservation below</p>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-6 max-w-5xl">
        <!-- Car summary (unchanged) -->
        <div class="bg-white shadow rounded-xl p-6 mb-8 flex gap-6">
            <img src="<?= htmlspecialchars($car['image_main']) ?>" class="w-40 h-28 object-cover rounded-lg" alt="<?= htmlspecialchars($car['name']) ?>">
            <div>
                <h2 class="text-2xl font-bold"><?= htmlspecialchars($car['name']) ?></h2>
                <p class="text-gray-500"><?= htmlspecialchars($car['brand']) ?> • <?= $car['year'] ?></p>
                <div class="text-indigo-600 font-bold text-xl mt-2">AED <?= number_format((float)$car['price_per_day']) ?>/day</div>
                <div class="mt-2 text-sm font-semibold">Total: AED <span id="totalAmount">0</span></div>
            </div>
        </div>

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form with repopulation -->
        <form method="POST" class="bg-white p-8 rounded-xl shadow">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="grid md:grid-cols-2 gap-4">
                <input type="text" name="customer_name" placeholder="Full Name" required
                       value="<?= htmlspecialchars($form_data['customer_name'] ?? '') ?>"
                       class="border p-3 rounded">
                <input type="email" name="customer_email" placeholder="Email" required
                       value="<?= htmlspecialchars($form_data['customer_email'] ?? '') ?>"
                       class="border p-3 rounded">
                <input type="tel" name="customer_phone" placeholder="Phone" required
                       value="<?= htmlspecialchars($form_data['customer_phone'] ?? '') ?>"
                       class="border p-3 rounded">
                <input type="text" name="driver_license" placeholder="Driver License" required
                       value="<?= htmlspecialchars($form_data['driver_license'] ?? '') ?>"
                       class="border p-3 rounded">

                <input type="date" name="pickup_date" required
                       value="<?= htmlspecialchars($form_data['pickup_date'] ?? '') ?>"
                       min="<?= date('Y-m-d') ?>"
                       class="border p-3 rounded">
                <input type="date" name="return_date" required
                       value="<?= htmlspecialchars($form_data['return_date'] ?? '') ?>"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       class="border p-3 rounded">

                <select name="pickup_time" class="border p-3 rounded">
                    <option value="09:00" <?= (($form_data['pickup_time'] ?? '') == '09:00') ? 'selected' : '' ?>>09:00</option>
                    <option value="10:00" <?= (($form_data['pickup_time'] ?? '') == '10:00') ? 'selected' : '' ?>>10:00</option>
                    <option value="11:00" <?= (($form_data['pickup_time'] ?? '') == '11:00') ? 'selected' : '' ?>>11:00</option>
                    <option value="12:00" <?= (($form_data['pickup_time'] ?? '') == '12:00') ? 'selected' : '' ?>>12:00</option>
                </select>

                <select name="return_time" class="border p-3 rounded">
                    <option value="09:00" <?= (($form_data['return_time'] ?? '') == '09:00') ? 'selected' : '' ?>>09:00</option>
                    <option value="10:00" <?= (($form_data['return_time'] ?? '') == '10:00') ? 'selected' : '' ?>>10:00</option>
                    <option value="11:00" <?= (($form_data['return_time'] ?? '') == '11:00') ? 'selected' : '' ?>>11:00</option>
                    <option value="12:00" <?= (($form_data['return_time'] ?? '') == '12:00') ? 'selected' : '' ?>>12:00</option>
                </select>

                <select name="pickup_location" class="border p-3 rounded md:col-span-2">
                    <option value="Dubai Airport" <?= (($form_data['pickup_location'] ?? '') == 'Dubai Airport') ? 'selected' : '' ?>>Dubai Airport</option>
                    <option value="Dubai Marina" <?= (($form_data['pickup_location'] ?? '') == 'Dubai Marina') ? 'selected' : '' ?>>Dubai Marina</option>
                    <option value="Downtown Dubai" <?= (($form_data['pickup_location'] ?? '') == 'Downtown Dubai') ? 'selected' : '' ?>>Downtown Dubai</option>
                </select>

                <textarea name="special_requests" placeholder="Special Requests" class="border p-3 rounded md:col-span-2"><?= htmlspecialchars($form_data['special_requests'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded w-full font-semibold hover:bg-indigo-700">Confirm Booking</button>
        </form>
    </div>
</section>

<script>
    const pricePerDay = <?= (float)$car['price_per_day'] ?>;
    const pickup = document.querySelector('[name="pickup_date"]');
    const ret = document.querySelector('[name="return_date"]');
    const totalSpan = document.getElementById('totalAmount');

    function updateTotal() {
        if (!pickup.value || !ret.value) {
            totalSpan.textContent = '0';
            return;
        }
        const d1 = new Date(pickup.value);
        const d2 = new Date(ret.value);
        if (d2 <= d1) {
            totalSpan.textContent = '0';
            return;
        }
        const days = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
        totalSpan.textContent = (days * pricePerDay).toLocaleString();
    }

    pickup?.addEventListener('change', updateTotal);
    ret?.addEventListener('change', updateTotal);
    updateTotal();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>