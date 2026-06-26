<?php
/**
 * login.php
 * Demo-only auth: there is no database, so any email/password
 * combination "logs in" and stores a name in the session.
 * Swap this for real credential checks if you add a database.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Enter an email and password to continue.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, name, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $email
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Log in — EventHub';
require_once __DIR__ . '/includes/header.php';
?>


<section class="section auth-section">
  <div class="container auth-container">
    <div class="auth-card">
      <h1>Welcome back</h1>
      <p class="auth-sub">Log in to manage your events and tickets.</p>

      <?php if ($error): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" action="login.php" class="auth-form">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>

        <button type="submit" class="btn btn-primary btn-block">Log in</button>
      </form>

      <p class="auth-note">Sign in securely using your registered account.</p>
      <p class="auth-switch">New to EventHub? <a href="signup.php">Sign up</a></p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
