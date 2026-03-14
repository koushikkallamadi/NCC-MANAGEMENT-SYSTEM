<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$officer_id = $_SESSION['user_id'];

// Get Officer Unit
$officer = $conn->query("SELECT unit_id FROM users WHERE id = $officer_id")->fetch_assoc();
$unit_id = $officer['unit_id'];

// Handle New Announcement
if (isset($_POST['post_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    $stmt = $conn->prepare("INSERT INTO announcements (unit_id, title, content, posted_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $unit_id, $title, $content, $officer_id);
    if ($stmt->execute()) {
        $msg = "Announcement posted successfully!";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $ann_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM announcements WHERE id = $ann_id AND posted_by = $officer_id");
    unset($_GET['delete']);
    header("Location: officer_announcements.php");
}

// Fetch Announcements
$ann_query = $unit_id ? 
    "SELECT * FROM announcements WHERE unit_id = $unit_id ORDER BY date_posted DESC" : 
    "SELECT * FROM announcements ORDER BY date_posted DESC";
$anns = $conn->query($ann_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements Mgmt - NCC Officer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #0047AB; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background: #0047AB; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; }
        .btn:hover { background: gold; color: black; }
        
        .ann-list { margin-top: 15px; }
        .ann-row { padding: 15px; border-bottom: 1px solid #eee; }
        .ann-row:last-child { border-bottom: none; }
        .ann-row h4 { margin: 0; color: #333; }
        .ann-row p { margin: 5px 0; font-size: 14px; color: #555; }
        .ann-row span { font-size: 12px; color: #999; }
        .delete-link { color: #dc3545; font-size: 12px; text-decoration: none; margin-top: 5px; display: inline-block; }
        
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; margin-bottom: 15px; display: inline-block; grid-column: 1 / -1; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; grid-column: 1 / -1; }
    </style>
</head>
<body>

<header>
    <h1>Announcement Management</h1>
</header>

<div class="container">
    <a href="officer_dashboard.php" class="back-btn">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <!-- Post Form -->
    <div class="card">
        <h2>Post New Notice</h2>
        <form method="POST">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" placeholder="e.g. Next Parade Schedule" required>
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" rows="4" placeholder="Important details here..." required></textarea>
            </div>
            <button type="submit" name="post_announcement" class="btn">Post to Unit</button>
        </form>
    </div>

    <!-- Announcements List -->
    <div class="card">
        <h2>History</h2>
        <div class="ann-list">
            <?php if ($anns->num_rows > 0): ?>
                <?php while($row = $anns->fetch_assoc()): ?>
                    <div class="ann-row">
                        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        <span>Posted on: <?php echo date('d M Y, h:i A', strtotime($row['date_posted'])); ?></span><br>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Delete this announcement?')">Delete Post</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; color:#666;">No announcements posted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
