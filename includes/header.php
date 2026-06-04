<?php
// includes/header.php
if (!function_exists('url')) {
    function url($path = '') {
        return $path;
    }
}

if (!function_exists('isActivePage')) {
    function isActivePage($page) {
        return '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- REMOVE base href (safer modern approach) -->

    <title><?= htmlspecialchars(SITE_NAME ?? 'Car Rental') ?> - Premium Car Rental</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { font-family: 'Inter', sans-serif; }

        .nav-active {
            color: #4f46e5;
            font-weight: 600;
        }

        .dropdown-content {
            display: none;
        }

        .dropdown.open .dropdown-content {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-50">

<!-- Loading -->
<div id="loading" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
    <div class="bg-white p-4 rounded-lg shadow">
        <i class="fas fa-spinner fa-spin text-indigo-600 text-2xl"></i>
    </div>
</div>

<!-- TOP BAR -->
<div class="bg-gray-900 text-white text-sm py-2 hidden md:block">
    <div class="container mx-auto px-6 flex justify-between">
        <div class="flex gap-6">
            <a href="tel:+97141234567"><?= SITE_PHONE ?? '' ?></a>
            <a href="mailto:<?= SITE_EMAIL ?? '' ?>"><?= SITE_EMAIL ?? '' ?></a>
        </div>
    </div>
</div>

<!-- NAV -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-6 flex justify-between items-center py-4">

        <!-- Logo -->
        <a href="<?= url('index.php') ?>" class="flex items-center gap-2">
            <i class="fas fa-car text-indigo-600 text-2xl"></i>
            <div>
                <div class="font-bold">Arise Car Rentals</div>
                <div class="text-xs text-gray-500">Luxury & Premium</div>
            </div>
        </a>

        <!-- Desktop -->
        <div class="hidden md:flex items-center gap-6">

            <a href="<?= url('index.php') ?>" class="<?= isActivePage('index.php') ?>">Home</a>

            <a href="<?= url('cars.php') ?>">Fleet</a>

            <a href="<?= url('services.php') ?>">Services</a>

            <a href="<?= url('about.php') ?>">About</a>

            <a href="<?= url('contact.php') ?>">Contact</a>

            <a href="<?= url('offers.php') ?>" class="bg-red-500 text-white px-3 py-1 rounded-full">
                Offers
            </a>

            <a href="<?= url('booking.php') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                Book Now
            </a>
        </div>

        <!-- Mobile button -->
        <button id="menuBtn" class="md:hidden">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
        <div class="p-4 space-y-3">
            <a href="<?= url('index.php') ?>" class="block">Home</a>
            <a href="<?= url('cars.php') ?>" class="block">Fleet</a>
            <a href="<?= url('services.php') ?>" class="block">Services</a>
            <a href="<?= url('about.php') ?>" class="block">About</a>
            <a href="<?= url('contact.php') ?>" class="block">Contact</a>
            <a href="<?= url('booking.php') ?>" class="block bg-indigo-600 text-white text-center py-2 rounded-lg">
                Book Now
            </a>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("menuBtn");
    const menu = document.getElementById("mobileMenu");

    if (btn && menu) {
        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    }

});
</script>

<main>