<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Handle Enrollment
if (isset($_POST['enroll'])) {
    $item_id = (int)$_POST['item_id'];
    // Check if already enrolled
    $check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND item_id = ?");
    $check->bind_param("ii", $user_id, $item_id);
    $check->execute();
    if ($check->get_result()->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO enrollments (user_id, item_id, status) VALUES (?, ?, 'Applied')");
        $stmt->bind_param("ii", $user_id, $item_id);
        if ($stmt->execute()) {
            $msg = "Successfully applied for the camp/event!";
        }
    } else {
        $msg = "You are already enrolled/applied for this.";
    }
}

// Fetch Upcoming Camps & Events
$items = $conn->query("SELECT ce.*, e.status as enrollment_status 
                      FROM camps_events ce 
                      LEFT JOIN enrollments e ON ce.id = e.item_id AND e.user_id = $user_id 
                      WHERE ce.status = 'Upcoming' ORDER BY ce.date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camps & Events - NCC</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .item-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; border-top: 5px solid #0047AB; }
        .item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .item-header h2 { margin: 0; color: #0047AB; }
        .type-badge { background: #eef2f7; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; color: #666; font-size: 15px; }
        .btn { background: #0047AB; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 15px; }
        .btn:hover { background: gold; color: black; }
        .btn-disabled { background: #ccc; cursor: not-allowed; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; display: block; margin-bottom: 20px; }
    </style>
</head>
<body>

<header>
    <h1>Camps & Upcoming Events</h1>
</header>

<div class="container">
    <a href="cadet_dashboard.php" class="back-btn">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <?php if ($items && $items->num_rows > 0): ?>
        <?php while($row = $items->fetch_assoc()): ?>
            <div class="item-card">
                <div class="item-header">
                    <div>
                        <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                        <span class="type-badge"><?php echo $row['type']; ?></span>
                    </div>
                    <div style="text-align: right;">
                        <strong>Date:</strong> <?php echo date('d M Y', strtotime($row['date'])); ?><br>
                        <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?>
                    </div>
                </div>
                <div class="details">
                    <div><strong>Duration:</strong> <?php echo htmlspecialchars($row['duration']); ?></div>
                    <div><strong>Max Seats:</strong> <?php echo $row['max_cadets']; ?></div>
                </div>
                <p><?php echo nl2br(htmlspecialchars($row['details'])); ?></p>
                
                <?php if ($row['enrollment_status']): ?>
                    <button class="btn btn-disabled">Status: <?php echo $row['enrollment_status']; ?></button>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="enroll" class="btn">Enroll Now</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; color: #666;">No upcoming camps or events at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
