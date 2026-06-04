<?php
// admin/includes/header.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= $page_title ?? 'Arise Car Rentals' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active {
            background-color: #4f46e5;
            color: white;
        }
        .sidebar-link:hover:not(.active) {
            background-color: #e0e7ff;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex-shrink-0 overflow-y-auto">
            <div class="p-5 border-b">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-car text-indigo-600 text-2xl"></i>
                    <span class="font-bold text-xl">Admin Panel</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Arise Car Rentals</p>
            </div>
            <nav class="p-4 space-y-1">
                <a href="index.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active text-white' : 'text-gray-700' ?>">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="cars.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'cars.php' ? 'active text-white' : 'text-gray-700' ?>">
                    <i class="fas fa-car w-5"></i>
                    <span>Manage Cars</span>
                </a>
                <a href="bookings.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active text-white' : 'text-gray-700' ?>">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>Bookings</span>
                </a>
                <a href="customers.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active text-white' : 'text-gray-700' ?>">
                    <i class="fas fa-users w-5"></i>
                    <span>Customers</span>
                </a>
                <hr class="my-4">
                <a href="logout.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">
                    <?= $page_title ?? 'Dashboard' ?>
                </h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Welcome, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
                    </span>
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-indigo-600"></i>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6 overflow-y-auto">