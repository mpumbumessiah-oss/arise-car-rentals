<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/email-config.php';

/* =========================
   CAR FUNCTIONS
========================= */

function getFeaturedCars(int $limit = 6): array
{
    $db = Database::getInstance();

    $sql = "SELECT * FROM cars 
            WHERE is_featured = 1 AND is_available = 1 
            ORDER BY created_at DESC 
            LIMIT ?";

    $stmt = $db->query($sql, [$limit]);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllCars(array $filters = []): array
{
    $db = Database::getInstance();

    $sql = "SELECT * FROM cars WHERE is_available = 1";
    $params = [];

    if (!empty($filters['brand'])) {
        $sql .= " AND brand = ?";
        $params[] = $filters['brand'];
    }

    if (!empty($filters['transmission'])) {
        $sql .= " AND transmission = ?";
        $params[] = $filters['transmission'];
    }

    if (!empty($filters['fuel_type'])) {
        $sql .= " AND fuel_type = ?";
        $params[] = $filters['fuel_type'];
    }

    if (!empty($filters['min_price'])) {
        $sql .= " AND price_per_day >= ?";
        $params[] = (float)$filters['min_price'];
    }

    if (!empty($filters['max_price'])) {
        $sql .= " AND price_per_day <= ?";
        $params[] = (float)$filters['max_price'];
    }

    if (!empty($filters['search'])) {
        $sql .= " AND (name LIKE ? OR brand LIKE ? OR model LIKE ?)";
        $search = "%" . $filters['search'] . "%";
        array_push($params, $search, $search, $search);
    }

    $sql .= " ORDER BY is_featured DESC, created_at DESC";

    $stmt = $db->query($sql, $params);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getCarById(int $id): ?array
{
    $db = Database::getInstance();

    $stmt = $db->query(
        "SELECT * FROM cars WHERE id = ?",
        [$id]
    );

    return $stmt->get_result()->fetch_assoc() ?: null;
}

/* =========================
   BOOKING FUNCTIONS
========================= */

function checkCarAvailability(int $car_id, string $pickup_date, string $return_date): bool
{
    $db = Database::getInstance();

    $sql = "SELECT COUNT(*) as count FROM bookings 
            WHERE car_id = ? 
            AND status != 'cancelled'
            AND (
                (pickup_date <= ? AND return_date >= ?) OR
                (pickup_date BETWEEN ? AND ?) OR
                (return_date BETWEEN ? AND ?)
            )";

    $stmt = $db->query($sql, [
        $car_id,
        $return_date,
        $pickup_date,
        $pickup_date,
        $return_date,
        $pickup_date,
        $return_date
    ]);

    $data = $stmt->get_result()->fetch_assoc();

    return ((int)$data['count'] === 0);
}

function createBooking(array $data): array|false
{
    $db = Database::getInstance();

    // safer reference than uniqid
    $reference = 'ARISE-' . bin2hex(random_bytes(5));

    $sql = "INSERT INTO bookings (
        booking_reference, customer_name, customer_email, customer_phone,
        car_id, pickup_date, return_date, pickup_time, return_time,
        pickup_location, return_location, total_amount,
        special_requests, driver_license
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $reference,
        $data['customer_name'],
        $data['customer_email'],
        $data['customer_phone'],
        (int)$data['car_id'],
        $data['pickup_date'],
        $data['return_date'],
        $data['pickup_time'],
        $data['return_time'],
        $data['pickup_location'],
        $data['return_location'],
        (float)$data['total_amount'],
        $data['special_requests'] ?? '',
        $data['driver_license'] ?? ''
    ];

    $booking_id = $db->insert($sql, $params);

    return $booking_id
        ? ['id' => $booking_id, 'reference' => $reference]
        : false;
}

function getUserBookings(string $email): array
{
    $db = Database::getInstance();

    $sql = "SELECT b.*, c.name AS car_name, c.image_main
            FROM bookings b
            JOIN cars c ON b.car_id = c.id
            WHERE b.customer_email = ?
            ORDER BY b.created_at DESC";

    $stmt = $db->query($sql, [$email]);

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* =========================
   PRICING
========================= */

function calculateTotalAmount(int $car_id, string $pickup_date, string $return_date): float
{
    $car = getCarById($car_id);
    if (!$car) return 0;

    $pickup = new DateTime($pickup_date);
    $return = new DateTime($return_date);

    if ($return <= $pickup) {
        return $car['price_per_day'] ?? 0;
    }

    $days = $pickup->diff($return)->days ?: 1;

    $daily = (float)$car['price_per_day'];
    $weekly = (float)($car['price_per_week'] ?? ($daily * 6));
    $monthly = (float)($car['price_per_month'] ?? ($daily * 20));

    if ($days >= 30) {
        $months = intdiv($days, 30);
        $remaining = $days % 30;
        return ($months * $monthly) + ($remaining * $daily);
    }

    if ($days >= 7) {
        $weeks = intdiv($days, 7);
        $remaining = $days % 7;
        return ($weeks * $weekly) + ($remaining * $daily);
    }

    return $days * $daily;
}

/* =========================
   UTILITIES
========================= */

function getBrands(): array
{
    $db = Database::getInstance();

    $stmt = $db->query("SELECT DISTINCT brand FROM cars WHERE is_available = 1 ORDER BY brand");

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function formatPrice(float $price): string
{
    return 'AED ' . number_format($price, 0);
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

/* =========================
   EMAIL
========================= */

function sendBookingConfirmation(array $booking, array $car, string $reference): bool
{
    $message = buildBookingEmail($booking, $car, $reference);

    return sendEmail(
        $booking['customer_email'],
        "Booking Confirmation - $reference",
        $message
    );
}

/**
 * Clean separation of email HTML
 */
function buildBookingEmail(array $booking, array $car, string $reference): string
{
    return "
    <html>
    <body style='font-family:Arial;line-height:1.6'>
        <h2>Booking Confirmed</h2>
        <p><strong>Reference:</strong> $reference</p>

        <p><strong>Car:</strong> {$car['name']}</p>
        <p><strong>Pickup:</strong> {$booking['pickup_date']} {$booking['pickup_time']}</p>
        <p><strong>Return:</strong> {$booking['return_date']} {$booking['return_time']}</p>

        <p><strong>Total:</strong> AED " . number_format((float)$booking['total_amount']) . "</p>
    </body>
    </html>";
}