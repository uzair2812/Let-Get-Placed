<?php
session_start();

// Strict Session Access Verification Gateway
if(!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

include("db_connect.php");

$message = "";

if(isset($_POST['submit_ticket'])) {
    $student_id = $_SESSION['student_id'];
    $reason = mysqli_real_escape_string($conn, trim($_POST['reason']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $proof_file = "";

    // File handling
    if(isset($_FILES['proof']) && $_FILES['proof']['name'] != "") {
        $target_dir = "uploads/tickets";
        if(!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        $proof_file = time() . '_' . preg_replace("/[^a-zA-w0-9\._-]/", "_", $_FILES['proof']['name']);
        move_uploaded_file($_FILES['proof']['tmp_name'], $target_dir . "/" . $proof_file);
    }

    // Insert statement matching your exact schema
    $query = "INSERT INTO TICKETS (student_id, ticket_reason, ticket_description, proof_file, status) 
              VALUES ('$student_id', '$reason', '$description', '$proof_file', 'Pending')";
              
    if(mysqli_query($conn, $query)) {
        $message = "Your support ticket has been registered successfully.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise Support Ticket | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; background-color: #f1f5f9; font-family: 'Inter', sans-serif; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background-color: #0f172a; position: fixed; overflow-y: auto; border-right: 1px solid #1e293b; }
        .sidebar h3 { font-size: 1.2rem; font-weight: 700; color: #ffffff; padding: 24px 20px; margin: 0; border-bottom: 1px solid #1e293b; }
        .sidebar a { display: block; padding: 14px 20px; color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .sidebar a:hover { background-color: #1e293b; color: #ffffff; }
        .sidebar .active-link { background-color: #2563eb; color: #ffffff !important; font-weight: 600; }
        .main { margin-left: 260px; padding: 40px; max-width: 1000px; }
        .card-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05); }
        .header-panel { background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 24px 32px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>🎓 Let's Get Placed</h3>
        <a href="student_dashboard.php">🏠 Dashboard</a>
        <a href="student_profile.php">👤 My Profile</a>
        <a href="upload_resume.php">📄 My Resume</a>
        <a href="resume_builder.php">📝 Create Resume</a>
        <a href="companies.php">🏢 Companies</a>
        <a href="registered_drives.php">📋 Registered Drives</a>
        <a href="placement_materials.php">📚 Placement Materials</a>
        <a href="notifications.php">🔔 Notifications</a>
        <a href="raise_tickets.php" class="active-link">🎫 Raise Support Ticket</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main">
        <div class="card card-box mb-5">
            <div class="header-panel"><h3 class="m-0">Raise Technical & Placement Ticket</h3></div>
            <div class="card-body p-5">
                <?php if(!empty($message)) { echo "<div class='alert alert-info mb-4'>$message</div>"; } ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label>Ticket Reason</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Choose reason...</option>
                            <option value="Unable To Register">Unable To Register</option>
                            <option value="Resume Issue">Resume Issue</option>
                            <option value="Placement Query">Placement Query</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label>Attachment</label>
                        <input type="file" name="proof" class="form-control">
                    </div>
                    <button type="submit" name="submit_ticket" class="btn btn-primary">Submit Ticket</button>
                </form>
            </div>
        </div>

        <div class="card card-box">
            <div class="header-panel"><h3 class="m-0">My Ticket History</h3></div>
            <div class="card-body p-4">
                <table class="table table-hover">
                    <thead><tr><th>Reason</th><th>Status</th><th>File</th></tr></thead>
                    <tbody>
                        <?php
                        $hist = mysqli_query($conn, "SELECT * FROM TICKETS WHERE student_id = '".$_SESSION['student_id']."' ORDER BY ticket_id DESC");
                        if($hist) {
                            while($row = mysqli_fetch_assoc($hist)) {
                                $file = !empty($row['proof_file']) ? "<a href='uploads/tickets/{$row['proof_file']}' target='_blank'>View</a>" : "None";
                                echo "<tr>
                                        <td>{$row['ticket_reason']}</td>
                                        <td><span class='badge bg-info'>{$row['status']}</span></td>
                                        <td>{$file}</td>
                                      </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>