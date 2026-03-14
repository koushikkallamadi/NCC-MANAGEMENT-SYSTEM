<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

$officer = $conn->query("SELECT unit_id FROM users WHERE id = $user_id")->fetch_assoc();
$unit_id = $officer['unit_id'];

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Handle Saving Attendance
if (isset($_POST['save_attendance'])) {
    foreach ($_POST['status'] as $cadet_id => $status) {
        $cadet_id = (int)$cadet_id;
        // Check if record exists
        $check = $conn->prepare("SELECT id FROM attendance WHERE user_id = ? AND date = ?");
        $check->bind_param("is", $cadet_id, $date);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE user_id = ? AND date = ?");
            $stmt->bind_param("sis", $status, $cadet_id, $date);
        } else {
            $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, status) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $cadet_id, $date, $status);
        }
        $stmt->execute();
    }
    $msg = "Attendance saved for $date!";
}

// Fetch Cadets and their attendance for the day
$cadet_query = $unit_id ? 
    "SELECT u.id, u.name, u.regiment_no, u.photo, a.status FROM users u 
     LEFT JOIN attendance a ON u.id = a.user_id AND a.date = '$date'
     WHERE u.role = 'Cadet' AND u.status = 'Approved' AND u.unit_id = $unit_id" :
    "SELECT u.id, u.name, u.regiment_no, u.photo, a.status FROM users u 
     LEFT JOIN attendance a ON u.id = a.user_id AND a.date = '$date'
     WHERE u.role = 'Cadet' AND u.status = 'Approved'";
$cadets = $conn->query($cadet_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance - NCC Officer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        .controls { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #0047AB; color: white; }
        
        .photo-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; vertical-align: middle; margin-right: 10px; }
        .btn-save { background: #28a745; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px; float: right; }
        .btn-save:hover { background: #218838; }
        
        .radio-group label { margin-right: 15px; cursor: pointer; font-weight: bold; }
        .radio-present { color: #28a745; }
        .radio-absent { color: #dc3545; }
        
        input[type="date"] { padding: 8px; border-radius: 4px; border: 1px solid #ccc; }
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; margin-bottom: 15px; display: inline-block; }
        .msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h1>Mark Attendance</h1>
</header>

<div class="container">
    <a href="officer_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <div class="controls">
        <form method="GET">
            <label>Select Date: </label>
            <input type="date" name="date" value="<?php echo $date; ?>" onchange="this.form.submit()">
        </form>
        <span style="color: #666; font-weight: bold;">Date: <?php echo date('d M Y', strtotime($date)); ?></span>
    </div>

    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Cadet Details</th>
                    <th>Attendance Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $cadets->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['photo']); ?>" class="photo-small">
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                            <small style="color:#666; margin-left:45px;"><?php echo htmlspecialchars($row['regiment_no']); ?></small>
                        </td>
                        <td class="radio-group">
                            <label class="radio-present">
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Present" <?php echo ($row['status'] == 'Present') ? 'checked' : ''; ?> required>
                                Present
                            </label>
                            <label class="radio-absent">
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Absent" <?php echo ($row['status'] == 'Absent') ? 'checked' : ''; ?>>
                                Absent
                            </label>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="save_attendance" class="btn-save">Save Attendance</button>
    </form>
</div>

</body>
</html>
