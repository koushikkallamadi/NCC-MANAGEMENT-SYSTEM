<?php
// Database Connection
require_once 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch Attendance Data (Group by Cadet)
$sql_fetch = "
    SELECT 
        c.id AS cadet_id, 
        c.name AS cadet_name, 
        COUNT(a.id) AS total_classes,
        SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS attended_classes,
        (SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) / NULLIF(COUNT(a.id), 0)) * 100 AS attendance_percentage
    FROM cadets c
    LEFT JOIN attendance a ON c.id = a.cadet_id
    WHERE c.name LIKE '%$search%' OR c.id LIKE '%$search%'
    GROUP BY c.id
    ORDER BY c.id";

$result = $conn->query($sql_fetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadet Attendance - NCC Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        header {
            background-color: #0047AB;
            color: white;
            text-align: center;
            padding: 30px;
            margin-bottom: 30px;
        }
        header h1 {
            margin: 0;
            font-size: 36px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #0047AB;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        /* Search Bar */
        .search-container {
            margin-bottom: 30px;
            text-align: center;
        }
        .search-container input[type="text"] {
            width: 60%;
            padding: 12px 20px;
            border: 2px solid #0047AB;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }
        .search-container input[type="text"]:focus {
            box-shadow: 0 0 8px rgba(0, 71, 171, 0.4);
        }
        .search-container button {
            padding: 12px 25px;
            margin-left: 10px;
            background-color: #0047AB;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .search-container button:hover {
            background-color: gold;
            color: black;
        }

        /* Attendance Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 8px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #0047AB;
            color: white;
            font-size: 18px;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #f1f7ff;
        }
        .percentage {
            font-weight: bold;
        }
        .low-attendance {
            color: #ff4d4d;
        }

        /* Footer Styling */
        footer {
            background-color: #0047AB;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>CADET ATTENDANCE</h1>
    </header>

    <div class="container">
        <h2>📊 Cadet Attendance Records</h2>

        <div class="search-container">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by Cadet Name or ID..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Cadet ID</th>
                    <th>Cadet Name</th>
                    <th>Total Classes</th>
                    <th>Attended Classes</th>
                    <th>Attendance (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <?php
                        $attendance_percentage = is_null($row['attendance_percentage']) ? 0 : number_format($row['attendance_percentage'], 2);
                        $row_class = ($attendance_percentage < 75) ? 'low-attendance' : '';
                    ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo htmlspecialchars($row['cadet_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['cadet_name']); ?></td>
                        <td><?php echo $row['total_classes']; ?></td>
                        <td><?php echo $row['attended_classes']; ?></td>
                        <td class="percentage"><?php echo $attendance_percentage; ?>%</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>Content Owned by JNTUA NCC</p>
        <p>Developed and hosted by JNTUA NCC,<br>
           Ministry of Electronics & Information Technology, Government of India</p>
        <p>Last Updated: March 13, 2026</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
