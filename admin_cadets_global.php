<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Verify Admin
$user = $conn->query("SELECT role FROM users WHERE id = $user_id")->fetch_assoc();
if ($user['role'] !== 'Admin') exit("Unauthorized");

// Fetch Global Cadets
$cadets = $conn->query("SELECT u.*, un.name as unit_name FROM users u LEFT JOIN units un ON u.unit_id = un.id WHERE u.role = 'Cadet' ORDER BY u.status ASC, u.name ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Cadet List - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #1a202c; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { background: #f7fafc; color: #4a5568; font-size: 13px; }
        .back { display: block; margin-bottom: 20px; text-decoration: none; color: #2b6cb0; font-weight: bold; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .Pending { background: #fffaf0; color: #9c4221; }
        .Approved { background: #c6f6d5; color: #22543d; }
        .Deactivated { background: #fed7d7; color: #822727; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard_v2.php" class="back">← Back to Dashboard</a>
    <h1>Global Cadet Directory</h1>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Regiment No</th>
                <th>Unit</th>
                <th>Contact</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($c = $cadets->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($c['regiment_no']); ?></td>
                    <td><?php echo htmlspecialchars($c['unit_name'] ?? 'Unassigned'); ?></td>
                    <td><?php echo htmlspecialchars($c['contact']); ?></td>
                    <td><span class="status-badge <?php echo $c['status']; ?>"><?php echo $c['status']; ?></span></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
