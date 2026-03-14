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

// Handle New Unit
if (isset($_POST['add_unit'])) {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $strength = (int)$_POST['strength'];
    
    $stmt = $conn->prepare("INSERT INTO units (name, type, strength) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $type, $strength);
    if ($stmt->execute()) {
        $msg = "Unit '$name' created successfully!";
    }
}

// Fetch Units
$units = $conn->query("SELECT u.*, (SELECT COUNT(*) FROM users WHERE unit_id = u.id AND role='Cadet') as cadet_count, (SELECT name FROM users WHERE unit_id = u.id AND role='NCC Officer' LIMIT 1) as officer_name FROM units u");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Management - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 25px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; color: #1a202c; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568; }
        input, select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; }
        .btn { background: #2d3748; color: white; padding: 12px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { background: #f7fafc; color: #718096; text-transform: uppercase; font-size: 12px; }
        .back { display: block; margin-bottom: 20px; text-decoration: none; color: #2b6cb0; font-weight: bold; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-army { background: #fefcbf; color: #744210; }
        .badge-navy { background: #bee3f8; color: #2c5282; }
        .badge-air { background: #fed7d7; color: #822727; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard_v2.php" class="back">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div style='background:#c6f6d5; color:#22543d; padding:15px; border-radius:6px; margin-bottom:20px;'>$msg</div>"; ?>

    <div class="grid">
        <div class="card">
            <h2>Add New Unit</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Unit Name</label>
                    <input type="text" name="name" placeholder="e.g. 1st Bengal Bn" required>
                </div>
                <div class="form-group">
                    <label>Unit Type</label>
                    <select name="type">
                        <option value="Army">Army</option>
                        <option value="Navy">Navy</option>
                        <option value="Air">Air</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Standard Strength</label>
                    <input type="number" name="strength" placeholder="e.g. 100" required>
                </div>
                <button type="submit" name="add_unit" class="btn">Create Unit</button>
            </form>
        </div>

        <div class="card">
            <h2>System Units</h2>
            <table>
                <thead>
                    <tr>
                        <th>Unit Name</th>
                        <th>Type</th>
                        <th>Cadets</th>
                        <th>Officer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($u = $units->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                            <td><span class="badge badge-<?php echo strtolower($u['type']); ?>"><?php echo $u['type']; ?></span></td>
                            <td><?php echo $u['cadet_count']; ?> / <?php echo $u['strength']; ?></td>
                            <td style="font-size: 13px;"><?php echo htmlspecialchars($u['officer_name'] ?? 'Unassigned'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
