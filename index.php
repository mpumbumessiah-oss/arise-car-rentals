<?php
// At the top of each page
$current_page = basename($_SERVER['PHP_SELF']);
?>


<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$featuredCars = getFeaturedCars(6);
?>

<!-- Hero Section -->
<section class="hero-gradient relative overflow-hidden">
    <div class="absolute inset-0 opacity-20 bg-[url('assets/images/main.jpg')] bg-cover bg-center"></div>
    <div class="container mx-auto px-6 py-24 relative z-10">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight animate-fade-up">
                Drive Your Dream Car in Dubai
            </h1>
            <p class="text-xl text-gray-300 mt-6 mb-8 animate-fade-up" style="animation-delay: 0.1s">
                Experience luxury and comfort with our premium fleet. Best rates, 24/7 support, and free delivery.
            </p>
            <div class="flex flex-wrap gap-4 animate-fade-up" style="animation-delay: 0.2s">
                <a href="/arise-car-rentals/cars.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg font-semibold transition transform hover:scale-105">
                    Browse Fleet <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50"></div>
</section>

<!-- Featured Cars -->
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Featured Luxury Cars</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Experience the finest collection of premium vehicles in Dubai</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featuredCars as $car): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                <img src="<?php echo $car['image_main']; ?>" alt="<?php echo $car['name']; ?>" class="w-full h-56 object-cover">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold"><?php echo htmlspecialchars($car['name']); ?></h3>
                        <span class="bg-indigo-100 text-indigo-600 px-2 py-1 rounded text-sm"><?php echo ucfirst($car['transmission']); ?></span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                        <span><i class="fas fa-users"></i> <?php echo $car['seats']; ?> seats</span>
                        <span><i class="fas fa-gas-pump"></i> <?php echo ucfirst($car['fuel_type']); ?></span>
                        <span><i class="fas fa-tachometer-alt"></i> <?php echo number_format($car['mileage']); ?> km</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-2xl font-bold text-indigo-600">AED <?php echo number_format($car['price_per_day']); ?></span>
                            <span class="text-gray-500">/day</span>
                        </div>
                        <a href="/arise-car-rentals/car-details.php?id=<?php echo $car['id']; ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Book Now</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="bg-gray-100 py-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Why Choose Arise Car Rentals?</h2>
            <p class="text-gray-600">We provide the best car rental experience in Dubai</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-car-side text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Wide Range of Cars</h3>
                <p class="text-gray-600">From economy to luxury, we have the perfect car for every need</p>
            </div>
            
            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-dollar-sign text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Best Prices</h3>
                <p class="text-gray-600">Competitive rates with no hidden charges</p>
            </div>
            
            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">24/7 Support</h3>
                <p class="text-gray-600">Round-the-clock customer service and roadside assistance</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Get in Touch</h2>
            <p class="text-gray-600">We're here to help you choose the perfect car</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold mb-6">Send us a message</h3>
                <form id="contactForm">
                    <div class="mb-4">
                        <input type="text" id="name" placeholder="Your Name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <input type="email" id="email" placeholder="Email Address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <input type="tel" id="phone" placeholder="Phone Number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <textarea id="message" rows="4" placeholder="Your Message" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition">
                        Send Message <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl p-8 text-white">
                <h3 class="text-2xl font-bold mb-6">Contact Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                        <span>Sheikh Zayed Road, Dubai, UAE</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-xl"></i>
                        <span>+971 4 123 4567</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-xl"></i>
                        <span>info@ariserenals.ae</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you for your message! We will contact you shortly.');
    this.reset();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>