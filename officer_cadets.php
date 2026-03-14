<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Get Officer Unit
$officer = $conn->query("SELECT unit_id FROM users WHERE id = $user_id")->fetch_assoc();
$unit_id = $officer['unit_id'];

// Handle Approval/Deactivation
if (isset($_POST['action'])) {
    $target_id = (int)$_POST['target_id'];
    $status = $_POST['action'] == 'approve' ? 'Approved' : ($_POST['action'] == 'deactivate' ? 'Deactivated' : 'Pending');
    
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $target_id);
    if ($stmt->execute()) {
        $msg = "Cadet status updated to $status.";
    }
}

// Fetch Cadets
$pending_query = $unit_id ? 
    "SELECT * FROM users WHERE role = 'Cadet' AND status = 'Pending' AND unit_id = $unit_id" : 
    "SELECT * FROM users WHERE role = 'Cadet' AND status = 'Pending'";
$pending_cadets = $conn->query($pending_query);

$approved_query = $unit_id ? 
    "SELECT * FROM users WHERE role = 'Cadet' AND status = 'Approved' AND unit_id = $unit_id" : 
    "SELECT * FROM users WHERE role = 'Cadet' AND status = 'Approved'";
$approved_cadets = $conn->query($approved_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadet Management - NCC Officer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .section { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        h2 { color: #0047AB; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #555; }
        
        .btn { padding: 8px 15px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold; font-size: 13px; text-decoration: none; }
        .btn-approve { background: #28a745; color: white; }
        .btn-reject { background: #dc3545; color: white; }
        .btn-view { background: #0047AB; color: white; }
        .btn:hover { opacity: 0.8; }
        
        .cadet-photo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; margin-bottom: 15px; display: inline-block; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

<header>
    <h1>Cadet Management</h1>
</header>

<div class="container">
    <a href="officer_dashboard.php" class="back-btn">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <!-- Pending Approvals -->
    <div class="section">
        <h2>Pending Approvals</h2>
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Regiment No</th>
                    <th>Branch/Year</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($pending_cadets->num_rows > 0): ?>
                    <?php while($row = $pending_cadets->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($row['photo']); ?>" class="cadet-photo"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['regiment_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch']); ?> (Yr <?php echo $row['year']; ?>)</td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="target_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">No pending registrations.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Approved Cadets -->
    <div class="section">
        <h2>Approved Cadets</h2>
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Regiment No</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($approved_cadets->num_rows > 0): ?>
                    <?php while($row = $approved_cadets->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($row['photo']); ?>" class="cadet-photo"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['regiment_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            <td><span style="color: green; font-weight: bold;">Approved</span></td>
                            <td>
                                <a href="cadet_profile_view.php?id=<?php echo $row['id']; ?>" class="btn btn-view">View Profile</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="target_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="deactivate" class="btn btn-reject">Deactivate</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">No approved cadets found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
