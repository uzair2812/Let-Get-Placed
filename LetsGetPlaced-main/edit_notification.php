

<?php
session_start();
include("db_connect.php");
if(!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$id = $_GET['id'];

// Handle Update
if(isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE notifications SET title=?, message=?, notification_type=? WHERE notification_id=?");
    $stmt->bind_param("sssi", $_POST['title'], $_POST['message'], $_POST['type'], $id);
    $stmt->execute();
    header("Location: manage_notifications.php");
    exit();
}

// Fetch current data
$res = mysqli_query($conn, "SELECT * FROM notifications WHERE notification_id = '$id'");
$row = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .card { border: 1px solid #e2e8f0; border-radius: 12px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <nav class="mb-4"><a href="manage_notifications.php" class="text-muted text-decoration-none">&larr; Back to Notifications</a></nav>
    <h2 class="fw-bold mb-4">Edit Notification</h2>
    <div class="card p-4">
        <form method="POST">
            <input type="text" name="title" class="form-control mb-3" value="<?php echo htmlspecialchars($row['title']); ?>" required>
            <textarea name="message" class="form-control mb-3" rows="4" required><?php echo htmlspecialchars($row['message']); ?></textarea>
            <select name="type" class="form-control mb-4">
                <option <?php if($row['notification_type'] == 'General') echo 'selected'; ?>>General</option>
                <option <?php if($row['notification_type'] == 'Company') echo 'selected'; ?>>Company</option>
                <option <?php if($row['notification_type'] == 'Placement') echo 'selected'; ?>>Placement</option>
                <option <?php if($row['notification_type'] == 'Urgent') echo 'selected'; ?>>Urgent</option>
            </select>
            <button class="btn btn-dark w-100" name="update">Save Changes</button>
        </form>
    </div>
</div>
</body>
</html>