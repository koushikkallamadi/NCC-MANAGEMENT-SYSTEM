<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$item_id = (int)$_GET['id'];

// Fetch Item Details
$item = $conn->query("SELECT * FROM camps_events WHERE id = $item_id")->fetch_assoc();

// Fetch Enrolled Cadets
$cadets = $conn->query("SELECT e.*, u.name, u.regiment_no, u.photo, u.contact 
                        FROM enrollments e 
                        JOIN users u ON e.user_id = u.id 
                        WHERE e.item_id = $item_id");

// Handle Status Update
if (isset($_POST['update_status'])) {
    $enroll_id = (int)$_POST['enroll_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE enrollments SET status = '$status' WHERE id = $enroll_id");
    header("Location: officer_view_enrollments.php?id=$item_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Enrollments - NCC Officer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1, h2 { color: #0047AB; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .cadet-photo { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; vertical-align: middle; margin-right: 10px; }
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; display: block; margin-bottom: 20px; }
        select { padding: 5px; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<header>
    <h1>Enrollments for <?php echo htmlspecialchars($item['name']); ?></h1>
</header>

<div class="container">
    <a href="officer_camps.php" class="back-btn">← Back to Camps</a>
    
    <h2>Enrolled Cadets</h2>
    <table>
        <thead>
            <tr>
                <th>Cadet</th>
                <th>Regiment No</th>
                <th>Contact</th>
                <th>Current Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($cadets->num_rows > 0): ?>
                <?php while($row = $cadets->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['photo']); ?>" class="cadet-photo">
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($row['regiment_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td><strong><?php echo $row['status']; ?></strong></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="enroll_id" value="<?php echo $row['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Applied" <?php echo ($row['status']=='Applied')?'selected':''; ?>>Applied</option>
                                    <option value="Selected" <?php echo ($row['status']=='Selected')?'selected':''; ?>>Selected</option>
                                    <option value="Enrolled" <?php echo ($row['status']=='Enrolled')?'selected':''; ?>>Enrolled</option>
                                </select>
                                <input type="hidden" name="update_status">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No cadets have applied for this yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
