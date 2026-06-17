<?php
session_start();
include("db_connect.php");

// Strict Security Verification Gateway
if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

$alert_message = "";

// Handle Student Deletion
if(isset($_GET['delete']))
{
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    if(mysqli_query($conn, "DELETE FROM students WHERE student_id='$id'")) {
        $alert_message = "<div class='alert alert-success border-0 p-3 small mb-4' style='background-color: #f0fdf4; color: #166534; border-radius: 8px;'>✔ Student profile entry dropped cleanly from database layers.</div>";
    }
}

// Initialize Active Filter Variable Constraints Safely
$filter_usn = isset($_GET['filter_usn']) ? mysqli_real_escape_string($conn, trim($_GET['filter_usn'])) : '';
$filter_branch = isset($_GET['filter_branch']) ? mysqli_real_escape_string($conn, trim($_GET['filter_branch'])) : '';
$filter_semester = isset($_GET['filter_semester']) ? mysqli_real_escape_string($conn, trim($_GET['filter_semester'])) : '';

// Build Elastic Core SQL Query Engine Based On Dynamic Conditions
$where_clauses = [];

if($filter_usn != '') {
    $where_clauses[] = "usn LIKE '%$filter_usn%'";
}
if($filter_branch != '') {
    $where_clauses[] = "branch = '$filter_branch'";
}
if($filter_semester != '') {
    $where_clauses[] = "semester = '$filter_semester'";
}

$sql_query = "SELECT * FROM students";
if(count($where_clauses) > 0) {
    $sql_query .= " WHERE " . implode(" AND ", $where_clauses);
}
$sql_query .= " ORDER BY student_id DESC";

$result = mysqli_query($conn, $sql_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Records | Administrative Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #334155;
            padding: 40px;
        }
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        /* Administrative Panel Card Frames */
        .admin-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.03);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .card-header-custom {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 30px;
        }
        .card-header-custom h5 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a; /* Deep Charcoal Slate Administrative Profile Color */
            margin: 0;
        }
        
        /* Modern Filter Controls Layout Styling */
        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 6px;
        }
        .form-control-custom {
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #0f172a;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
        }
        
        /* High-Definition Responsive Admin Table Grid */
        .table-custom th {
            background-color: #f8fafc;
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
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        /* Action Buttons and Anchors */
        .btn-admin-primary {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            transition: background 0.2s ease;
        }
        .btn-admin-primary:hover {
            background-color: #1e293b;
            color: #ffffff;
        }
        .btn-admin-secondary {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-admin-secondary:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>

    <div class="admin-container">
        
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="admin_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Administrative Core Workspace</a>
                <h1 class="h3 fw-bold mt-1 text-dark" style="letter-spacing: -0.5px;">Student Records Matrix</h1>
            </div>
        </div>

        <?php echo $alert_message; ?>

        <div class="card admin-card">
            <div class="card-header-custom">
                <h5>Search & Segment Criteria Filtering</h5>
            </div>
            <div class="card-body p-4 bg-white">
                <form method="GET" action="" autocomplete="off">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label-custom">Filter by USN Identifier</label>
                            <input type="text" 
                                   name="filter_usn" 
                                   class="form-control form-control-custom text-uppercase" 
                                   placeholder="e.g., 1UV22IS" 
                                   value="<?php echo htmlspecialchars(isset($_GET['filter_usn']) ? $_GET['filter_usn'] : ''); ?>">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label-custom">Filter by Branch / Class</label>
                            <select name="filter_branch" class="form-select form-control-custom text-uppercase">
                                <option value="">All Branches</option>
                                <?php
                                // Dynamic distinct queries generation parsing for active branch tags
                                $branch_query = mysqli_query($conn, "SELECT DISTINCT branch FROM students WHERE branch != '' ORDER BY branch ASC");
                                while($b_row = mysqli_fetch_assoc($branch_query)) {
                                    $selected = ($filter_branch == $b_row['branch']) ? 'selected' : '';
                                    echo "<option value='".htmlspecialchars($b_row['branch'])."' $selected>".htmlspecialchars($b_row['branch'])."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label-custom">Semester</label>
                            <select name="filter_semester" class="form-select form-control-custom">
                                <option value="">All</option>
                                <?php
                                for($i=1; $i<=8; $i++) {
                                    $selected = ($filter_semester == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-admin-primary flex-grow-1 shadow-sm">
                                Apply Filters
                            </button>
                            <?php if($filter_usn != '' || $filter_branch != '' || $filter_semester != '') { ?>
                                <a href="manage_students.php" class="btn btn-admin-secondary">
                                    Clear
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card admin-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5>Indexed Student Profiles</h5>
                <span class="badge bg-dark-subtle text-dark border px-2 py-1 small fw-medium">
                    Records Evaluated: <?php echo mysqli_num_rows($result); ?>
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 6%;">Sys ID</th>
                            <th>Student USN</th>
                            <th>Full Name</th>
                            <th>Contact Email</th>
                            <th>Phone Context</th>
                            <th style="width: 10%;">Class (Branch)</th>
                            <th style="width: 8%;">Semester</th>
                            <th class="text-end" style="width: 12%;">Admin Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td class="text-muted font-monospace"><?php echo $row['student_id']; ?></td>
                                <td class="fw-semibold text-dark text-uppercase"><?php echo htmlspecialchars($row['usn']); ?></td>
                                <td class="fw-medium text-dark"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td class="text-muted small"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="text-muted font-monospace small"><?php echo htmlspecialchars($row['phone'] ? $row['phone'] : '—'); ?></td>
                                <td class="text-uppercase">
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                        <?php echo htmlspecialchars($row['branch'] ? $row['branch'] : 'N/A'); ?>
                                    </span>
                                </td>
                                <td class="text-center fw-semibold text-secondary"><?php echo htmlspecialchars($row['semester'] ? $row['semester'] : '—'); ?></td>
                                <td class="text-end">
                                    <a href="?delete=<?php echo $row['student_id']; ?>" 
                                       onclick="return confirm('Security Check: Confirm permanent removal of this student profile? This cascade event is completely irreversible.');" 
                                       class="btn btn-sm btn-outline-danger px-3" 
                                       style="border-radius: 6px; font-weight: 500; font-size: 0.8rem;">
                                        Drop Profile
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center text-muted py-5 bg-white'>No student tracking records matched the specified query parameters.</td></tr>";
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