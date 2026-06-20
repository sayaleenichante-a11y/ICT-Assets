<?php
global $conn;
include("../../includes/auth.php");
include("../../config/db.php");
include("../../includes/header.php");
include("../../includes/sidebar.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* ---------- DOCUMENT INFO ---------- */
$query = "
SELECT d.*, a.asset_name, a.serial_number, a.asset_id, c.category_name
FROM documents d
JOIN assets a ON d.asset_id = a.asset_id
LEFT JOIN asset_categories c ON a.category_id = c.category_id
WHERE d.document_id = '$id'
";
$document = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$document) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Document not found.</div></div>";
    include("../../includes/footer.php");
    exit();
}

$file_ext = strtolower(pathinfo($document['file_name'], PATHINFO_EXTENSION));
$is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif']);
$is_pdf = ($file_ext === 'pdf');
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Document Details: <?= htmlspecialchars($document['file_name']) ?></h2>
        <div>
            <a href="../../<?= $document['file_path'] ?>" target="_blank" class="btn btn-primary">
                <i class="bi bi-download"></i> Download File
            </a>
            <a href="documents_list.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <!-- LEFT COLUMN: DOCUMENT & ASSET INFO -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Document Metadata</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th width="40%">Doc ID</th><td><?= $document['document_id'] ?></td></tr>
                        <tr><th>Type</th><td><span class="badge bg-secondary"><?= str_replace('_', ' ', $document['document_type']) ?></span></td></tr>
                        <tr><th>Uploaded</th><td><?= date('d M Y, H:i', strtotime($document['uploaded_at'])) ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Associated Asset</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Asset Name</label>
                        <a href="../assets/asset_details.php?id=<?= $document['asset_id'] ?>" class="h5 fw-bold text-decoration-none">
                            <?= htmlspecialchars($document['asset_name']) ?>
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Serial Number</label>
                        <code><?= htmlspecialchars($document['serial_number']) ?></code>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Category</label>
                        <span><?= htmlspecialchars($document['category_name']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PREVIEW -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">File Preview</h5>
                </div>
                <div class="card-body text-center p-4">
                    <?php if($is_image): ?>
                        <img src="../../<?= $document['file_path'] ?>" class="img-fluid rounded shadow-sm" style="max-height: 600px;" alt="Preview">
                    <?php elseif($is_pdf): ?>
                        <embed src="../../<?= $document['file_path'] ?>" type="application/pdf" width="100%" height="600px" />
                    <?php else: ?>
                        <div class="py-5">
                            <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                            <p class="mt-3 fs-5">Preview not available for <strong>.<?= $file_ext ?></strong> files.</p>
                            <a href="../../<?= $document['file_path'] ?>" target="_blank" class="btn btn-outline-primary mt-2">Open in New Tab</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
