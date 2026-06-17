<?php
session_start();
include("db_connect.php");

// Security Gateway
if(!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

// --- HANDLE DELETE ACTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $filename = basename($_POST['filename']);
    $stmt = $conn->prepare("DELETE FROM student_resumes WHERE filename = ? AND student_id = ?");
    $stmt->bind_param("si", $filename, $student_id);
    if ($stmt->execute()) {
        if (file_exists($uploadDir . $filename)) unlink($uploadDir . $filename);
        $_SESSION['upload_message'] = "Resume successfully deleted.";
        $_SESSION['upload_message_type'] = "success";
    }
    header("Location: upload_resume.php"); exit();
}

// --- HANDLE UPLOAD ACTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resumes'])) {
    $files = $_FILES['resumes'];
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'doc', 'docx'])) {
                $newFileName = "res_" . $student_id . "_" . time() . "_" . $i . "." . $ext;
                if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $newFileName)) {
                    $stmt = $conn->prepare("INSERT INTO student_resumes (student_id, filename) VALUES (?, ?)");
                    $stmt->bind_param("is", $student_id, $newFileName);
                    $stmt->execute();
                }
            }
        }
    }
    header("Location: upload_resume.php"); exit();
}

// --- FETCH FILES ---
$existingResumes = [];
$stmt = $conn->prepare("SELECT * FROM student_resumes WHERE student_id = ? ORDER BY upload_date DESC");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $filePath = $uploadDir . $row['filename'];
    if (file_exists($filePath)) {
        $existingResumes[] = [
            'filename' => $row['filename'],
            'path' => $filePath,
            'size' => round(filesize($filePath) / 1024, 2) . ' KB',
            'date' => date("d M, Y H:i", strtotime($row['upload_date']))
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume Vault | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; color: #334155; padding: 40px; }
        .resume-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 6px 12px -2px rgba(15,23,42,0.08);
            padding: 30px;
            min-height: 280px; /* bigger box */
            display:flex;
            flex-direction:column;
            justify-content:space-between;
        }
        .resume-card h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .resume-meta {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 15px;
        }
        .resume-actions .btn {
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
        }
        .upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            background:#fff;
            padding:50px;
            text-align:center;
            cursor:pointer;
            transition: all 0.2s ease;
            min-height: 280px; /* match card size */
        }
        .upload-box:hover { border-color:#0d6efd; background:#f8fafc; }
    </style>
</head>
<body>

<div class="container">
    <div class="mb-4">
        <a href="student_dashboard.php" class="text-decoration-none text-muted small fw-medium">← Back to Dashboard</a>
        <h1 class="h3 fw-bold mt-1 text-dark">Resume Vault</h1>
        <p class="text-muted small">Secure management of your uploaded resumes</p>
    </div>

    <div class="row g-4">
        <!-- Upload Box -->
        <div class="col-md-4">
            <form action="upload_resume.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                <label for="fileInput" class="upload-box w-100 h-100">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-cloud-arrow-up text-secondary" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-4.473 2.326 4.5 4.5 0 0 0-3.02 7.326A3.5 3.5 0 0 0 3.5 16h9a3.5 3.5 0 0 0 2.993-5.348A4.5 4.5 0 0 0 12.473 2.326 5.53 5.53 0 0 0 8 0zM5.5 8.5a.5.5 0 0 1 .5-.5h2V5.707l-1.146 1.147a.5.5 0 0 1-.708-.708l2-2 .007-.007a.5.5 0 0 1 .701.007l2 2a.5.5 0 0 1-.708.708L9 5.707V8h2a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </div>
                    <p class="fw-semibold text-secondary">Click to Upload Resumes</p>
                    <input type="file" name="resumes[]" id="fileInput" multiple accept=".pdf,.doc,.docx" class="d-none" onchange="document.getElementById('uploadForm').submit();">
                </label>
            </form>
        </div>

        <!-- Resume Cards -->
        <?php foreach ($existingResumes as $resume): ?>
        <div class="col-md-4">
            <div class="resume-card h-100">
                <div>
                    <h5 class="text-truncate"><?php echo htmlspecialchars($resume['filename']); ?></h5>
                    <div class="resume-meta">
                        Size: <?php echo $resume['size']; ?><br>
                        Uploaded: <?php echo $resume['date']; ?>
                    </div>
                </div>
                <div class="resume-actions d-flex gap-2 mt-3">
                    <a href="<?php echo $resume['path']; ?>" target="_blank" class="btn btn-sm btn-primary flex-fill">View</a>
                    <a href="<?php echo $resume['path']; ?>" download class="btn btn-sm btn-success flex-fill">Download</a>
                    <form action="upload_resume.php" method="POST" class="flex-fill" onsubmit="return confirm('Delete this resume?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="filename" value="<?php echo $resume['filename']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
