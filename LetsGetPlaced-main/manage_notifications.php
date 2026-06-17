<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Adding Notifications
if(isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO notifications (title, message, notification_type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['title'], $_POST['message'], $_POST['type']);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_notifications.php");
    exit();
}

// Handle Deleting
if(isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM notifications WHERE notification_id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_notifications.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table { background: #fff; border-radius: 12px; overflow: hidden; }
        .msg-cell { max-width: 400px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Manage Notifications</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-dark btn-sm px-3">&larr; Back to Dashboard</a>
    </div>

    <div class="card mb-4 p-4">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" name="title" class="form-control" placeholder="Notification Title" required>
                </div>
                <div class="col-md-6 mb-3">
                    <select name="type" class="form-control">
                        <option value="General">General</option>
                        <option value="Company">Company</option>
                        <option value="Placement">Placement</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <textarea name="message" class="form-control mb-3" placeholder="Full Notification Message" rows="3" required></textarea>
            <button class="btn btn-dark px-4" name="add">Add Notification</button>
        </form>
    </div>

    <table class="table table-hover border">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Message</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM notifications ORDER BY notification_id DESC");
            while($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td class="fw-bold"><?php echo $row['notification_id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td class="msg-cell"><?php echo htmlspecialchars($row['message']); ?></td>
                <td>
                    <span class="badge bg-secondary"><?php echo htmlspecialchars($row['notification_type']); ?></span>
                </td>
                <td>
                    <a href="edit_notification.php?id=<?php echo $row['notification_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="?delete=<?php echo $row['notification_id']; ?>" 
                       class="btn btn-sm btn-outline-danger" 
                       onclick="return confirm('Delete this notification?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>