<?php
$page_title = 'Search';
require_once __DIR__ . '/header.php';

$q = trim($_GET['q'] ?? '');

$pages = [
    ['title' => 'Home', 'url' => 'index.php', 'text' => 'Software development company solutions and services'],
    ['title' => 'About', 'url' => 'about.php', 'text' => 'Company profile team mission and values'],
    ['title' => 'Services', 'url' => 'services.php', 'text' => 'School ERP inventory POS ecommerce custom software'],
    ['title' => 'School ERP', 'url' => 'school-erp.php', 'text' => 'School management software'],
    ['title' => 'Inventory & POS', 'url' => 'inventory-pos.php', 'text' => 'Retail inventory and point of sale'],
    ['title' => 'E-commerce', 'url' => 'ecommerce.php', 'text' => 'Online store website development'],
    ['title' => 'Contact', 'url' => 'contact.php', 'text' => 'Get quote consultation contact form'],
];

$results = [];
if ($q !== '') {
    $needle = strtolower($q);
    foreach ($pages as $page) {
        $haystack = strtolower($page['title'] . ' ' . $page['text']);
        if (strpos($haystack, $needle) !== false) {
            $results[] = $page;
        }
    }
}
?>
<section class="page-header">
    <div class="container">
        <h1>Search</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Search</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <form method="get" action="search.php" style="max-width: 640px; margin-bottom: 20px;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search pages..." style="width: 100%; padding: 12px;">
        </form>

        <?php if ($q === ''): ?>
            <p>Enter a search term to find pages.</p>
        <?php elseif (empty($results)): ?>
            <p>No pages matched "<strong><?php echo htmlspecialchars($q); ?></strong>".</p>
        <?php else: ?>
            <p>Found <?php echo count($results); ?> result(s) for "<strong><?php echo htmlspecialchars($q); ?></strong>".</p>
            <ul>
                <?php foreach ($results as $result): ?>
                    <li style="margin: 12px 0;">
                        <a href="<?php echo htmlspecialchars($result['url']); ?>">
                            <?php echo htmlspecialchars($result['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
