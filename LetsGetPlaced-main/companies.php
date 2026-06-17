<?php
session_start();
include("db_connect.php");

// Security Gateway
if(!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Drives | Let's Get Placed</title>
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
        .btn-view { font-weight: 600; font-size: 0.8rem; border-radius: 6px; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="mb-4">
        <a href="student_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Back to Dashboard</a>
        <h1 class="h3 fw-bold mt-1 text-dark">Available Placement Drives</h1>
    </div>

    <div class="card admin-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5>Corporate Recruitment Matrix</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Role</th>
                        <th>Package (CTC)</th>
                        <th>Eligibility</th>
                        <th>Drive Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM companies ORDER BY drive_date ASC");
                    while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td class="text-primary"><?php echo htmlspecialchars($row['role_offered']); ?></td>
                        <td class="text-success fw-bold"><?php echo htmlspecialchars($row['ctc']); ?> LPA</td>
                        <td><?php echo htmlspecialchars($row['eligibility_cgpa']); ?> CGPA</td>
                        <td class="text-muted font-monospace"><?php echo date('d M, Y', strtotime($row['drive_date'])); ?></td>
                        <td class="text-center">
                            <a href="view_company.php?id=<?php echo $row['company_id']; ?>" 
                               class="btn btn-sm btn-dark btn-view px-3">View Details</a>
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