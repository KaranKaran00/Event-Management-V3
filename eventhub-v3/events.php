<?php
$pageTitle = 'Find Events — EventHub';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';

$q        = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? 'all');
$price    = trim($_GET['price'] ?? 'all');

$results = filterEvents($events, $q, $category, $price);

// Simple sort options, applied after filtering.
$sort = $_GET['sort'] ?? 'date';
usort($results, function ($a, $b) use ($sort) {
    if ($sort === 'price_low')  return $a['price'] <=> $b['price'];
    if ($sort === 'price_high') return $b['price'] <=> $a['price'];
    return strtotime($a['date']) <=> strtotime($b['date']); // default: soonest first
});
?>

<section class="page-head">
  <div class="container">
    <h1>Find events</h1>
    <p><?= count($results) ?> event<?= count($results) === 1 ? '' : 's' ?> found<?= $q !== '' ? ' for “' . htmlspecialchars($q) . '”' : '' ?></p>
  </div>
</section>

<section class="section">
  <div class="container browse-layout">

    <form method="get" action="events.php" class="filters-sidebar">
      <div class="filter-group">
        <label for="q">Search</label>
        <input type="text" id="q" name="q" placeholder="Title, venue, organizer..." value="<?= htmlspecialchars($q) ?>">
      </div>

      <div class="filter-group">
        <span class="filter-group-label">Category</span>
        <label class="filter-option">
          <input type="radio" name="category" value="all" <?= $category === 'all' ? 'checked' : '' ?>>
          All categories
        </label>
        <?php foreach ($categories as $cat): ?>
          <label class="filter-option">
            <input type="radio" name="category" value="<?= htmlspecialchars($cat['slug']) ?>" <?= $category === $cat['slug'] ? 'checked' : '' ?>>
            <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
          </label>
        <?php endforeach; ?>
      </div>

      <div class="filter-group">
        <span class="filter-group-label">Price</span>
        <label class="filter-option">
          <input type="radio" name="price" value="all" <?= $price === 'all' ? 'checked' : '' ?>> Any price
        </label>
        <label class="filter-option">
          <input type="radio" name="price" value="free" <?= $price === 'free' ? 'checked' : '' ?>> Free
        </label>
        <label class="filter-option">
          <input type="radio" name="price" value="paid" <?= $price === 'paid' ? 'checked' : '' ?>> Paid
        </label>
      </div>

      <div class="filter-group">
        <label for="sort">Sort by</label>
        <select id="sort" name="sort">
          <option value="date" <?= $sort === 'date' ? 'selected' : '' ?>>Date (soonest)</option>
          <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price (low to high)</option>
          <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price (high to low)</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Apply filters</button>
      <a href="events.php" class="clear-filters">Clear all</a>
    </form>

    <div class="browse-results">
      <?php if (empty($results)): ?>
        <div class="empty-state">
          <span class="empty-state-icon">🔎</span>
          <h3>No events match your filters yet</h3>
          <p>Try a broader search term, or clear a filter to see more results.</p>
          <a href="events.php" class="btn btn-ghost">Clear all filters</a>
        </div>
      <?php else: ?>
        <div class="event-grid">
          <?php foreach ($results as $event):
            $cat = getCategory($event['category'], $categories);
          ?>
            <a href="event-detail.php?id=<?= $event['id'] ?>" class="event-card">
              <div class="event-card-cover" style="--cover-color: <?= htmlspecialchars($cat['color']) ?>">
                <span class="event-card-icon"><?= $cat['icon'] ?></span>
                <span class="event-card-price"><?= formatPrice($event['price']) ?></span>
              </div>
              <div class="event-card-body">
                <span class="event-card-date"><?= formatEventDate($event['date']) ?></span>
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p class="event-card-venue">📍 <?= htmlspecialchars($event['venue']) ?>, <?= htmlspecialchars($event['city']) ?></p>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
