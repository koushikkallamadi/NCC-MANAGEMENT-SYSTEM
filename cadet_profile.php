<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact = trim($_POST['contact']);
    $password = trim($_POST['password']);
    
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET contact = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $contact, $hashed, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET contact = ? WHERE id = ?");
        $stmt->bind_param("si", $contact, $user_id);
    }
    
    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
    }
}

// Fetch User Data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cadet = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - NCC</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 600px; margin: 30px auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .photo-section { text-align: center; margin-bottom: 30px; }
        .photo-section img { width: 150px; height: 150px; border-radius: 50%; border: 5px solid #0047AB; object-fit: cover; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 16px; background: #fafafa; }
        input[readonly] { background: #eee; cursor: not-allowed; }
        .btn { width: 100%; padding: 14px; background: #0047AB; color: white; border: none; border-radius: 6px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: gold; color: black; }
        .back-btn { color: #0047AB; text-decoration: none; font-weight: bold; display: block; margin-bottom: 20px; }
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

<header>
    <h1>Profile Settings</h1>
</header>

<div class="container">
    <a href="cadet_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <?php if (isset($success)) echo "<div class='success-msg'>$success</div>"; ?>

    <div class="photo-section">
        <img src="<?php echo htmlspecialchars($cadet['photo']); ?>" alt="Profile Photo">
        <h2><?php echo htmlspecialchars($cadet['name']); ?></h2>
        <span style="color: #666;"><?php echo htmlspecialchars($cadet['regiment_no']); ?></span>
    </div>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" value="<?php echo htmlspecialchars($cadet['name']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Regiment Number</label>
            <input type="text" value="<?php echo htmlspecialchars($cadet['regiment_no']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Branch & Year</label>
            <input type="text" value="<?php echo htmlspecialchars($cadet['branch'] . " - Year " . $cadet['year']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($cadet['contact']); ?>" required>
        </div>
        <div class="form-group">
            <label>Email ID</label>
            <input type="email" value="<?php echo htmlspecialchars($cadet['email']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>New Password (Leave blank to keep current)</label>
            <input type="password" name="password" placeholder="Enter new password">
        </div>
        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>

</body>
</html>
