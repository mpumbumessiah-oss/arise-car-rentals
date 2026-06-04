<?php
// includes/footer.php
?>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-6 py-12">

            <div class="grid md:grid-cols-4 gap-8">

                <!-- Brand -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-car text-2xl text-indigo-400"></i>
                        <span class="text-xl font-bold">Arise Car Rentals</span>
                    </div>
                    <p class="text-gray-400">
                        Premium car rental services in Dubai. Experience luxury and comfort with our wide range of vehicles.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="<?php echo url('cars.php'); ?>" class="text-gray-400 hover:text-indigo-400">
                                Our Fleet
                            </a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-indigo-400">How It Works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-indigo-400">Terms & Conditions</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-indigo-400">FAQ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Sheikh Zayed Road, Dubai, UAE</li>
                        <li><i class="fas fa-phone mr-2"></i> +971 4 123 4567</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@ariserenals.ae</li>
                    </ul>
                </div>

                <!-- Hours -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Working Hours</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>Monday - Friday: 8:00 AM - 10:00 PM</li>
                        <li>Saturday: 9:00 AM - 8:00 PM</li>
                        <li>Sunday: 9:00 AM - 6:00 PM</li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Arise Car Rentals. All rights reserved.</p>
            </div>

        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Mobile menu toggle (safe version)
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Global loading helpers (safe)
            window.showLoading = function () {
                const loader = document.getElementById('loading');
                if (loader) loader.classList.add('active');
            };

            window.hideLoading = function () {
                const loader = document.getElementById('loading');
                if (loader) loader.classList.remove('active');
            };

        });
    </script>

</body>
</html>