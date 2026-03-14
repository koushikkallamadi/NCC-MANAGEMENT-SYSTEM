<?php
// Database Connection
require_once 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM events WHERE event_name LIKE ? OR event_date LIKE ? ORDER BY event_date DESC";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$search_param = "%$search%";
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - NCC Management System</title>
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

        /* Events Table */
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
        .no-events {
            text-align: center;
            padding: 30px;
            font-size: 18px;
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
        <h1>NCC EVENTS</h1>
    </header>

    <div class="container">
        <h2>📅 NCC Events</h2>
        <div class="search-container">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by event name or date..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Date</th>
                    <th>Event Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$i}</td>
                            <td>" . htmlspecialchars($row['event_date']) . "</td>
                            <td>" . htmlspecialchars($row['event_name']) . "</td>
                        </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='no-events'>❌ No events found matching your search.</td></tr>";
                }
                ?>
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
