<?php
$conn = new mysqli("localhost", "root", "", "dj_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
