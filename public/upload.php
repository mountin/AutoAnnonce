<!DOCTYPE html>
<html>
<head>
    <title>File Upload Test</title>
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="uploadedFile" />
    <button type="submit">Upload</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = './uploads/images/';
    $uploadFile = $uploadDir . basename($_FILES['uploadedFile']['name']);

    if (move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $uploadFile)) {
        echo "File successfully uploaded: " . htmlspecialchars(basename($_FILES['uploadedFile']['name']));
    } else {
        echo "Error uploading the file.";
    }
}
?>
</body>
</html>