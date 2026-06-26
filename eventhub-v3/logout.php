<?php
/**
 * logout.php
 * Clears the demo session (no real auth/database — see login.php).
 */
session_start();
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
