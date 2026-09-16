<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['user'] ?? null;
$pageTitle = $pageTitle ?? 'EventHub — Find things to do';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a href="index.php" class="logo">
      <span class="logo-mark">🎟️</span><span class="logo-text">Event<strong>Hub</strong></span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" id="mainNav">
      <a href="events.php">Find Events</a>
      <?php if ($currentUser): ?>
        <a href="create-event.php">Create Event</a>
        <a href="dashboard.php">Dashboard</a>
      <?php endif; ?>
      <?php if (!empty($currentUser['is_admin'])): ?>
        <a href="admin_instagram.php">Manage Instagram</a>
      <?php endif; ?>
    </nav>

    <div class="header-actions">
      <?php if ($currentUser): ?>
        <a href="dashboard.php" class="user-chip">
          <span class="user-avatar"><?= htmlspecialchars(strtoupper(substr($currentUser['name'], 0, 1))) ?></span>
          <?= htmlspecialchars($currentUser['name']) ?>
        </a>
        <a href="logout.php" class="btn btn-ghost btn-sm">Log out</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-ghost btn-sm">Log in</a>
        <a href="signup.php" class="btn btn-primary btn-sm">Sign up</a>
      <?php endif; ?>
    </div>
  </div>
</header>
