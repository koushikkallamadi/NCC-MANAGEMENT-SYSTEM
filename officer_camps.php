<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Handle New Camp/Event
if (isset($_POST['add_item'])) {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $date = $_POST['date'];
    $location = trim($_POST['location']);
    $duration = trim($_POST['duration']);
    $max_cadets = (int)$_POST['max_cadets'];
    $details = trim($_POST['details']);
    
    $stmt = $conn->prepare("INSERT INTO camps_events (name, type, date, location, duration, max_cadets, details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $type, $date, $location, $duration, $max_cadets, $details);
    if ($stmt->execute()) {
        $msg = "New $type added successfully!";
    }
}

// Fetch All Items
$items = $conn->query("SELECT * FROM camps_events ORDER BY date DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camp & Event Mgmt - NCC Officer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #0047AB; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background: #0047AB; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; transition: 0.3s; }
        .btn:hover { background: gold; color: black; }
        
        .item-list { margin-top: 15px; }
        .item-row { padding: 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .item-row:last-child { border-bottom: none; }
        .item-info h4 { margin: 0; color: #333; }
        .item-info span { font-size: 13px; color: #666; }
        .badge { padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .badge-camp { background: #e3f2fd; color: #0d47a1; }
        .badge-event { background: #f3e5f5; color: #4a148c; }
        
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; margin-bottom: 15px; display: inline-block; grid-column: 1 / -1; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; grid-column: 1 / -1; }
    </style>
</head>
<body>

<header>
    <h1>Camp & Event Management</h1>
</header>

<div class="container">
    <a href="officer_dashboard.php" class="back-btn">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <!-- Add New Item -->
    <div class="card">
        <h2>Add Camp/Event</h2>
        <form method="POST">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" placeholder="e.g. Annual Training Camp" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="Camp">Camp</option>
                    <option value="Event">Event</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" required>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" placeholder="e.g. City Grounds" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" placeholder="e.g. 10 Days" required>
            </div>
            <div class="form-group">
                <label>Max Cadets</label>
                <input type="number" name="max_cadets" placeholder="e.g. 50" required>
            </div>
            <div class="form-group">
                <label>Details</label>
                <textarea name="details" rows="3" placeholder="What to bring, reporting time, etc."></textarea>
            </div>
            <button type="submit" name="add_item" class="btn">Post Camp/Event</button>
        </form>
    </div>

    <!-- Item List -->
    <div class="card">
        <h2>Existing Items</h2>
        <div class="item-list">
            <?php if ($items->num_rows > 0): ?>
                <?php while($row = $items->fetch_assoc()): ?>
                    <div class="item-row">
                        <div class="item-info">
                            <span class="badge <?php echo ($row['type']=='Camp') ? 'badge-camp' : 'badge-event'; ?>"><?php echo $row['type']; ?></span>
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                            <span>Date: <?php echo date('d M Y', strtotime($row['date'])); ?> | Loc: <?php echo htmlspecialchars($row['location']); ?></span>
                        </div>
                        <div>
                            <a href="officer_view_enrollments.php?id=<?php echo $row['id']; ?>" style="color:#0047AB; font-size:13px; font-weight:bold;">View Enrollments</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; color:#666;">No items posted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
