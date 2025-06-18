# DJ Booking System

A simple PHP & MySQL web application for booking DJs, with an admin dashboard, CSV export, and DJ management.

## Features

- Book DJs with name, email, phone, and special requests
- Admin dashboard: view, search, delete, and export bookings
- Manage DJs (add, edit, delete)
- Confirmation email sent to users (if mail is configured)

## How to Run

1. **Clone or download this repo.**
2. Place the project folder in your XAMPP/WAMP/MAMP `htdocs` directory.
3. Import the SQL tables into phpMyAdmin (create a database, then import).
4. Update your database connection in `db.php` if needed.
5. Start Apache and MySQL in XAMPP/WAMP/MAMP.
6. Open `http://localhost/dj-booking-system/booking.php` in your browser.

## Admin Panel

- Go to `http://localhost/dj-booking-system/admin.php`
- Use your admin credentials to log in.

## Notes

- Email sending requires SMTP configuration in XAMPP. For demo/testing, this can be skipped.
- For any issues, open an issue or contact [RICKY-RIO](https://github.com/RICKY-RIO).

## Author

[RICKY-RIO](https://github.com/RICKY-RIO)
