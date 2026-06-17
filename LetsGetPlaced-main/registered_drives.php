<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['student_id'])) { header("Location: student_login.php"); exit(); }
$student_id = $_SESSION['student_id'];

// Handle Deregistration
if(isset($_GET['cancel_id'])) {
    $cancel_id = intval($_GET['cancel_id']);
    mysqli_query($conn, "DELETE FROM registrations WHERE registration_id='$cancel_id' AND student_id='$student_id' AND status='Registered'");
    header("Location: registered_drives.php");
    exit();
}

$sql = "SELECT r.*, c.company_name, c.role_offered, c.ctc, c.drive_date 
        FROM registrations r 
        JOIN companies c ON r.company_id = c.company_id 
        WHERE r.student_id='$student_id' ORDER BY r.registration_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Registered Drives | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; padding: 40px; }
        .main-card { border-radius: 16px; border: 1px solid #e2e8f0; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .panel-header { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 700; margin-bottom: 5px; }
        .data-value { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
        .reg-row { border-bottom: 1px solid #f1f5f9; padding: 20px 0; }
        .reg-row:last-child { border-bottom: none; }
        .badge-status { font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; }
    </style>
</head>
<body>

<div class="container" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Registered Drives</h2>
        <a href="student_dashboard.php" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
    </div>

    <div class="card main-card p-4">
        <?php if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) { 
                $status = trim($row['status']);
        ?>
        <div class="row reg-row align-items-center">
            <div class="col-md-3">
                <div class="panel-header">Company</div>
                <div class="data-value"><?php echo htmlspecialchars($row['company_name']); ?></div>
            </div>
            <div class="col-md-2">
                <div class="panel-header">Role</div>
                <div class="data-value"><?php echo htmlspecialchars($row['role_offered']); ?></div>
            </div>
            <div class="col-md-2">
                <div class="panel-header">Package</div>
                <div class="data-value text-success">₹<?php echo htmlspecialchars($row['ctc']); ?></div>
            </div>
            <div class="col-md-2">
                <div class="panel-header">Status</div>
                <span class="badge bg-light text-dark border badge-status"><?php echo $status; ?></span>
            </div>
            <div class="col-md-3 text-end">
                <?php if($status == 'Registered') { ?>
                    <a href="?cancel_id=<?php echo $row['registration_id']; ?>" 
                       class="btn btn-sm btn-outline-danger" 
                       onclick="return confirm('Withdraw registration?');">Deregister</a>
                <?php } else { ?>
                    <button class="btn btn-sm btn-light" disabled>Locked</button>
                <?php } ?>
            </div>
        </div>
        <?php } } else { ?>
            <p class="text-center text-muted py-5">No registered drives found.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>