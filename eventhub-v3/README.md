# EventHub — PHP event listing site (static demo)

A multi-page event discovery and listing site inspired by Eventbrite's
layout and flow, built in plain PHP/HTML/CSS/JS. This is the **static**
build you asked for: there's no database, so login, sign-up, event
creation, and ticket checkout are working UI flows that don't persist
any data. Wire up MySQL (or any store) later if you want it to stick.

## Pages

| File                  | What it does                                            |
|------------------------|----------------------------------------------------------|
| `index.php`            | Homepage — hero search, category tiles, trending events |
| `events.php`           | Browse/search page with PHP-driven category & price filters |
| `event-detail.php`     | Single event page with a demo ticket-quantity + checkout modal |
| `login.php` / `signup.php` | Demo session login (any email/password works)       |
| `dashboard.php`        | Organizer dashboard (stats + "your events" — needs login) |
| `create-event.php`     | Create/edit event form (shows a success message, doesn't save) |
| `includes/data.php`    | Sample categories & events array + helper functions     |
| `includes/header.php` / `includes/footer.php` | Shared nav & footer |
| `assets/css/style.css` | All styling |
| `assets/js/main.js`    | Mobile nav toggle, ticket stepper, checkout modal        |

## Running it locally

You need PHP installed (8.0+). From this folder, run:

```bash
php -S localhost:8000
```

Then open `http://localhost:8000` in your browser.

Alternatively, drop this folder into your XAMPP/WAMP/MAMP `htdocs`
directory and visit `http://localhost/eventhub/`.

## Where to add a database later

- `includes/data.php` is the only place that defines event/category
  data — replace the arrays with PDO/MySQLi queries.
- `login.php` / `signup.php` currently trust any input — add real
  password hashing (`password_hash` / `password_verify`) and a
  `users` table check.
- `create-event.php` would `INSERT` into an `events` table instead of
  just rendering a success message.
