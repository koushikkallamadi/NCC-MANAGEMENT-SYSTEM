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

// Handle New Officer
if (isset($_POST['add_officer'])) {
    $name = trim($_POST['name']);
    $regiment_no = trim($_POST['officer_id']); // Using regiment_no field for Officer ID
    $email = trim($_POST['email']);
    $unit_id = (int)$_POST['unit_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (name, regiment_no, email, unit_id, password, role, status) VALUES (?, ?, ?, ?, ?, 'NCC Officer', 'Approved')");
    $stmt->bind_param("sssis", $name, $regiment_no, $email, $unit_id, $password);
    if ($stmt->execute()) {
        $msg = "Officer added successfully!";
    }
}

// Fetch Officers
$officers = $conn->query("SELECT u.*, un.name as unit_name FROM users u LEFT JOIN units un ON u.unit_id = un.id WHERE u.role = 'NCC Officer'");
$units = $conn->query("SELECT * FROM units");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Mgmt - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #1a202c; }
        .form-group { margin-bottom: 12px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background: #2d3748; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; font-size: 14px; }
        .back { display: block; margin-bottom: 20px; text-decoration: none; color: #2b6cb0; }
    </style>
</head>
<body>

<div class="container" style="display: block; max-width: 1000px;">
    <a href="admin_dashboard_v2.php" class="back">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div style='color:green; margin-bottom:10px;'>$msg</div>"; ?>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px;">
        <div class="card">
            <h2>Add Officer</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Officer ID</label>
                    <input type="text" name="officer_id" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Assign Unit</label>
                    <select name="unit_id">
                        <option value="">-- No Unit --</option>
                        <?php while($u = $units->fetch_assoc()): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Default Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="add_officer" class="btn">Register Officer</button>
            </form>
        </div>

        <div class="card">
            <h2>Existing Officers</h2>
            <table>
                <thead>
                    <tr>
                        <th>Officer</th>
                        <th>ID</th>
                        <th>Unit</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($off = $officers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($off['name']); ?></td>
                            <td><?php echo htmlspecialchars($off['regiment_no']); ?></td>
                            <td><?php echo htmlspecialchars($off['unit_name'] ?? 'Unassigned'); ?></td>
                            <td><?php echo $off['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
