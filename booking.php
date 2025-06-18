<?php
include 'db.php';

// Fetch DJs from the database
$djs = $conn->query("SELECT id, name FROM djs");

// Handle booking submission and success message
$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $dj_id = intval($_POST["dj_id"]);
    $event_date = $_POST["event_date"];
    $phone = $conn->real_escape_string($_POST["phone"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $special_requests = $conn->real_escape_string($_POST["special_requests"]);

    // Check if user exists, if not, create
    $user_check = $conn->query("SELECT id FROM users WHERE username='$username'");
    if($user_check->num_rows > 0){
        $user = $user_check->fetch_assoc();
        $user_id = $user['id'];
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '')");
        $user_id = $conn->insert_id;
    }

    // Save booking with all new fields
    $conn->query("INSERT INTO bookings (user_id, dj_id, event_date, phone, email, special_requests) 
                  VALUES ($user_id, $dj_id, '$event_date', '$phone', '$email', '$special_requests')");

    // Send confirmation email
    $to = $email;
    $subject = "DJ Booking Confirmation";
    $message = "Dear $username,\n\nYour booking for DJ ID $dj_id on $event_date is confirmed!\n\nThank you!";
    $headers = "From: no-reply@yourdomain.com";
    mail($to, $subject, $message, $headers);

    $success = "Booking successful! A confirmation email has been sent to $email.";
    // Refresh DJs list for form after POST
    $djs = $conn->query("SELECT id, name FROM djs");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Book a DJ - DJ Booking System</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="admin.php">DJ Booking Admin</a>
    <div>
      <a class="btn btn-outline-light me-2" href="manage_djs.php">Manage DJs</a>
      <a class="btn btn-outline-light" href="admin.php">Admin Panel</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h2 class="mb-4 text-primary">Book a DJ</h2>
  
  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $success ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <form method="POST" action="booking.php" class="bg-white p-4 rounded shadow-sm" style="max-width: 500px; margin:auto;">
  <div class="mb-3">
    <label class="form-label">Your Name:</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email:</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Phone Number:</label>
    <input type="text" name="phone" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Special Requests:</label>
    <textarea name="special_requests" class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Select DJ:</label>
    <select name="dj_id" class="form-select" required>
      <option value="">--Select DJ--</option>
      <?php while($dj = $djs->fetch_assoc()): ?>
        <option value="<?php echo $dj['id']; ?>"><?php echo htmlspecialchars($dj['name']); ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Event Date:</label>
    <input type="date" name="event_date" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary w-100">Book DJ</button>
</form>

</div>

<!-- Bootstrap JS for alert close -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
