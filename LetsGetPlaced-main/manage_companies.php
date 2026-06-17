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

// Variables to handle updating data state context mapping
$edit_mode = false;
$edit_id = "";
$edit_name = "";
$edit_role = "";
$edit_ctc = "";
$edit_date = "";
$edit_cgpa = "";
$edit_desc = "";
$edit_jd = "";

// 1. Handle Campaign Deletion Route
if(isset($_GET['delete']))
{
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Fetch target record first to scrub the physical PDF file asset off storage disks
    $file_check = mysqli_query($conn, "SELECT jd_file FROM companies WHERE company_id='$id'");
    if($file_row = mysqli_fetch_assoc($file_check)) {
        $target_file = "uploads/jd/" . $file_row['jd_file'];
        if(!empty($file_row['jd_file']) && file_exists($target_file)) {
            unlink($target_file); // Removes physical file asset cleanly
        }
    }
    
    if(mysqli_query($conn, "DELETE FROM companies WHERE company_id='$id'")) {
        $alert_message = "<div class='alert alert-success border-0 p-3 small mb-4' style='background-color: #f0fdf4; color: #166534; border-radius: 8px;'>✔ Recruitment campaign entry and associated JD files purged from cluster.</div>";
    } else {
        $alert_message = "<div class='alert alert-danger border-0 p-3 small mb-4' style='background-color: #fef2f2; color: #991b1b; border-radius: 8px;'>❌ Deletion Failed: " . mysqli_error($conn) . "</div>";
    }
}

// 2. Fetch Existing Context to Populate the Editor Panel
if(isset($_GET['edit']))
{
    $edit_mode = true;
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    
    $fetch_res = mysqli_query($conn, "SELECT * FROM companies WHERE company_id='$edit_id'");
    if($e_row = mysqli_fetch_assoc($fetch_res)) {
        $edit_name = $e_row['company_name'];
        $edit_role = $e_row['role_offered'];
        $edit_ctc  = $e_row['ctc'];
        $edit_date = $e_row['drive_date'];
        $edit_cgpa = $e_row['eligibility_cgpa'];
        $edit_desc = $e_row['company_desc'];
        $edit_jd   = $e_row['jd_file'];
    }
}

// 3. Commit Changes via Update Post Protocol Request
if(isset($_POST['update_company']))
{
    $edit_id      = mysqli_real_escape_string($conn, $_POST['company_id']);
    $company_name = mysqli_real_escape_string($conn, trim($_POST['company_name']));
    $role         = mysqli_real_escape_string($conn, trim($_POST['role']));
    $ctc          = mysqli_real_escape_string($conn, trim($_POST['ctc']));
    $drive_date   = mysqli_real_escape_string($conn, trim($_POST['drive_date']));
    $cgpa         = mysqli_real_escape_string($conn, trim($_POST['cgpa']));
    $description  = mysqli_real_escape_string($conn, trim($_POST['company_desc']));
    $existing_jd  = mysqli_real_escape_string($conn, $_POST['existing_jd_file']);

    $jd_file = $existing_jd; // Fallback default preserves file state index lines

    // Evaluate if a fresh document override exists
    if(isset($_FILES['jd_pdf']) && $_FILES['jd_pdf']['name'] != "")
    {
        $file_name = $_FILES['jd_pdf']['name'];
        $tmp_name  = $_FILES['jd_pdf']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if($ext === 'pdf')
        {
            // Clear previous structural dependency references from servers
            if(!empty($existing_jd) && file_exists("uploads/jd/" . $existing_jd)) {
                unlink("uploads/jd/" . $existing_jd);
            }

            $target_dir = "uploads/jd";
            $jd_file = time() . '_jd_' . rand(1000, 9999) . '.pdf';
            move_uploaded_file($tmp_name, $target_dir . "/" . $jd_file);
        }
        else
        {
            $alert_message = "<div class='alert alert-danger border-0 p-3 small mb-4' style='background-color: #fef2f2; color: #991b1b; border-radius: 8px;'>⚠️ Update Aborted: Invalid format token targeting system update arrays. Only PDFs accepted.</div>";
        }
    }

    if(empty($alert_message))
    {
        $update_sql = "UPDATE companies SET 
                        company_name='$company_name', 
                        role_offered='$role', 
                        ctc='$ctc', 
                        drive_date='$drive_date', 
                        eligibility_cgpa='$cgpa', 
                        company_desc='$description', 
                        jd_file='$jd_file' 
                       WHERE company_id='$edit_id'";

        if(mysqli_query($conn, $update_sql)) {
            $alert_message = "<div class='alert alert-success border-0 p-3 small mb-4' style='background-color: #f0fdf4; color: #166534; border-radius: 8px;'>✔ Corporate structural lines for $company_name updated correctly.</div>";
            $edit_mode = false; // Relieve user context interface state
        } else {
            $alert_message = "<div class='alert alert-danger border-0 p-3 small mb-4' style='background-color: #fef2f2; color: #991b1b; border-radius: 8px;'>❌ Database Sync Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

// 4. Extraction Logic for Operational Matrix Filtering Parameters
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_ctc      = isset($_GET['min_ctc']) && $_GET['min_ctc'] !== '' ? floatval($_GET['min_ctc']) : 0;
$max_cgpa     = isset($_GET['max_cgpa']) && $_GET['max_cgpa'] !== '' ? floatval($_GET['max_cgpa']) : '';

// Establish multi-layered dynamic queries conditionally
$where_clauses = [];

if ($search_query !== '') {
    $escaped_search = mysqli_real_escape_string($conn, $search_query);
    $where_clauses[] = "(company_name LIKE '%$escaped_search%' OR role_offered LIKE '%$escaped_search%')";
}
if ($min_ctc > 0) {
    $where_clauses[] = "ctc >= $min_ctc";
}
if ($max_cgpa !== '') {
    $where_clauses[] = "eligibility_cgpa <= $max_cgpa";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

$final_query = "SELECT * FROM companies $where_sql ORDER BY company_id DESC";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Corporate Campaigns | Administrative Terminal</title>
    
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
            max-width: 1300px;
            margin: 0 auto;
        }
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
            color: #0f172a;
            margin: 0;
        }
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
            background-color: #ffffff;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #0f172a;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
        }
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
        .pdf-link-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-admin-primary {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
        }
        .btn-admin-primary:hover { background-color: #1e293b; color: #fff; }
        
        .btn-admin-filter {
            background-color: #475569;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
        }
        .btn-admin-filter:hover { background-color: #334155; color: #fff; }

        .btn-admin-cancel {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
        }
        .btn-admin-cancel:hover { background-color: #e2e8f0; color: #0f172a; }
    </style>
</head>
<body>

    <div class="admin-container">
        
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="admin_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Administrative Core Workspace</a>
                <h1 class="h3 fw-bold mt-1 text-dark" style="letter-spacing: -0.5px;">Corporate Recruitment Infrastructure</h1>
            </div>
            <div>
                <a href="add_company.php" class="btn btn-admin-primary shadow-sm">+ Launch Campaign File</a>
            </div>
        </div>

        <?php echo $alert_message; ?>

        <!-- DYNAMIC SECTION: Active Record Modification Interface (Toggled on Edit Request) -->
        <?php if($edit_mode) { ?>
            <div class="card admin-card border-primary" style="border-width: 2px;">
                <div class="card-header-custom bg-light">
                    <h5 class="text-primary">⚙ Modify Corporate Drive Deployment Profile: <?php echo htmlspecialchars($edit_name); ?></h5>
                </div>
                <div class="card-body p-4 bg-white">
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="company_id" value="<?php echo $edit_id; ?>">
                        <input type="hidden" name="existing_jd_file" value="<?php echo $edit_jd; ?>">

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">Corporate Legal Name</label>
                                <input type="text" name="company_name" class="form-control form-control-custom" value="<?php echo htmlspecialchars($edit_name); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Profile Role Offering</label>
                                <input type="text" name="role" class="form-control form-control-custom" value="<?php echo htmlspecialchars($edit_role); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Financial Package (CTC in LPA)</label>
                                <input type="number" step="0.01" name="ctc" class="form-control form-control-custom" value="<?php echo htmlspecialchars($edit_ctc); ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-custom">Target Event Calendar Date</label>
                                <input type="date" name="drive_date" class="form-control form-control-custom" value="<?php echo $edit_date; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Minimum Academic Criteria Cutoff (CGPA)</label>
                                <input type="number" step="0.01" name="cgpa" class="form-control form-control-custom" value="<?php echo htmlspecialchars($edit_cgpa); ?>" min="0" max="10" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Overwrite Job Description Portfolio (PDF)</label>
                                <input type="file" name="jd_pdf" class="form-control form-control-custom" accept=".pdf">
                                <div class="form-text small text-muted">Leave blank to retain active file mapping.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Company Profile Overview & Drive Description</label>
                            <textarea name="company_desc" class="form-control form-control-custom" rows="3"><?php echo htmlspecialchars($edit_desc); ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="update_company" class="btn btn-admin-primary">Commit Changes</button>
                            <a href="manage_companies.php" class="btn btn-admin-cancel">Dismiss</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>

        <!-- FILTER WORKSPACE MATRIX LAYER -->
        <div class="card admin-card mb-4">
            <div class="card-header-custom bg-light py-3">
                <span class="text-uppercase tracking-wider fw-bold text-secondary" style="font-size: 0.72rem; letter-spacing:0.5px;">Targeted Search & Sorting Controls</span>
            </div>
            <div class="card-body p-4 bg-white">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label-custom">Enterprise Search Keyword</label>
                        <input type="text" name="search" class="form-control form-control-custom" placeholder="Search company or role parameters..." value="<?php echo htmlspecialchars($search_query); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Compensation Base Threshold (CTC ≥ X LPA)</label>
                        <input type="number" step="0.1" name="min_ctc" class="form-control form-control-custom" placeholder="e.g. 6.0" value="<?php echo $min_ctc > 0 ? htmlspecialchars($min_ctc) : ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Max Eligible CGPA Threshold (Cutoff ≤ X)</label>
                        <select name="max_cgpa" class="form-select form-control-custom">
                            <option value="">Show All Criteria Layers</option>
                            <option value="6.0" <?php echo $max_cgpa === 6.0 ? 'selected' : ''; ?>>Eligible up to 6.0 CGPA</option>
                            <option value="7.0" <?php echo $max_cgpa === 7.0 ? 'selected' : ''; ?>>Eligible up to 7.0 CGPA</option>
                            <option value="8.0" <?php echo $max_cgpa === 8.0 ? 'selected' : ''; ?>>Eligible up to 8.0 CGPA</option>
                            <option value="9.0" <?php echo $max_cgpa === 9.0 ? 'selected' : ''; ?>>Eligible up to 9.0 CGPA</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-admin-filter flex-grow-1">Apply Filters</button>
                        <?php if($search_query !== '' || $min_ctc > 0 || $max_cgpa !== '') { ?>
                            <a href="manage_companies.php" class="btn btn-admin-cancel px-3" title="Clear All Settings">✕</a>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- CENTRAL MATRIX PANEL: Corporate Records Monitoring Interface -->
        <div class="card admin-card">
            <div class="card-header-custom">
                <h5>Active Institutional Drive Calendars</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 15%;">Corporate Entity</th>
                            <th style="width: 15%;">Target Role</th>
                            <th style="width: 25%;">Drive Description / Context Summary</th>
                            <th style="width: 10%;">Compensation</th>
                            <th style="width: 10%;">Eligibility</th>
                            <th style="width: 10%;">Schedule</th>
                            <th class="text-end" style="width: 10%;">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, $final_query);
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td class="text-muted font-monospace"><?php echo $row['company_id']; ?></td>
                                <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['company_name']); ?></td>
                                <td class="fw-medium text-secondary"><?php echo htmlspecialchars($row['role_offered']); ?></td>
                                <td>
                                    <div class="text-muted small" style="max-height: 65px; overflow-y: auto; font-size:0.85rem; line-height:1.4;">
                                        <?php echo !empty($row['company_desc']) ? nl2br(htmlspecialchars($row['company_desc'])) : '<span class="text-black-50 text-opacity-25 italic">No descriptive context recorded.</span>'; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">₹<?php echo htmlspecialchars($row['ctc']); ?> LPA</span>
                                </td>
                                <td class="font-monospace fw-semibold text-dark" style="font-size: 0.85rem;">
                                    🎓 ≥ <?php echo htmlspecialchars($row['eligibility_cgpa']); ?> CGPA
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border px-2 py-1 fw-semibold font-monospace" style="font-size:0.75rem;">
                                        <?php echo date('M d, Y', strtotime($row['drive_date'])); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex flex-column gap-2 align-items-end">
                                        <?php if(!empty($row['jd_file']) && file_exists("uploads/jd/" . $row['jd_file'])) { ?>
                                            <a href="uploads/jd/<?php echo $row['jd_file']; ?>" target="_blank" class="pdf-link-badge">
                                                📄 View JD
                                            </a>
                                        <?php } ?>
                                        
                                        <div class="d-flex gap-2 justify-content-end align-items-center mt-1">
                                            <a href="?edit=<?php echo $row['company_id']; ?>" class="text-decoration-none text-primary fw-medium small">
                                                Update
                                            </a>
                                            <span class="text-muted small">|</span>
                                            <a href="?delete=<?php echo $row['company_id']; ?>" 
                                               onclick="return confirm('Security Check: Confirm processing removal of this placement drive campaign mapping?');" 
                                               class="text-decoration-none text-danger fw-medium small">
                                                Drop
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center text-muted py-5 bg-white'>No corporate entities match the assigned structural filtration parameters inside the matrix.</td></tr>";
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