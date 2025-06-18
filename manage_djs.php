<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Success message
$success = '';

// Handle DJ addition
if(isset($_POST['add'])) {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $genre = $conn->real_escape_string(trim($_POST['genre']));
    if ($name && $genre) {
        $conn->query("INSERT INTO djs (name, genre) VALUES ('$name', '$genre')");
        $success = "DJ added successfully!";
    }
}

// Handle DJ deletion
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM djs WHERE id=$id");
    header("Location: manage_djs.php?deleted=1");
    exit;
}

// Show delete success message
if(isset($_GET['deleted'])) {
    $success = "DJ deleted successfully!";
}

// Fetch DJs
$djs = $conn->query("SELECT * FROM djs");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage DJs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="admin.php">DJ Booking Admin</a>
    <div>
      <a class="btn btn-outline-light me-2" href="admin.php">Admin Panel</a>
      <a class="btn btn-outline-light" href="booking.php">Booking Form</a>
      <a class="btn btn-outline-light" href="logout.php">Logout</a>

    </div>
  </div>
</nav>

<div class="container py-4">
  <h2 class="mb-4 text-primary">Manage DJs</h2>
  
  <!-- Success Alert -->
  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $success ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <form method="POST" class="row g-3 mb-4 bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <div class="col-md-5">
      <input type="text" name="name" class="form-control" placeholder="DJ Name" required>
    </div>
    <div class="col-md-5">
      <input type="text" name="genre" class="form-control" placeholder="Genre" required>
    </div>
    <div class="col-md-2">
      <button type="submit" name="add" class="btn btn-primary w-100">Add DJ</button>
    </div>
  </form>
  
  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Genre</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if($djs->num_rows > 0): ?>
          <?php while($dj = $djs->fetch_assoc()): ?>
          <tr>
            <td><?= $dj['id'] ?></td>
            <td><?= htmlspecialchars($dj['name']) ?></td>
            <td><?= htmlspecialchars($dj['genre']) ?></td>
            <td>
                <a href="edit_dj.php?id=<?= $dj['id'] ?>" class="btn btn-warning btn-sm">Edit</a>

              <a href="manage_djs.php?delete=<?= $dj['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this DJ?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center">No DJs found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <a href="admin.php" class="btn btn-secondary mt-3">Back to Admin</a>
</div>

<!-- Bootstrap JS for alert close -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
