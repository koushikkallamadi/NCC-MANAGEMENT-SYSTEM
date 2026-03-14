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

// Handle Issuance
if (isset($_POST['issue_cert'])) {
    $cadet_id = (int)$_POST['cadet_id'];
    $type = trim($_POST['type']);
    $grade = trim($_POST['grade']);
    $issue_date = $_POST['issue_date'];
    
    // Logic for file path (placeholder)
    $file_path = "uploads/certs/" . time() . "_cert.pdf";
    
    $stmt = $conn->prepare("INSERT INTO certificates (user_id, type, grade, issue_date, issuing_officer_id, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $cadet_id, $type, $grade, $issue_date, $officer_id, $file_path);
    if ($stmt->execute()) {
        $msg = "Certificate issued successfully!";
    }
}

// Fetch Approved Cadets in Unit
$cadet_query = $unit_id ? 
    "SELECT id, name, regiment_no FROM users WHERE role = 'Cadet' AND status = 'Approved' AND unit_id = $unit_id" :
    "SELECT id, name, regiment_no FROM users WHERE role = 'Cadet' AND status = 'Approved'";
$cadets = $conn->query($cadet_query);

// Fetch Issued Certs
$issued_query = $unit_id ?
    "SELECT c.*, u.name as cadet_name, u.regiment_no FROM certificates c JOIN users u ON c.user_id = u.id WHERE u.unit_id = $unit_id ORDER BY c.issue_date DESC" :
    "SELECT c.*, u.name as cadet_name, u.regiment_no FROM certificates c JOIN users u ON c.user_id = u.id ORDER BY c.issue_date DESC";
$certs = $conn->query($issued_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Certificates - NCC Officer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #0047AB; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background: #0047AB; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; }
        .btn:hover { background: gold; color: black; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        th { background: #f8f9fa; }
        
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; margin-bottom: 15px; display: inline-block; grid-column: 1 / -1; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; grid-column: 1 / -1; }
    </style>
</head>
<body>

<header>
    <h1>Certificate Management</h1>
</header>

<div class="container">
    <a href="officer_dashboard.php" class="back-btn">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <!-- Issue Form -->
    <div class="card">
        <h2>Issue New Certificate</h2>
        <form method="POST">
            <div class="form-group">
                <label>Select Cadet</label>
                <select name="cadet_id" required>
                    <option value="">-- Choose Approved Cadet --</option>
                    <?php while($c = $cadets->fetch_assoc()): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name'] . " (" . $c['regiment_no'] . ")"); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Certificate Type</label>
                <select name="type">
                    <option value="A Certificate">A Certificate</option>
                    <option value="B Certificate">B Certificate</option>
                    <option value="C Certificate">C Certificate</option>
                    <option value="Participation Certificate">Participation Certificate</option>
                    <option value="Merit Certificate">Merit Certificate</option>
                </select>
            </div>
            <div class="form-group">
                <label>Grade / Remarks</label>
                <input type="text" name="grade" placeholder="e.g. Grade A, Outstanding" required>
            </div>
            <div class="form-group">
                <label>Issue Date</label>
                <input type="date" name="issue_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" name="issue_cert" class="btn">Issue Certificate</button>
        </form>
    </div>

    <!-- Issued List -->
    <div class="card">
        <h2>Issued Certificates</h2>
        <table>
            <thead>
                <tr>
                    <th>Cadet</th>
                    <th>Type</th>
                    <th>Grade</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $certs->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($row['cadet_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($row['regiment_no']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo htmlspecialchars($row['grade']); ?></td>
                        <td><?php echo date('d/m/y', strtotime($row['issue_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
