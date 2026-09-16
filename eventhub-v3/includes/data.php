<?php
require_once __DIR__ . '/../db.php';

$categories = [];
$catQuery = "SELECT * FROM categories";
$catResult = mysqli_query($conn, $catQuery);
if ($catResult) {
    while ($row = mysqli_fetch_assoc($catResult)) {
        $categories[] = $row;
    }
}

$events = [];
$evtQuery = "SELECT * FROM events ORDER BY date ASC, time ASC";
$evtResult = mysqli_query($conn, $evtQuery);
if ($evtResult) {
    while ($row = mysqli_fetch_assoc($evtResult)) {
        $row['id'] = (int)$row['id'];
        $row['price'] = (float)$row['price'];
        $events[] = $row;
    }
}

function getCategory(string $slug, array $categories): ?array {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $slug) return $cat;
    }
    return null;
}

function filterEvents(array $events, string $query = '', string $category = '', string $price = 'all'): array {
    return array_values(array_filter($events, function ($e) use ($query, $category, $price) {
        $matchesQuery = true;
        if ($query !== '') {
            $haystack = strtolower($e['title'] . ' ' . $e['city'] . ' ' . $e['organizer'] . ' ' . $e['venue']);
            $matchesQuery = str_contains($haystack, strtolower($query));
        }
        $matchesCategory = ($category === '' || $category === 'all') ? true : $e['category'] === $category;
        $matchesPrice = true;
        if ($price === 'free') $matchesPrice = ((int)$e['price']) === 0;
        if ($price === 'paid') $matchesPrice = ((int)$e['price']) > 0;
        return $matchesQuery && $matchesCategory && $matchesPrice;
    }));
}

function getEventById(array $events, int $id): ?array {
    foreach ($events as $e) {
        if ((int)$e['id'] === $id) return $e;
    }
    return null;
}

function formatEventDate(string $dateStr): string {
    $ts = strtotime($dateStr);
    return $ts ? date('D, M j, Y', $ts) : $dateStr;
}

function formatEventTime(string $timeStr): string {
    $ts = strtotime($timeStr);
    return $ts ? date('g:i A', $ts) : $timeStr;
}

function formatPrice($price): string {
    $price = (float)$price;
    return $price <= 0 ? 'Free' : '₹' . number_format($price);
}
