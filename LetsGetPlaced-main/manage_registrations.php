<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Status Update
if(isset($_POST['update'])) {
    $reg_id = mysqli_real_escape_string($conn, $_POST['registration_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE registrations SET status='$status' WHERE registration_id='$reg_id'");
    header("Location: manage_registrations.php");
    exit();
}

// Build Query with Filters (if you want to add filtering later)
$sql = "SELECT r.*, s.full_name, c.company_name 
        FROM registrations r 
        INNER JOIN students s ON r.student_id = s.student_id 
        INNER JOIN companies c ON r.company_id = c.company_id 
        ORDER BY r.registration_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Registrations | Administrative Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; color: #334155; padding: 40px; }
        .admin-container { max-width: 1200px; margin: 0 auto; }
        .admin-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.03); overflow: hidden; margin-bottom: 30px; }
        .card-header-custom { background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 20px 30px; }
        .card-header-custom h5 { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0; }
        .table-custom th { background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; padding: 14px 24px; border-bottom: 1px solid #e2e8f0; }
        .table-custom td { padding: 16px 24px; vertical-align: middle; font-size: 0.9rem; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .btn-update { font-weight: 600; font-size: 0.8rem; border-radius: 6px; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="mb-4">
        <a href="admin_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Administrative Core Workspace</a>
        <h1 class="h3 fw-bold mt-1 text-dark">Drive Registrations Matrix</h1>
    </div>

    <div class="card admin-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5>Registered Application Profiles</h5>
            <span class="badge bg-dark-subtle text-dark border px-2 py-1 small fw-medium">
                Total Entries: <?php echo mysqli_num_rows($result); ?>
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td class="text-muted font-monospace small"><?php echo date('d M, Y', strtotime($row['registration_date'])); ?></td>
                        <td>
                            <span class="badge border text-dark bg-light"><?php echo $row['status']; ?></span>
                        </td>
                        <td class="text-center">
                            <form method="POST" class="d-flex justify-content-center gap-2">
                                <input type="hidden" name="registration_id" value="<?php echo $row['registration_id']; ?>">
                                <select name="status" class="form-select form-select-sm" style="width: 150px;">
                                    <?php 
                                    $statuses = ['Registered','Shortlisted','Aptitude Cleared','Technical Cleared','HR Cleared','Selected','Rejected'];
                                    foreach($statuses as $s) {
                                        echo "<option value='$s' ".($row['status'] == $s ? 'selected' : '').">$s</option>";
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-sm btn-dark btn-update" name="update">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>