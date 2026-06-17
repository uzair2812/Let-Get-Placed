<?php
session_start();

// Strict Authentication Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("db_connect.php");

$success_message = "";
$error_message = "";

/* Handle Ticket Resolution / Status Update Action */
if (isset($_POST['update_status'])) {
    $ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $admin_remarks = mysqli_real_escape_string($conn, $_POST['admin_remarks']);

    $update_query = "UPDATE tickets SET 
                     status = '$new_status', 
                     admin_remarks = '$admin_remarks', 
                     resolved_at = NOW() 
                     WHERE ticket_id = '$ticket_id'";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Ticket #$ticket_id successfully updated to status: <strong>$new_status</strong>.";
    } else {
        $error_message = "Database execution error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets Hub | Admin Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        /* Sidebar Restyling */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0f172a;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            padding: 15px 24px;
            letter-spacing: -0.5px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 15px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 11px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1e293b;
            color: #fff;
        }
        .sidebar a span {
            margin-right: 12px;
            font-size: 1.05rem;
        }
        /* Layout Spaces */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        .content-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .card-header-custom {
            padding: 20px 24px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        /* High-Definition Responsive Tables */
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom th {
            background-color: #f8f9fa;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            padding: 14px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-custom td {
            padding: 16px 24px;
            vertical-align: middle;
            font-size: 0.88rem;
            border-bottom: 1px solid #f1f5f9;
        }
        /* Custom Badges */
        .badge-pending { background-color: #fef3c7; color: #d97706; font-weight: 600; }
        .badge-progress { background-color: #dbeafe; color: #2563eb; font-weight: 600; }
        .badge-resolved { background-color: #dcfce7; color: #16a34a; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Sidebar Layout Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand">Let's Get Placed</div>
        <a href="admin_dashboard.php"><span>🏠</span> Dashboard</a>
        <a href="manage_students.php"><span>👨‍🎓</span> Manage Students</a>
        <a href="add_student.php"><span>➕</span> Add Student</a>
        <a href="manage_companies.php"><span>🏢</span> Manage Companies</a>
        <a href="add_company.php"><span>➕</span> Add Company</a>
        <a href="manage_registrations.php"><span>📋</span> Registrations</a>
        <a href="manage_notifications.php"><span>🔔</span> Notifications</a>
        <a href="manage_materials.php"><span>📚</span> Placement Materials</a>
        <a href="manage_tickets.php" class="active"><span>🎫</span> Support Tickets</a>
        <a href="placement_reports.php"><span>📊</span> Reports</a>
        <a href="placement_statistics.php"><span>📈</span> Statistics</a>
        <a href="logout.php" class="mt-4 text-danger"><span>🚪</span> Logout</a>
    </div>

    <!-- Main Dynamic Workspace -->
    <div class="main-content">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1" style="color: #0f172a;">Student Helpdesk Resolution Board</h1>
            <p class="text-muted m-0">Review complaints, manage credential update requests, and debug eligibility issues.</p>
        </div>

        <!-- Alert Notifications -->
        <?php if (!empty($success_message)) { ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <!-- Tickets Primary Controller Card -->
        <div class="card content-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold" style="color: #0f172a;">Incoming Support Logs</h5>
                <span class="badge bg-dark rounded-pill">Total System Records</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Student Context</th>
                            <th>Category / Reason</th>
                            <th>Submission Date</th>
                            <th>Status Badge</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Enhanced Query joining students context info seamlessly
                        $tickets_query = "SELECT t.*, s.full_name, s.usn, s.branch 
                                          FROM tickets t 
                                          JOIN students s ON t.student_id = s.student_id 
                                          ORDER BY t.ticket_id DESC";
                        
                        $result = mysqli_query($conn, $tickets_query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Assign corresponding UI color styles depending on configuration data
                                $status = htmlspecialchars($row['status']);
                                $status_badge = "badge-pending";
                                if ($status == "In Progress") $status_badge = "badge-progress";
                                if ($status == "Resolved") $status_badge = "badge-resolved";
                        ?>
                            <tr>
                                <td class="text-muted fw-mono">#<?php echo $row['ticket_id']; ?></td>
                                <td>
                                    <span class="fw-semibold text-dark d-block"><?php echo htmlspecialchars($row['full_name']); ?></span>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['usn']); ?> &bull; <?php echo htmlspecialchars($row['branch']); ?></small>
                                </td>
                                <td>
                                    <span class="fw-medium text-dark d-block"><?php echo htmlspecialchars($row['ticket_reason']); ?></span>
                                </td>
                                <td class="text-muted">
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                </td>
                                <td>
                                    <span class="badge p-2 px-3 rounded-pill <?php echo $status_badge; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-light btn-sm border fw-medium px-3 me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewModal<?php echo $row['ticket_id']; ?>">
                                        Inspect
                                    </button>
                                </td>
                            </tr>

                            <!-- Modernized Interactive Action Modal for Ticket #<?php echo $row['ticket_id']; ?> -->
                            <div class="modal fade" id="viewModal<?php echo $row['ticket_id']; ?>" Oskar-index="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                        <div class="modal-header border-bottom p-4">
                                            <h5 class="modal-title fw-bold">Ticket Registry details — #<?php echo $row['ticket_id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="manage_tickets.php" method="POST">
                                            <input type="hidden" name="ticket_id" value="<?php echo $row['ticket_id']; ?>">
                                            
                                            <div class="modal-body p-4">
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-6">
                                                        <label class="form-label text-muted small text-uppercase fw-semibold">Student Name</label>
                                                        <p class="fw-medium text-dark bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($row['full_name']); ?> (<?php echo htmlspecialchars($row['usn']); ?>)</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-muted small text-uppercase fw-semibold">Subject Context</label>
                                                        <p class="fw-medium text-dark bg-light p-3 rounded-3 mb-0"><?php echo htmlspecialchars($row['ticket_reason']); ?></p>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label text-muted small text-uppercase fw-semibold">Student's Detailed Log Explanation</label>
                                                    <div class="bg-light p-3 rounded-3 text-secondary" style="white-space: pre-line; line-height: 1.6; font-size: 0.9rem;">
                                                        <?php echo htmlspecialchars($row['ticket_description']); ?>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-4 align-items-center">
                                                    <div class="col-md-6">
                                                        <label class="form-label text-muted small text-uppercase fw-semibold">Attached Document Proof</label>
                                                        <div>
                                                            <?php if (!empty($row['proof_file'])) { ?>
                                                                <a href="uploads/tickets/<?php echo $row['proof_file']; ?>" target="_blank" class="btn btn-outline-primary btn-sm px-3 fw-medium d-inline-flex align-items-center">
                                                                    📄 Open Uploaded Document File
                                                                </a>
                                                            <?php } else { ?>
                                                                <span class="text-muted bg-light p-2 rounded small d-block">No physical layout attachment provided</span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-muted small text-uppercase fw-semibold">Modify Status State</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="Pending" <?php if($row['status'] == 'Pending') echo 'selected'; ?>>Pending (Awaiting Evaluation)</option>
                                                            <option value="In Progress" <?php if($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress (Investigation)</option>
                                                            <option value="Resolved" <?php if($row['status'] == 'Resolved') echo 'selected'; ?>>Resolved (Close Ticket)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label text-muted small text-uppercase fw-semibold">Admin Feedback & Remarks</label>
                                                    <textarea class="form-textarea form-control" name="admin_remarks" rows="3" placeholder="Provide notes regarding changes or rejection arguments for the student dashboard display..."><?php echo htmlspecialchars($row['admin_remarks'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer bg-light p-3 border-top d-flex justify-content-between" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                                                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">Close Overlay</button>
                                                <button type="submit" name="update_status" class="btn btn-primary px-4 fw-medium">Save Ticket Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted py-5'>✓ Zero complaints active. Everything running smoothly!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>