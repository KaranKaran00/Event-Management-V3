<?php
/**
 * signup.php
 * Demo-only registration: there is no database, so submitting the
 * form just stores the entered name/email in the session.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Fill in your name, email and password to continue.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'This email address is already registered.';
        } else {
            // Hash password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Insert user
            $insertStmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insertStmt, "sss", $name, $email, $hashedPassword);
            
            if (mysqli_stmt_execute($insertStmt)) {
                $userId = mysqli_insert_id($conn);
                $_SESSION['user'] = [
                    'id' => $userId,
                    'name' => $name,
                    'email' => $email
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'An error occurred during registration. Please try again.';
            }
        }
    }
}


$pageTitle = 'Sign up — EventHub';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section auth-section">
  <div class="container auth-container">
    <div class="auth-card">
      <h1>Create your account</h1>
      <p class="auth-sub">Sign up to host events or save the ones you love.</p>

      <?php if ($error): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" action="signup.php" class="auth-form">
        <label for="name">Full name</label>
        <input type="text" id="name" name="name" placeholder="Jordan Lee" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>

        <label for="confirm">Confirm password</label>
        <input type="password" id="confirm" name="confirm" placeholder="••••••••" required>

        <button type="submit" class="btn btn-primary btn-block">Sign up</button>
      </form>

      <p class="auth-note">Your account details will be securely saved to the database.</p>
      <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
