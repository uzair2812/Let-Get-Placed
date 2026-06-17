<?php
session_start();
include("db_connect.php");

// Tight Security Check
if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

$categories = [
    "Aptitude"  => ["icon" => "📊", "desc" => "Quantitative, Logical Reasoning, & Verbal preparation kits"],
    "Technical" => ["icon" => "💻", "desc" => "Core Programming, Data Structures, System Design, & DBMS notes"],
    "Interview" => ["icon" => "🤝", "desc" => "HR questions, behavioral assessments, and real company case studies"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preparation Hub | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        .main-wrapper {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }
        /* Tab Navigation Styling */
        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0;
            gap: 8px;
        }
        .nav-tabs-custom .nav-link {
            border: none;
            color: #64748b;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-tabs-custom .nav-link:hover {
            color: #0f172a;
            background-color: #f1f5f9;
        }
        .nav-tabs-custom .nav-link.active {
            color: #2563eb;
            background: none;
            font-weight: 600;
        }
        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #2563eb;
        }
        /* Content Card Customization */
        .repository-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            overflow: hidden;
        }
        /* High-Definition Tables */
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom th {
            background-color: #f8f9fa;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 14px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-custom td {
            padding: 16px 24px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .file-badge {
            background-color: #eff6ff;
            color: #2563eb;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <!-- Dashboard Header Context -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="student_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Workspace Terminal</a>
                <h1 class="h3 fw-bold mt-1 text-dark" style="letter-spacing: -0.5px;">Placement Preparation Repository</h1>
            </div>
        </div>

        <!-- Filter Navigation Layout Control tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="materialTabs" role="tablist">
            <?php 
            $isActive = true;
            foreach($categories as $catKey => $catData) { 
            ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $isActive ? 'active' : ''; ?>" 
                            id="<?php echo $catKey; ?>-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#panel-<?php echo $catKey; ?>" 
                            type="button" 
                            role="tab">
                        <span class="me-1"><?php echo $catData['icon']; ?></span> <?php echo $catKey; ?>
                    </button>
                </li>
            <?php 
                $isActive = false;
            } 
            ?>
        </ul>

        <!-- Categorized Grid Output Content Panels -->
        <div class="tab-content" id="materialTabsContent">
            <?php 
            $isActive = true;
            foreach($categories as $catKey => $catData) { 
            ?>
                <div class="tab-pane fade <?php echo $isActive ? 'show active' : ''; ?>" 
                     id="panel-<?php echo $catKey; ?>" 
                     role="tabpanel">
                     
                    <div class="p-2 mb-3">
                        <p class="text-muted small mb-0">💡 <?php echo $catData['desc']; ?></p>
                    </div>

                    <div class="card repository-card">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Resource Name</th>
                                        <th style="width: 15%;">Type</th>
                                        <th class="text-end" style="width: 20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = mysqli_prepare($conn, "SELECT * FROM placement_materials WHERE category = ? ORDER BY material_id DESC");
                                    mysqli_stmt_bind_param($stmt, "s", $catKey);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);

                                    if (mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            $filename = $row['material_file'];
                                            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)) ?: 'DOC';
                                    ?>
                                        <tr>
                                            <td class="fw-semibold text-dark">
                                                <?php echo htmlspecialchars($row['title']); ?>
                                            </td>
                                            <td>
                                                <span class="file-badge"><?php echo $ext; ?></span>
                                            </td>
                                            <td class="text-end">
                                                <a href="uploads/materials/<?php echo htmlspecialchars($filename); ?>" 
                                                   target="_blank" 
                                                   class="btn btn-light btn-sm border fw-medium px-3">
                                                    Download ↓
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr>
                                                <td colspan='3' class='text-center text-muted py-5 bg-white'>
                                                    No verification study items posted yet for this section.
                                                </td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php 
                $isActive = false;
            } 
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>