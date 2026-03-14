<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch Issued Certificates
$stmt = $conn->prepare("SELECT c.*, u.name as officer_name FROM certificates c 
                        JOIN users u ON c.issuing_officer_id = u.id 
                        WHERE c.user_id = ? ORDER BY c.issue_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certs = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates - NCC</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        .cert-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 25px; display: flex; align-items: center; border-left: 8px solid gold; }
        .cert-icon { font-size: 50px; margin-right: 25px; color: #0047AB; }
        .cert-info { flex-grow: 1; }
        .cert-info h2 { margin: 0 0 5px; color: #0047AB; font-size: 22px; }
        .cert-info p { margin: 3px 0; color: #555; font-size: 15px; }
        .download-btn { background: #0047AB; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        .download-btn:hover { background: gold; color: black; }
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; display: block; margin-bottom: 20px; }
        .empty-state { text-align: center; color: #666; margin-top: 50px; }
    </style>
</head>
<body>

<header>
    <h1>My NCC Certificates</h1>
</header>

<div class="container">
    <a href="cadet_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <?php if ($certs && $certs->num_rows > 0): ?>
        <?php while($row = $certs->fetch_assoc()): ?>
            <div class="cert-card">
                <div class="cert-icon">📜</div>
                <div class="cert-info">
                    <h2><?php echo htmlspecialchars($row['type']); ?></h2>
                    <p><strong>Grade/Result:</strong> <?php echo htmlspecialchars($row['grade']); ?></p>
                    <p><strong>Issued on:</strong> <?php echo date('d M Y', strtotime($row['issue_date'])); ?></p>
                    <p><strong>Issuing Officer:</strong> <?php echo htmlspecialchars($row['officer_name']); ?></p>
                </div>
                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="download-btn">Download PDF</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <div style="font-size: 60px; margin-bottom: 20px;">🎖️</div>
            <h3>No certificates issued yet.</h3>
            <p>Your certificates will appear here once they are verified and issued by your NCC Officer.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
