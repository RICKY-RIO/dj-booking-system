<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include 'db.php';
$id = intval($_GET['id']);
$dj = $conn->query("SELECT * FROM djs WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])) {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $genre = $conn->real_escape_string(trim($_POST['genre']));
    $conn->query("UPDATE djs SET name='$name', genre='$genre' WHERE id=$id");
    header("Location: manage_djs.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit DJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-primary">Edit DJ</h2>
    <form method="POST" class="bg-white p-4 rounded shadow-sm" style="max-width:400px;">
        <div class="mb-3">
            <label class="form-label">DJ Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($dj['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control" value="<?= htmlspecialchars($dj['genre']) ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary w-100">Update DJ</button>
        <a href="manage_djs.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </form>
</div>
</body>
</html>
