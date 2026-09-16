<?php
$pageTitle = 'EventHub — Find things to do';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';

$trending = array_slice($events, 0, 6);
?>

<section class="hero">
  <div class="container hero-inner">
    <h1>Find your next unforgettable thing to do</h1>
    <p class="hero-sub">Concerts, workshops, markets and meetups — happening near you this week.</p>

    <form action="events.php" method="get" class="hero-search">
      <div class="hero-search-field">
        <span class="field-icon">🔍</span>
        <input type="text" name="q" placeholder="Search events, organizers, venues...">
      </div>
      <div class="hero-search-field hero-search-select">
        <span class="field-icon">📂</span>
        <select name="category">
          <option value="all">All categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary hero-search-btn">Search</button>
    </form>

    <div class="hero-trending">
      <span>Trending:</span>
      <a href="events.php?q=music">Music</a>
      <a href="events.php?q=workshop">Workshops</a>
      <a href="events.php?price=free">Free events</a>
      <a href="events.php?q=food">Food &amp; drink</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <h2>Browse by category</h2>
    </div>
    <div class="category-tiles">
      <?php foreach ($categories as $cat): ?>
        <a href="events.php?category=<?= htmlspecialchars($cat['slug']) ?>" class="category-tile" style="--tile-color: <?= htmlspecialchars($cat['color']) ?>">
          <span class="category-tile-icon"><?= $cat['icon'] ?></span>
          <span class="category-tile-name"><?= htmlspecialchars($cat['name']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-soft">
  <div class="container">
    <div class="section-head">
      <h2>Trending this week</h2>
      <a href="events.php" class="see-all">See all events →</a>
    </div>
    <div class="event-grid">
      <?php foreach ($trending as $event):
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
  </div>
</section>

<section class="cta-banner">
  <div class="container cta-banner-inner">
    <div>
      <h2>Have an event of your own?</h2>
      <p>List it on EventHub in a few minutes — free for community events.</p>
    </div>
    <a href="create-event.php" class="btn btn-light">Create an event</a>
  </div>
</section>

<?php
$instagramPosts = [];
$igResult = mysqli_query($conn, "SELECT post_url FROM instagram_posts WHERE is_active = 1 ORDER BY created_at DESC LIMIT 9");
if ($igResult) {
    while ($row = mysqli_fetch_assoc($igResult)) {
        $instagramPosts[] = $row;
    }
}
?>

<?php if (!empty($instagramPosts)): ?>
<section class="section instagram-section">
    <div class="container">
        <div class="section-head">
            <h2>Follow Us On Instagram</h2>
            <a href="https://instagram.com/Czmgbca" target="_blank" class="see-all">View Profile →</a>
        </div>
        <div class="instagram-grid">
            <?php foreach ($instagramPosts as $post): ?>
                <blockquote class="instagram-media" data-instgrm-permalink="<?= htmlspecialchars($post['post_url']) ?>" data-instgrm-version="14"></blockquote>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<script async src="//www.instagram.com/embed.js"></script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
