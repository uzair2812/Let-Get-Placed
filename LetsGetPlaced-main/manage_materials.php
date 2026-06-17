<?php
session_start();

// Security: Restrict to Admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("db_connect.php");

// Define isolated path for administrative materials
$upload_dir = "uploads/materials/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// --- Handle Material Upload ---
if (isset($_POST['upload'])) {
    $category = trim($_POST['category']);
    $title = trim($_POST['title']);

    if (isset($_FILES['material']) && $_FILES['material']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['material']['name'];
        $tmp = $_FILES['material']['tmp_name'];
        $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $allowed_extensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'pptx', 'xlsx'];

        if (in_array($file_ext, $allowed_extensions)) {
            // Prefix 'mat_' ensures no conflict with student 'res_' files
            $new_filename = "mat_" . time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $file);
            
            if (move_uploaded_file($tmp, $upload_dir . $new_filename)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO placement_materials (category, title, material_file) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sss", $category, $title, $new_filename);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                header("Location: manage_materials.php?status=success");
                exit();
            }
        }
    }
}

// --- Handle Material Deletion ---
if (isset($_GET['delete'])) {
    $material_id = intval($_GET['delete']);
    
    $query = mysqli_query($conn, "SELECT material_file FROM placement_materials WHERE material_id = $material_id");
    if ($row = mysqli_fetch_assoc($query)) {
        $file_path = $upload_dir . $row['material_file'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        mysqli_query($conn, "DELETE FROM placement_materials WHERE material_id = $material_id");
    }
    header("Location: manage_materials.php?status=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Placement Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .main-content { margin-left: 260px; padding: 40px; }
        .content-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; }
    </style>
</head>
<body>

    <div class="main-content">
        <h1 class="h3 fw-bold">Manage Educational Materials</h1>
        
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="content-card p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option>Aptitude</option>
                                <option>Technical</option>
                                <option>Interview</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Select File</label>
                            <input type="file" name="material" class="form-control" required>
                        </div>
                        <button class="btn btn-success w-100" name="upload">Upload Material</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="content-card p-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fetch = mysqli_query($conn, "SELECT * FROM placement_materials ORDER BY material_id DESC");
                            while ($row = mysqli_fetch_assoc($fetch)) {
                                echo "<tr>
                                    <td>{$row['title']}</td>
                                    <td>{$row['category']}</td>
                                    <td><a href='{$upload_dir}{$row['material_file']}' target='_blank'>View</a></td>
                                    <td><a href='manage_materials.php?delete={$row['material_id']}' class='btn btn-danger btn-sm'>Remove</a></td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>