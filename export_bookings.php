<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include 'db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="bookings.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'User', 'DJ', 'Event Date'));

$result = $conn->query("SELECT bookings.id, users.username, djs.name as dj_name, bookings.event_date
                        FROM bookings
                        JOIN users ON bookings.user_id = users.id
                        JOIN djs ON bookings.dj_id = djs.id
                        ORDER BY bookings.event_date DESC");

while($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
