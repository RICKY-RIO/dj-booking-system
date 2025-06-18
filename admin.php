<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Handle success message for deletion
$success = '';
if (isset($_GET['deleted'])) {
    $success = "Booking deleted successfully!";
}



// Handle search
$search = '';
$where = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = $conn->real_escape_string(trim($_GET['search']));
    $where = "WHERE users.username LIKE '%$search%' OR djs.name LIKE '%$search%'";
}

$query = "SELECT bookings.id, users.username, djs.name as dj_name, bookings.event_date
          FROM bookings
          JOIN users ON bookings.user_id = users.id
          JOIN djs ON bookings.dj_id = djs.id
          $where
          ORDER BY bookings.event_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - DJ Booking System</title>
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
      <a class="btn btn-outline-light" href="booking.php">Booking Form</a>
      <a class="btn btn-outline-light" href="logout.php">Logout</a>

    </div>
  </div>
</nav>

<div class="container py-4">
  <h2 class="mb-4 text-primary">All Bookings</h2>
  
  <!-- Success Alert -->
  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $success ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Search Form -->
  <form class="row g-3 mb-4" method="get" action="admin.php">
    <div class="col-md-4">
      <input type="text" class="form-control" name="search" placeholder="Search by User or DJ" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
    <div class="col-md-2">
      <a href="admin.php" class="btn btn-secondary w-100">Reset</a>
    </div>
  </form>
  <a href="export_bookings.php" class="btn btn-info mb-3">Export Bookings to CSV</a>


  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>DJ</th>
          <th>Event Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['dj_name']) ?></td>
            <td><?= $row['event_date'] ?></td>
            <td>
              <a href="delete_booking.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No bookings found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <a href="booking.php" class="btn btn-success mt-3">Back to Booking</a>
</div>

<!-- Bootstrap JS for alert close -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
