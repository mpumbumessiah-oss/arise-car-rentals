<?php
session_start();
$page_title = 'Manage Cars';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$conn = $db->connection();
$message = '';
$error = '';

// Create uploads directory if it doesn't exist
$uploadDir = __DIR__ . '/../uploads/cars/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// CSRF token
if (empty($_SESSION['admin_csrf_token'])) {
    $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
}

// Helper function to delete an image file
function deleteCarImage($imagePath) {
    if (!empty($imagePath)) {
        $fullPath = __DIR__ . '/../' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}

// Handle Delete (changed to POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!hash_equals($_SESSION['admin_csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }
    $id = (int)$_POST['delete_id'];
    
    // Get current image path before deleting the car
    $stmt = $conn->prepare("SELECT image_main FROM cars WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    if ($car && !empty($car['image_main'])) {
        deleteCarImage($car['image_main']);
    }
    
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Car deleted successfully.";
    } else {
        $error = "Cannot delete – car may have bookings.";
    }
}

// Handle Toggle Availability (changed to POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    if (!hash_equals($_SESSION['admin_csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }
    $id = (int)$_POST['toggle_id'];
    $stmt = $conn->prepare("UPDATE cars SET is_available = NOT is_available WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = "Availability updated.";
}

// Handle Add/Edit with image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_car'])) {
    if (!hash_equals($_SESSION['admin_csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }

    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = (int)$_POST['year'];
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $seats = (int)$_POST['seats'];
    $price_per_day = (float)$_POST['price_per_day'];
    $description = trim($_POST['description']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle image upload
    $image_main = $_POST['existing_image'] ?? ''; // default to existing image
    if (isset($_FILES['image_main']) && $_FILES['image_main']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image_main'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            $error = "Invalid file type. Allowed: JPEG, PNG, GIF, WEBP.";
        } elseif ($file['size'] > $maxSize) {
            $error = "File too large. Max size: 10MB.";
        } else {
            // Generate unique filename
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('car_', true) . '.' . $ext;
            $relativePath = 'uploads/cars/' . $filename;
            $fullPath = __DIR__ . '/../' . $relativePath;
            
            if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                // Delete old image if updating
                if ($id > 0 && !empty($_POST['existing_image'])) {
                    deleteCarImage($_POST['existing_image']);
                }
                $image_main = $relativePath;
            } else {
                $error = "Failed to upload image.";
            }
        }
    } elseif (isset($_FILES['image_main']) && $_FILES['image_main']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error = "Image upload error: " . $_FILES['image_main']['error'];
    }
    
    // If there's an error, don't proceed with DB save
    if (!empty($error)) {
        // We'll show the error below and keep the form values
    } else {
        if ($id > 0) {
            // If no new image was uploaded, keep the existing one
            if (empty($image_main) && !empty($_POST['existing_image'])) {
                $image_main = $_POST['existing_image'];
            }
            $stmt = $conn->prepare("UPDATE cars SET name=?, brand=?, model=?, year=?, transmission=?, fuel_type=?, seats=?, price_per_day=?, image_main=?, description=?, is_featured=? WHERE id=?");
            $stmt->bind_param("sssisssdssii", $name, $brand, $model, $year, $transmission, $fuel_type, $seats, $price_per_day, $image_main, $description, $is_featured, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO cars (name, brand, model, year, transmission, fuel_type, seats, price_per_day, image_main, description, is_featured, is_available) VALUES (?,?,?,?,?,?,?,?,?,?,?,1)");
            $stmt->bind_param("sssisssdssi", $name, $brand, $model, $year, $transmission, $fuel_type, $seats, $price_per_day, $image_main, $description, $is_featured);
        }
        if ($stmt->execute()) {
            $message = $id > 0 ? "Car updated." : "Car added.";
            // Redirect to clear POST data and avoid re-upload
            header("Location: cars.php?msg=" . urlencode($message));
            exit;
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

// Handle message from redirect
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}

// Fetch all cars
$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");

// Fetch edit car safely using prepared statement
$editCar = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $editCar = $result->fetch_assoc();
}
?>

<!-- Add/Edit Form with multipart/form-data -->
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4"><?= $editCar ? 'Edit Car' : 'Add New Car' ?></h2>
    <?php if ($message): ?><div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($editCar['id'] ?? '') ?>">
        <input type="hidden" name="save_car" value="1">
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editCar['image_main'] ?? '') ?>">
        <div class="grid md:grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="Car Name" required value="<?= htmlspecialchars($editCar['name'] ?? '') ?>" class="border rounded p-2">
            <input type="text" name="brand" placeholder="Brand" required value="<?= htmlspecialchars($editCar['brand'] ?? '') ?>" class="border rounded p-2">
            <input type="text" name="model" placeholder="Model" required value="<?= htmlspecialchars($editCar['model'] ?? '') ?>" class="border rounded p-2">
            <input type="number" name="year" placeholder="Year" required value="<?= htmlspecialchars($editCar['year'] ?? '') ?>" class="border rounded p-2">
            <select name="transmission" class="border rounded p-2">
                <option value="automatic" <?= ($editCar['transmission'] ?? '') == 'automatic' ? 'selected' : '' ?>>Automatic</option>
                <option value="manual" <?= ($editCar['transmission'] ?? '') == 'manual' ? 'selected' : '' ?>>Manual</option>
            </select>
            <select name="fuel_type" class="border rounded p-2">
                <option value="petrol" <?= ($editCar['fuel_type'] ?? '') == 'petrol' ? 'selected' : '' ?>>Petrol</option>
                <option value="diesel" <?= ($editCar['fuel_type'] ?? '') == 'diesel' ? 'selected' : '' ?>>Diesel</option>
                <option value="electric" <?= ($editCar['fuel_type'] ?? '') == 'electric' ? 'selected' : '' ?>>Electric</option>
                <option value="hybrid" <?= ($editCar['fuel_type'] ?? '') == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
            </select>
            <input type="number" name="seats" placeholder="Seats" required value="<?= htmlspecialchars($editCar['seats'] ?? '') ?>" class="border rounded p-2">
            <input type="number" step="0.01" name="price_per_day" placeholder="Price per day (AED)" required value="<?= htmlspecialchars($editCar['price_per_day'] ?? '') ?>" class="border rounded p-2">
            
            <div class="md:col-span-2">
                <label class="block font-medium mb-1">Car Image</label>
                <?php if ($editCar && !empty($editCar['image_main'])): ?>
                    <div class="mb-2">
                        <img src="<?= htmlspecialchars('../' . $editCar['image_main']) ?>" class="w-24 h-24 object-cover rounded border">
                        <p class="text-sm text-gray-500 mt-1">Current image. Upload new to replace.</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="image_main" accept="image/jpeg,image/png,image/gif,image/webp" class="border rounded p-2 w-full">
                <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, GIF, WEBP. Max 2MB.</p>
            </div>
            
            <div class="md:col-span-2">
                <textarea name="description" placeholder="Description" rows="3" class="border rounded p-2 w-full"><?= htmlspecialchars($editCar['description'] ?? '') ?></textarea>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_featured" <?= ($editCar['is_featured'] ?? 0) ? 'checked' : '' ?>>
                <label>Featured Car</label>
            </div>
        </div>
        <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded"><?= $editCar ? 'Update Car' : 'Add Car' ?></button>
        <?php if ($editCar): ?>
            <a href="cars.php" class="mt-4 ml-2 inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<!-- Cars List with POST forms for delete/toggle -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <h2 class="text-xl font-bold p-6 pb-0">All Cars</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr><th class="px-4 py-3 text-left">ID</th><th>Image</th><th>Name</th><th>Brand</th><th>Price/Day</th><th>Available</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($car = $cars->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-3"><?= htmlspecialchars($car['id']) ?></td>
                    <td class="px-4 py-3">
                        <?php if (!empty($car['image_main'])): ?>
                            <img src="<?= htmlspecialchars('../' . $car['image_main']) ?>" class="w-12 h-12 object-cover rounded">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">No img</div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3"><?= htmlspecialchars($car['name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($car['brand']) ?></td>
                    <td class="px-4 py-3">AED <?= number_format($car['price_per_day']) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs <?= $car['is_available'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $car['is_available'] ? 'Yes' : 'No' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 space-x-2">
                        <a href="cars.php?edit=<?= $car['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token']) ?>">
                            <input type="hidden" name="toggle_id" value="<?= $car['id'] ?>">
                            <button type="submit" class="text-yellow-600 hover:underline">Toggle</button>
                        </form>
                        <form method="POST" class="inline" onsubmit="return confirm('Delete this car?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token']) ?>">
                            <input type="hidden" name="delete_id" value="<?= $car['id'] ?>">
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>