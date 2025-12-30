<?php
require_once __DIR__ . '/config/config.php';
session_start();

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_file'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    $file = $_FILES['import_file'];
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "File upload error: " . $file['error'];
    } else {
        $tempPath = $file['tmp_name'];

        if ($fileType === 'json') {
            $result = importFromJSON($tempPath);
            if ($result === true) {
                $success = true;
            } else {
                $error = $result;
            }
        } elseif ($fileType === 'md' || $fileType === 'markdown') {
            $result = importFromMarkdown($tempPath);
            if ($result === true) {
                $success = true;
            } else {
                $error = $result;
            }
        } else {
            $error = "Unsupported file format. Please upload JSON or Markdown files.";
        }
    }
}

function importFromJSON($filePath) {
    $content = file_get_contents($filePath);
    if ($content === false) {
        return "Failed to read file.";
    }

    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Invalid JSON file: " . json_last_error_msg();
    }

    if (!isset($data['zettels']) || !is_array($data['zettels'])) {
        return "Invalid JSON structure. Missing 'zettels' array.";
    }

    $imported = 0;
    $skipped = 0;
    $zettelsDir = ZETTELS_DIR;

    foreach ($data['zettels'] as $zettel) {
        if (empty($zettel['id']) || empty($zettel['title']) || empty($zettel['content'])) {
            $skipped++;
            continue;
        }

        $filepath = $zettelsDir . '/' . $zettel['id'] . '.txt';

        // Check if zettel already exists
        if (file_exists($filepath)) {
            $skipped++;
            continue;
        }

        // Decode HTML entities and ensure proper UTF-8 encoding
        $zettel['title'] = html_entity_decode($zettel['title'], ENT_QUOTES, 'UTF-8');
        $zettel['content'] = html_entity_decode($zettel['content'], ENT_QUOTES, 'UTF-8');

        // Ensure required fields
        $zettel['created_at'] = $zettel['created_at'] ?? date('Y-m-d H:i:s');
        $zettel['updated_at'] = $zettel['updated_at'] ?? date('Y-m-d H:i:s');
        $zettel['tags'] = $zettel['tags'] ?? [];
        $zettel['links'] = $zettel['links'] ?? [];

        $file = fopen($filepath, 'w');
        if (flock($file, LOCK_EX)) {
            fwrite($file, json_encode($zettel, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            flock($file, LOCK_UN);
            $imported++;
        } else {
            $skipped++;
        }
        fclose($file);
    }

    // Invalidate tag cache
    @unlink($zettelsDir . '/.tag_cache.json');

    return "Imported $imported zettels. Skipped $skipped (duplicates or invalid).";
}

function importFromMarkdown($filePath) {
    // This would be more complex - you'd need to parse the markdown structure
    // to extract individual zettels. For now, we'll just return a message.
    return "Markdown import is not yet implemented. Please use JSON format for now.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Zettels - Zettelkasten</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Import Zettels</h1>
        <a href="index.php" class="back-link">Back to Zettelkasten</a>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">Import completed successfully!</div>
        <?php endif; ?>

        <div class="import-container">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="import-option">
                    <h3>JSON Backup File</h3>
                    <p>Import from a previously exported JSON backup file (recommended)</p>
                    <input type="file" name="import_file" accept=".json" required>
                </div>

                <div class="import-option">
                    <h3>Markdown File</h3>
                    <p>Import from a single Markdown file export (not yet implemented)</p>
                    <input type="file" name="import_file" accept=".md,.markdown" disabled>
                </div>

                <button type="submit" class="btn-import">Import Zettels</button>
            </form>
        </div>
    </div>
</body>
</html>
