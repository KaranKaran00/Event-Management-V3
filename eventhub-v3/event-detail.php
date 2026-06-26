<?php
$pageTitle = 'Event details — EventHub';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$event = getEventById($events, $id);

if (!$event) {
    echo '<section class="section"><div class="container empty-state">';
    echo '<span class="empty-state-icon">🎫</span><h3>We couldn\'t find that event</h3>';
    echo '<p>It may have sold out its run or the link is off.</p>';
    echo '<a href="events.php" class="btn btn-primary">Browse events</a>';
    echo '</div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$cat = getCategory($event['category'], $categories);

$related = array_values(array_filter($events, fn($e) => $e['category'] === $event['category'] && $e['id'] !== $event['id']));
$related = array_slice($related, 0, 3);
?>

<section class="event-banner" style="--cover-color: <?= htmlspecialchars($cat['color']) ?>">
  <div class="container event-banner-inner">
    <span class="event-banner-icon"><?= $cat['icon'] ?></span>
  </div>
</section>

<section class="section">
  <div class="container event-detail-layout">

    <div class="event-detail-main">
      <span class="event-tag" style="--tag-color: <?= htmlspecialchars($cat['color']) ?>"><?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?></span>
      <h1><?= htmlspecialchars($event['title']) ?></h1>

      <div class="event-meta-list">
        <div class="event-meta-item">
          <span class="event-meta-icon">📅</span>
          <div>
            <strong><?= formatEventDate($event['date']) ?></strong>
            <div class="event-meta-sub"><?= formatEventTime($event['time']) ?></div>
          </div>
        </div>
        <div class="event-meta-item">
          <span class="event-meta-icon">📍</span>
          <div>
            <strong><?= htmlspecialchars($event['venue']) ?></strong>
            <div class="event-meta-sub"><?= htmlspecialchars($event['city']) ?></div>
          </div>
        </div>
        <div class="event-meta-item">
          <span class="event-meta-icon">🏷️</span>
          <div>
            <strong>Hosted by</strong>
            <div class="event-meta-sub"><?= htmlspecialchars($event['organizer']) ?></div>
          </div>
        </div>
      </div>

      <h2 class="event-section-title">About this event</h2>
      <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
    </div>

    <aside class="ticket-panel">
      <div class="ticket-card">
        <h3>Tickets</h3>
        <div class="ticket-row">
          <div>
            <strong>General Admission</strong>
            <div class="event-meta-sub"><?= formatPrice($event['price']) ?></div>
          </div>
          <div class="qty-stepper" data-price="<?= (float)$event['price'] ?>">
            <button type="button" class="qty-btn" data-action="decrease" aria-label="Decrease quantity">−</button>
            <span class="qty-value">1</span>
            <button type="button" class="qty-btn" data-action="increase" aria-label="Increase quantity">+</button>
          </div>
        </div>
        <div class="ticket-total">
          <span>Total</span>
          <span class="ticket-total-value"><?= formatPrice($event['price']) ?></span>
        </div>
        <button type="button" class="btn btn-primary btn-block" id="getTicketsBtn">Get tickets</button>
        <p class="ticket-note">Demo checkout — no payment is processed.</p>
      </div>
    </aside>

  </div>
</section>

<?php if (!empty($related)): ?>
<section class="section section-soft">
  <div class="container">
    <div class="section-head">
      <h2>More <?= htmlspecialchars($cat['name']) ?> events</h2>
    </div>
    <div class="event-grid">
      <?php foreach ($related as $re):
        $rcat = getCategory($re['category'], $categories);
      ?>
        <a href="event-detail.php?id=<?= $re['id'] ?>" class="event-card">
          <div class="event-card-cover" style="--cover-color: <?= htmlspecialchars($rcat['color']) ?>">
            <span class="event-card-icon"><?= $rcat['icon'] ?></span>
            <span class="event-card-price"><?= formatPrice($re['price']) ?></span>
          </div>
          <div class="event-card-body">
            <span class="event-card-date"><?= formatEventDate($re['date']) ?></span>
            <h3><?= htmlspecialchars($re['title']) ?></h3>
            <p class="event-card-venue">📍 <?= htmlspecialchars($re['venue']) ?>, <?= htmlspecialchars($re['city']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<div class="modal" id="ticketModal">
  <div class="modal-card">
    <span class="modal-icon">🎉</span>
    <h3>You're going!</h3>
    <p>This is a UI demo — connect a payment provider and database to make it real.</p>
    <button type="button" class="btn btn-primary" id="closeModalBtn">Close</button>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
