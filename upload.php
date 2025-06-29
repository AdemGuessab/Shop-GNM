<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "Upload successful! File saved as: " . htmlspecialchars($filename);
        } else {
            echo "Upload failed!";
        }
    } else {
        echo "No file uploaded or error.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required />
    <button type="submit">Upload Image</button>
</form>
