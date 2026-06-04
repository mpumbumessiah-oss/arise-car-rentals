<?php
$page_title = "Complete Fleet - Arise Car Rentals";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$conn = getConnection();

// Get brands safely
$brands = [];
$result = $conn->query("SELECT DISTINCT brand FROM cars ORDER BY brand");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $brands[] = $row['brand'];
    }
}
?>

<!-- HERO -->
<section class="relative bg-gradient-to-r from-indigo-900 to-indigo-800 py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold text-white mb-4">Our Complete Fleet</h1>
        <p class="text-xl text-indigo-200">Explore our collection of luxury vehicles</p>
    </div>
</section>

<!-- FILTERS -->
<section class="py-12 bg-gray-100">
    <div class="container mx-auto px-6">

        <div class="flex flex-wrap justify-center gap-4">

            <button class="filter-btn px-6 py-2 rounded-full bg-indigo-600 text-white"
                    data-brand="all">
                All Cars
            </button>

            <?php foreach ($brands as $brand): ?>
                <button class="filter-btn px-6 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-indigo-600 hover:text-white transition"
                        data-brand="<?= htmlspecialchars($brand) ?>">
                    <?= htmlspecialchars($brand) ?>
                </button>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- CARS -->
<section class="py-12">
    <div class="container mx-auto px-6">

        <div id="carsContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

        <div id="loadingSpinner" class="text-center mt-10 hidden">
            <div class="animate-spin w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full mx-auto"></div>
        </div>

    </div>
</section>

<script>
async function loadCars(brand = "all") {
    const container = document.getElementById("carsContainer");
    const spinner = document.getElementById("loadingSpinner");

    spinner.classList.remove("hidden");

    try {
        const response = await fetch(`ajax/get-cars.php?brand=${encodeURIComponent(brand)}`);

        if (!response.ok) throw new Error("Network error");

        const cars = await response.json();

        if (!Array.isArray(cars)) {
            throw new Error("Invalid response format");
        }

        container.innerHTML = cars.map(car => `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <img src="${car.image_main}" class="w-full h-56 object-cover" alt="${car.name}">

                <div class="p-6">

                    <h3 class="text-xl font-bold">${car.name}</h3>

                    <p class="text-gray-500 text-sm mb-3">
                        ${car.brand} • ${car.year}
                    </p>

                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">
                            AED ${Number(car.price_per_day).toLocaleString()}
                        </span>

                        <a href="car-details.php?id=${car.id}"
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            View
                        </a>
                    </div>

                </div>
            </div>
        `).join("");

    } catch (err) {
        console.error(err);
        container.innerHTML = `
            <div class="text-center col-span-full text-red-500">
                Failed to load cars. Please try again.
            </div>
        `;
    } finally {
        spinner.classList.add("hidden");
    }
}

// initial load
loadCars();

// filter logic
document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", () => {

        document.querySelectorAll(".filter-btn").forEach(b => {
            b.classList.remove("bg-indigo-600", "text-white");
            b.classList.add("bg-gray-200", "text-gray-700");
        });

        btn.classList.remove("bg-gray-200", "text-gray-700");
        btn.classList.add("bg-indigo-600", "text-white");

        loadCars(btn.dataset.brand);
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>