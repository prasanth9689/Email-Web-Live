<?php
require 'db.php';

$maxSize = 25 * 1024 * 1024;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $draftId = $_POST['draft_id'] ?? null;
    $file = $_FILES['attachment'] ?? null;

    if (!$draftId || !$file || $file['error'] !== UPLOAD_ERR_OK) {
        echo "Invalid input or upload error.";
        exit;
    }

    if ($file['size'] > $maxSize) {
        echo "File exceeds 25MB limit.";
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = uniqid("attach_") . '.' . $ext;
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $filePath = $uploadDir . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        echo "Failed to store file.";
        exit;
    }

    // Store in DB
    $stmt = $pdo->prepare("INSERT INTO attachments (draft_id, filename, filepath) VALUES (?, ?, ?)");
    $stmt->execute([$draftId, $file['name'], "uploads/" . $safeName]);

    echo "File uploaded and linked to draft #$draftId.";
}
