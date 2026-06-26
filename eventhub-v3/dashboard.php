<?php
/**
 * dashboard.php
 * Organizer dashboard — gated behind the demo session login.
 * "Your events" below is illustrative sample data, since there is
 * no database tying real events to a real organizer account yet.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Dashboard — EventHub';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';

$userId = $_SESSION['user']['id'] ?? null;
$myEvents = [];
if ($userId) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE organizer_id = ? ORDER BY date ASC, time ASC");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) {
            $row['id'] = (int)$row['id'];
            $row['price'] = (float)$row['price'];
            $myEvents[] = $row;
        }
    }
}

$stats = [
    ['label' => 'Live events', 'value' => count($myEvents)],
    ['label' => 'Tickets sold', 'value' => count($myEvents) > 0 ? '1,284' : '0'],
    ['label' => 'Revenue', 'value' => count($myEvents) > 0 ? '₹3,42,900' : '₹0'],
    ['label' => 'Page views', 'value' => count($myEvents) > 0 ? '18,402' : '0'],
];
?>

<section class="page-head">
  <div class="container dashboard-head">
    <div>
      <h1>Welcome back, <?= htmlspecialchars($currentUser['name']) ?></h1>
      <p>Here's how your events are doing.</p>
    </div>
    <a href="create-event.php" class="btn btn-primary">+ Create event</a>
  </div>
</section>

<section class="section">
  <div class="container">

    <div class="stats-grid">
      <?php foreach ($stats as $s): ?>
        <div class="stat-card">
          <span class="stat-value"><?= htmlspecialchars($s['value']) ?></span>
          <span class="stat-label"><?= htmlspecialchars($s['label']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="section-head">
      <h2>Your events</h2>
    </div>

    <div class="dashboard-table">
      <div class="dashboard-row dashboard-row-head">
        <span>Event</span>
        <span>Date</span>
        <span>Price</span>
        <span>Status</span>
        <span></span>
      </div>
      <?php if (empty($myEvents)): ?>
        <div class="dashboard-row" style="grid-template-columns: 1fr; justify-content: center; text-align: center; padding: 2rem;">
          <span style="color: var(--text-muted); font-size: 0.95rem;">You haven't created any events yet. <a href="create-event.php" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Create your first event!</a></span>
        </div>
      <?php else: ?>
        <?php foreach ($myEvents as $event):
          $cat = getCategory($event['category'], $categories);
        ?>
          <div class="dashboard-row">
            <span class="dashboard-event-name">
              <span class="dashboard-event-icon" style="--cover-color: <?= htmlspecialchars($cat['color'] ?? '#7B61FF') ?>"><?= htmlspecialchars($cat['icon'] ?? '📅') ?></span>
              <?= htmlspecialchars($event['title']) ?>
            </span>
            <span><?= formatEventDate($event['date']) ?></span>
            <span><?= formatPrice($event['price']) ?></span>
            <span class="status-badge status-live">Live</span>
            <span class="dashboard-row-actions">
              <a href="event-detail.php?id=<?= $event['id'] ?>" class="btn btn-ghost btn-sm">View</a>
              <a href="create-event.php?edit=<?= $event['id'] ?>" class="btn btn-ghost btn-sm">Edit</a>
            </span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
