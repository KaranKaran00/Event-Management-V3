<?php
/**
 * setup_db.php
 * Sets up the eventhub database, tables, and seeds initial data.
 */

$host = 'localhost';
$user = 'root';
$pass = '';

echo "Connecting to MySQL server...\n";
$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "\n");
}

echo "Creating database 'eventhub' if not exists...\n";
if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS eventhub")) {
    die("Error creating database: " . mysqli_error($conn) . "\n");
}

if (!mysqli_select_db($conn, 'eventhub')) {
    die("Error selecting database 'eventhub': " . mysqli_error($conn) . "\n");
}

echo "Executing schema.sql...\n";
$schemaSql = file_get_contents(__DIR__ . '/schema.sql');
if (!$schemaSql) {
    die("Could not read schema.sql\n");
}

// Split queries by semicolon to execute one by one
$queries = array_filter(array_map('trim', explode(';', $schemaSql)));
foreach ($queries as $query) {
    if ($query !== '') {
        if (!mysqli_query($conn, $query)) {
            die("Error running query:\n$query\nError: " . mysqli_error($conn) . "\n");
        }
    }
}
echo "Schema built successfully!\n";

// Seed categories
$categories = [
    ['slug' => 'music',     'name' => 'Music',            'icon' => '🎵', 'color' => '#F05537'],
    ['slug' => 'business',  'name' => 'Business',          'icon' => '💼', 'color' => '#0C8B65'],
    ['slug' => 'food',      'name' => 'Food & Drink',      'icon' => '🍔', 'color' => '#F2A93B'],
    ['slug' => 'arts',      'name' => 'Arts & Culture',    'icon' => '🎨', 'color' => '#7B61FF'],
    ['slug' => 'sports',    'name' => 'Sports & Fitness',  'icon' => '🏃', 'color' => '#1E90FF'],
    ['slug' => 'tech',      'name' => 'Tech',              'icon' => '💻', 'color' => '#1A1A2E'],
    ['slug' => 'workshop',  'name' => 'Workshops',         'icon' => '🛠️', 'color' => '#D9412A'],
    ['slug' => 'community', 'name' => 'Community',         'icon' => '🤝', 'color' => '#0C8B65'],
];

echo "Seeding categories...\n";
foreach ($categories as $cat) {
    $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO categories (slug, name, icon, color) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $cat['slug'], $cat['name'], $cat['icon'], $cat['color']);
    mysqli_stmt_execute($stmt);
}

// Seed events
$events = [
    [
        'id' => 1,
        'title' => 'Sunset Indie Music Festival',
        'category' => 'music',
        'date' => '2026-07-12',
        'time' => '17:00',
        'venue' => 'Riverside Amphitheatre',
        'city' => 'Mumbai',
        'price' => 899,
        'organizer' => 'Indie Collective',
        'description' => 'A full evening of live indie and alternative acts on the riverside stage, with food trucks and local art stalls. Bring a blanket and watch the sunset turn into a night of music under string lights.',
    ],
    [
        'id' => 2,
        'title' => 'Startup Founders Mixer',
        'category' => 'business',
        'date' => '2026-06-25',
        'time' => '18:30:00',
        'venue' => 'Innov8 Co-working Hub',
        'city' => 'Bengaluru',
        'price' => 0,
        'organizer' => 'FoundersConnect',
        'description' => 'Meet early-stage founders, angel investors, and operators over drinks. Short pitch slots are open for the first ten people who sign up at the door.',
    ],
    [
        'id' => 3,
        'title' => 'Street Food Carnival',
        'category' => 'food',
        'date' => '2026-06-28',
        'time' => '12:00:00',
        'venue' => 'Marine Drive Promenade',
        'city' => 'Mumbai',
        'price' => 199,
        'organizer' => 'City Bites Collective',
        'description' => 'Forty stalls, one stretch of seafront. Sample dishes from across the country, enter the chilli-eating contest, or just come for the live grilling stations.',
    ],
    [
        'id' => 4,
        'title' => 'Contemporary Art Walk',
        'category' => 'arts',
        'date' => '2026-07-02',
        'time' => '11:00:00',
        'venue' => 'Kala Ghoda Art District',
        'city' => 'Mumbai',
        'price' => 0,
        'organizer' => 'Kala Ghoda Association',
        'description' => 'A self-paced walking tour through twelve open studios and galleries, with three artists giving live demonstrations throughout the afternoon.',
    ],
    [
        'id' => 5,
        'title' => 'City Half Marathon',
        'category' => 'sports',
        'date' => '2026-08-09',
        'time' => '05:30:00',
        'venue' => 'Bandra-Worli Sea Link',
        'city' => 'Mumbai',
        'price' => 1299,
        'organizer' => 'RunIndia',
        'description' => 'A scenic 21.1km route across the sea link with full medical support, hydration points every 2.5km, and a finisher medal for every runner who crosses the line.',
    ],
    [
        'id' => 6,
        'title' => 'AI & Future of Work Summit',
        'category' => 'tech',
        'date' => '2026-07-18',
        'time' => '09:30:00',
        'venue' => 'Hitech City Convention Centre',
        'city' => 'Hyderabad',
        'price' => 1499,
        'organizer' => 'TechForward Media',
        'description' => 'A full-day summit on applied AI in the workplace, with hands-on workshops, hiring-trend panels, and a startup showcase floor.',
    ],
    [
        'id' => 7,
        'title' => 'Watercolour for Beginners',
        'category' => 'workshop',
        'date' => '2026-06-30',
        'time' => '15:00:00',
        'venue' => 'The Paper Studio',
        'city' => 'Pune',
        'price' => 599,
        'organizer' => 'The Paper Studio',
        'description' => 'A relaxed three-hour studio session covering colour mixing, wet-on-wet technique, and simple landscape composition. All materials included.',
    ],
    [
        'id' => 8,
        'title' => 'Neighbourhood Clean-Up Drive',
        'category' => 'community',
        'date' => '2026-06-21',
        'time' => '07:00:00',
        'venue' => 'Carter Road',
        'city' => 'Mumbai',
        'price' => 0,
        'organizer' => 'Clean Coast Collective',
        'description' => 'Gloves, bags, and breakfast provided. Join neighbours for a two-hour beach and promenade clean-up followed by a community breakfast.',
    ],
    [
        'id' => 9,
        'title' => 'Late Night Jazz Sessions',
        'category' => 'music',
        'date' => '2026-07-05',
        'time' => '21:00:00',
        'venue' => 'The Quarter Note Club',
        'city' => 'Pune',
        'price' => 699,
        'organizer' => 'Quarter Note Live',
        'description' => 'An intimate evening with a rotating quartet, low lighting, and a menu built around small plates and old fashioneds.',
    ],
    [
        'id' => 10,
        'title' => 'Product Management Bootcamp',
        'category' => 'business',
        'date' => '2026-07-22',
        'time' => '10:00:00',
        'venue' => 'WorkSpace Central',
        'city' => 'Bengaluru',
        'price' => 2999,
        'organizer' => 'PM School',
        'description' => 'A two-day intensive on roadmapping, stakeholder communication, and metrics that matter, led by practitioners from product-led companies.',
    ],
    [
        'id' => 11,
        'title' => 'Weekend Farmers Market',
        'category' => 'food',
        'date' => '2026-06-21',
        'time' => '08:00:00',
        'venue' => 'Phoenix Grounds',
        'city' => 'Pune',
        'price' => 0,
        'organizer' => 'Fresh Roots Collective',
        'description' => 'Local growers, small-batch preserves, and a pop-up breakfast counter. Bring your own bags; cash and UPI both accepted at every stall.',
    ],
    [
        'id' => 12,
        'title' => 'Sunrise Yoga on the Lawn',
        'category' => 'sports',
        'date' => '2026-06-22',
        'time' => '06:00:00',
        'venue' => 'Cubbon Park',
        'city' => 'Bengaluru',
        'price' => 149,
        'organizer' => 'Breathwork Studio',
        'description' => 'An all-levels Vinyasa flow on the open lawn as the sun comes up, followed by herbal tea and a short breathing workshop.',
    ],
];

echo "Seeding events...\n";
foreach ($events as $event) {
    // Check if event already exists to prevent duplicate seeding
    $checkQuery = mysqli_prepare($conn, "SELECT id FROM events WHERE id = ?");
    mysqli_stmt_bind_param($checkQuery, "i", $event['id']);
    mysqli_stmt_execute($checkQuery);
    mysqli_stmt_store_result($checkQuery);
    
    if (mysqli_stmt_num_rows($checkQuery) === 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO events (id, title, category, date, time, venue, city, price, organizer, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param(
            $stmt, 
            "issssssdss", 
            $event['id'], 
            $event['title'], 
            $event['category'], 
            $event['date'], 
            $event['time'], 
            $event['venue'], 
            $event['city'], 
            $event['price'], 
            $event['organizer'], 
            $event['description']
        );
        mysqli_stmt_execute($stmt);
    }
}

echo "Database successfully set up and seeded!\n";
?>
