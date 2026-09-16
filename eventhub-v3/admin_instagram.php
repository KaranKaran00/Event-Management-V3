<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if (empty($_SESSION['user']['is_admin'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
    $url = trim($_POST['post_url'] ?? '');
    $caption = trim($_POST['caption'] ?? '');

    if (!preg_match('#^https?://(www\\.)?instagram\\.com/(p|reel)/[A-Za-z0-9_-]+/?#', $url)) {
        $error = 'Please paste a valid Instagram post or reel link (e.g. https://www.instagram.com/p/XXXXXXXXX/).';
    } else {
        $adminId = $_SESSION['user']['id'];
        $stmt = mysqli_prepare($conn, "INSERT INTO instagram_posts (post_url, caption, added_by) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssi", $url, $caption, $adminId);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Instagram post added — it will now show on the home page.';
        } else {
            $error = 'Could not save the post. Please try again.';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM instagram_posts WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header('Location: admin_instagram.php');
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    $stmt = mysqli_prepare($conn, "UPDATE instagram_posts SET is_active = 1 - is_active WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header('Location: admin_instagram.php');
    exit;
}

$posts = [];
$result = mysqli_query($conn, "SELECT ip.*, u.name AS added_by_name
                                FROM instagram_posts ip
                                LEFT JOIN users u ON u.id = ip.added_by
                                ORDER BY ip.created_at DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
}

$pageTitle = 'Manage Instagram Posts — EventHub';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-head">
  <div class="container dashboard-head">
    <div>
      <h1>Manage Instagram Posts</h1>
      <p>Paste a link and it goes live on the home page automatically.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">

    <div class="auth-card" style="max-width: 600px; margin: 0 0 2rem;">
      <?php if ($error): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="form-error" style="background:#e8f7ee; color:#1e7e42; border-color:#bfe6cc;"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="post" class="auth-form">
        <label for="post_url">Instagram post link</label>
        <input type="url" id="post_url" name="post_url" placeholder="https://www.instagram.com/p/XXXXXXXXX/" required>

        <label for="caption">Internal note (optional, not shown publicly)</label>
        <input type="text" id="caption" name="caption" placeholder="e.g. Tech Fest 2026 highlight reel">

        <button type="submit" name="add_post" class="btn btn-primary btn-block">Add Post</button>
      </form>
    </div>

    <div class="section-head">
      <h2>Existing posts</h2>
    </div>

    <div class="dashboard-table">
      <div class="dashboard-row dashboard-row-head">
        <span>Link</span>
        <span>Added by</span>
        <span>Date</span>
        <span>Status</span>
        <span></span>
      </div>
      <?php if (empty($posts)): ?>
        <div class="dashboard-row" style="grid-template-columns: 1fr; justify-content: center; text-align: center; padding: 2rem;">
          <span style="color: var(--text-muted); font-size: 0.95rem;">No Instagram posts added yet.</span>
        </div>
      <?php else: ?>
        <?php foreach ($posts as $post): ?>
          <div class="dashboard-row">
            <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
              <a href="<?= htmlspecialchars($post['post_url']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($post['post_url']) ?></a>
            </span>
            <span><?= htmlspecialchars($post['added_by_name'] ?? 'Unknown') ?></span>
            <span><?= htmlspecialchars($post['created_at']) ?></span>
            <span class="status-badge <?= $post['is_active'] ? 'status-live' : '' ?>">
              <?= $post['is_active'] ? 'Visible' : 'Hidden' ?>
            </span>
            <span class="dashboard-row-actions">
              <a href="?toggle=<?= (int) $post['id'] ?>" class="btn btn-ghost btn-sm"><?= $post['is_active'] ? 'Hide' : 'Show' ?></a>
              <a href="?delete=<?= (int) $post['id'] ?>" class="btn btn-ghost btn-sm" onclick="return confirm('Delete this post?');">Delete</a>
            </span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
