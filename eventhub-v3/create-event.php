<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$currentUser = $_SESSION['user'];

$pageTitle = 'Create event — EventHub';
require_once __DIR__ . '/includes/data.php';

$editEvent = null;
if (isset($_GET['edit'])) {
    $editEvent = getEventById($events, (int)$_GET['edit']);
    if ($editEvent && (int)$editEvent['organizer_id'] !== (int)$currentUser['id']) {
        header('Location: dashboard.php');
        exit;
    }
}

$error = '';
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (float)($_POST['price'] ?? 0.00);
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $venue = trim($_POST['venue'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $organizer = $currentUser['name'];
    $organizer_id = $currentUser['id'];

    if ($title === '' || $category === '' || $date === '' || $time === '' || $venue === '' || $city === '') {
        $error = 'Please fill in all required fields.';
    } else {
        if ($editEvent) {
            // Update existing event
            $stmt = mysqli_prepare($conn, "UPDATE events SET title = ?, category = ?, date = ?, time = ?, venue = ?, city = ?, price = ?, description = ? WHERE id = ? AND organizer_id = ?");
            mysqli_stmt_bind_param($stmt, "ssssssdsii", $title, $category, $date, $time, $venue, $city, $price, $description, $editEvent['id'], $organizer_id);
            if (mysqli_stmt_execute($stmt)) {
                $submitted = true;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'Error updating event: ' . mysqli_error($conn);
            }
        } else {
            // Insert new event
            $stmt = mysqli_prepare($conn, "INSERT INTO events (title, category, date, time, venue, city, price, organizer, organizer_id, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssssdsis", $title, $category, $date, $time, $venue, $city, $price, $organizer, $organizer_id, $description);
            if (mysqli_stmt_execute($stmt)) {
                $submitted = true;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'Error creating event: ' . mysqli_error($conn);
            }
        }
    }
}

$values = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : ($editEvent ?? []);
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-head">
  <div class="container">
    <h1><?= $editEvent ? 'Edit event' : 'Create an event' ?></h1>
    <p>Fill in the details below. Your event will be saved to the database.</p>
  </div>
</section>

<section class="section">
  <div class="container form-container">

    <?php if ($error): ?>
      <div class="form-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="create-event.php<?= $editEvent ? '?edit=' . $editEvent['id'] : '' ?>" class="event-form">
      <div class="form-grid">
        <div class="form-field form-field-full">
          <label for="title">Event title</label>
          <input type="text" id="title" name="title" placeholder="Sunset Indie Music Festival" value="<?= htmlspecialchars($values['title'] ?? '') ?>" required>
        </div>

        <div class="form-field">
          <label for="category">Category</label>
          <select id="category" name="category" required>
            <option value="">Choose a category</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= htmlspecialchars($cat['slug']) ?>" <?= (isset($values['category']) && $values['category'] === $cat['slug']) ? 'selected' : '' ?>>
                <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field">
          <label for="price">Ticket price (₹, 0 = free)</label>
          <input type="number" id="price" name="price" min="0" placeholder="0" value="<?= htmlspecialchars($values['price'] ?? '') ?>">
        </div>

        <div class="form-field">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" value="<?= htmlspecialchars($values['date'] ?? '') ?>" required>
        </div>

        <div class="form-field">
          <label for="time">Time</label>
          <input type="time" id="time" name="time" value="<?= htmlspecialchars($values['time'] ?? '') ?>" required>
        </div>

        <div class="form-field">
          <label for="venue">Venue</label>
          <input type="text" id="venue" name="venue" placeholder="Riverside Amphitheatre" value="<?= htmlspecialchars($values['venue'] ?? '') ?>" required>
        </div>

        <div class="form-field">
          <label for="city">City</label>
          <input type="text" id="city" name="city" placeholder="Mumbai" value="<?= htmlspecialchars($values['city'] ?? '') ?>" required>
        </div>

        <div class="form-field form-field-full">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="5" placeholder="Tell people what to expect..."><?= htmlspecialchars($values['description'] ?? '') ?></textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary"><?= $editEvent ? 'Save changes' : 'Create event' ?></button>
    </form>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
