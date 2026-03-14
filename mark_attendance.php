<?php
// Start session to get username
session_start();

// Database connection
require_once 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $marked_by = $_SESSION['username'] ?? 'Unknown Officer'; // Default if session not set

    foreach ($_POST['attendance'] as $cadet_id => $status) {
        $sql = "INSERT INTO attendance (cadet_id, date, status, marked_by) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                marked_by = VALUES(marked_by), 
                updated_at = CURRENT_TIMESTAMP";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isss", $cadet_id, $date, $status, $marked_by);
            $stmt->execute();
        }
    }

    echo "<script>alert('Attendance marked successfully!'); window.location='mark_attendance.php';</script>";
}

// Fetch cadets sorted by ID (in order)
$cadets = $conn->query("SELECT id, name, year FROM cadets ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .submit-btn {
            background: green;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 15px;
        }
        .submit-btn:hover {
            background: darkgreen;
        }
        input[type="date"] {
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h2>Mark Attendance</h2>
    <form method="POST">
        <label><b>Select Date:</b></label>
        <input type="date" name="date" required>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Year</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $cadets->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['year'] ?></td>
                <td>
                    <select name="attendance[<?= $row['id'] ?>]">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit" class="submit-btn">Submit Attendance</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
