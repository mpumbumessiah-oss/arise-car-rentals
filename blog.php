<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';

$page_title = "Blog - Arise Car Rentals";

/**
 * In real systems this should come from DB.
 */
$posts = [
    [
        'id' => 1,
        'title' => 'Top 10 Luxury Cars to Rent in Dubai',
        'date' => 'Jan 15, 2024',
        'excerpt' => 'Discover the most sought-after luxury vehicles in Dubai...',
        'image' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537'
    ],
    [
        'id' => 2,
        'title' => 'Driving Tips for Tourists in Dubai',
        'date' => 'Jan 10, 2024',
        'excerpt' => 'Essential tips for navigating Dubai roads safely...',
        'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2'
    ],
];
?>

<main>

<!-- HERO -->
<section class="bg-gradient-to-r from-indigo-900 to-indigo-800 py-20 text-center">
    <h1 class="text-5xl font-bold text-white">
        <?= htmlspecialchars($page_title) ?>
    </h1>

    <p class="text-indigo-200 mt-2">
        Latest insights & updates
    </p>
</section>

<!-- BLOG GRID -->
<section class="py-20">
    <div class="container mx-auto px-6 grid md:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php foreach ($posts as $i => $post): ?>

            <article class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <!-- Image -->
                <img
                    src="<?= htmlspecialchars($post['image']) ?>"
                    alt="<?= htmlspecialchars($post['title']) ?>"
                    loading="lazy"
                    class="w-full h-48 object-cover"
                    onerror="this.src='https://via.placeholder.com/600x400'"
                >

                <!-- Content -->
                <div class="p-6">

                    <p class="text-sm text-indigo-600">
                        <?= htmlspecialchars($post['date']) ?>
                    </p>

                    <h2 class="text-xl font-bold mt-1">
                        <?= htmlspecialchars($post['title']) ?>
                    </h2>

                    <p class="text-gray-600 mt-2">
                        <?= htmlspecialchars($post['excerpt']) ?>
                    </p>

                    <a
                        href="<?= url('blog-post.php?id=' . $post['id']) ?>"
                        class="inline-block mt-4 text-indigo-600 font-semibold hover:underline"
                    >
                        Read More →
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </div>
</section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>