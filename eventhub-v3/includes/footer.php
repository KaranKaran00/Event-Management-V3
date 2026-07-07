<?php
/**
 * includes/footer.php
 * Shared bottom of every page: footer markup, closing tags, scripts.
 */
?>
<footer class="site-footer">
  <div class="container footer-inner">
    <div class="footer-col footer-brand">
      <a href="index.php" class="logo">
        <span class="logo-mark">🎟️</span><span class="logo-text">Event<strong>Hub</strong></span>
      </a>
      <p class="footer-tagline">Find things to do, or put your own event on the map.</p>
    </div>

    <div class="footer-col">
      <h4>Discover</h4>
      <a href="events.php">All events</a>
      <a href="events.php?price=free">Free events</a>
      <a href="events.php?category=music">Music</a>
      <a href="events.php?category=business">Business</a>
    </div>

    <div class="footer-col">
      <h4>Hosting</h4>
      <a href="create-event.php">Create an event</a>
      <a href="dashboard.php">Organizer dashboard</a>
      <a href="signup.php">Sign up to host</a>
    </div>

    <div class="footer-col">
      <h4>EventHub</h4>
      <a href="#">About</a>
      <a href="#">Help centre</a>
      <a href="#">Contact us</a>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>&copy; <?= date('Y') ?> EventHub. Built as a static PHP demo — no data is stored.</p>
  </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
