<?php
session_start();
include("db_connect.php");

// Tight Security Check
if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Center | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        .main-wrapper {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        /* High-Definition Notification and Drive Panels */
        .content-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
            margin-bottom: 16px;
            transition: border-color 0.2s ease;
        }
        .content-card:hover {
            border-color: #cbd5e1;
        }
        /* Notification Category Badges */
        .notify-badge {
            border-left: 4px solid #2563eb; /* Cobalt Blue Theme Anchor */
            background-color: #f8fafc;
        }
        .drive-badge {
            border-left: 4px solid #16a34a; /* Vibrant Green Success Accent */
            background-color: #fafafa;
        }
        .meta-timestamp {
            font-size: 0.78rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .ctc-highlight {
            color: #16a34a;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .section-header {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <!-- Return Navigation Handle -->
        <div class="mb-5 d-flex justify-content-between align-items-center">
            <div>
                <a href="student_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Back to Workspace</a>
                <h1 class="h3 fw-bold mt-1 text-dark" style="letter-spacing: -0.5px;">Updates & Live Announcements</h1>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Grid Pane: General Broadcasting System Notifications -->
            <div class="col-lg-7">
                <div class="d-flex align-items-center mb-3">
                    <span class="me-2">📢</span>
                    <h2 class="section-header mb-0">General Campus Notices</h2>
                </div>

                <?php
                $result = mysqli_query($conn, "SELECT * FROM notifications ORDER BY created_at DESC");
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                    <div class="card content-card notify-badge">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1rem;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h5>
                            <p class="text-muted small mb-3line-height-base mb-3">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </p>
                            <div class="meta-timestamp d-flex align-items-center">
                                <span>⏱️ Registered: <?php echo date('M d, Y • h:i A', strtotime($row['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo "<div class='p-5 text-center text-muted bg-white border rounded-3 small'>
                            No structural bulletins have been broadcast to your workspace yet.
                          </div>";
                }
                ?>
            </div>

            <!-- Right Grid Pane: Time-Sensitive Recruitment Campaigns -->
            <div class="col-lg-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="me-2">⚡</span>
                    <h2 class="section-header mb-0">Drives (Next 3 Days)</h2>
                </div>

                <?php
                $today = date('Y-m-d');
                $next = date('Y-m-d', strtotime('+3 days'));

                $drives = mysqli_query($conn, "
                    SELECT * FROM companies 
                    WHERE drive_date BETWEEN '$today' AND '$next' 
                    ORDER BY drive_date ASC
                ");

                if (mysqli_num_rows($drives) > 0) {
                    while($drive = mysqli_fetch_assoc($drives)) {
                ?>
                    <div class="card content-card drive-badge">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold text-dark m-0" style="font-size: 1.05rem;">
                                    <?php echo htmlspecialchars($drive['company_name']); ?>
                                </h5>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small fw-medium" style="font-size: 0.72rem;">
                                    Active Campaign
                                </span>
                            </div>
                            
                            <div class="small text-muted mb-3">
                                <div class="mb-1">💼 Profile Offering: <span class="text-dark fw-medium"><?php echo htmlspecialchars($drive['role_offered']); ?></span></div>
                                <div class="mb-1">💰 Financial Package: <span class="ctc-highlight">₹<?php echo htmlspecialchars($drive['ctc']); ?> LPA</span></div>
                            </div>
                            
                            <div class="p-2 px-3 bg-light rounded-2 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-medium">📅 Calendar Date:</span>
                                <span class="fw-bold text-primary small"><?php echo date('F d, Y', strtotime($drive['drive_date'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo "<div class='p-5 text-center text-muted bg-white border rounded-3 small'>
                            🛡️ No external corporate recruitment events are scheduled within the upcoming 3-day window.
                          </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>