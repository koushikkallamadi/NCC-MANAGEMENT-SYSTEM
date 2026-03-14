<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to database
require_once 'db.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $user_id = $_SESSION['user_id'];

    // Update user role
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $user_id);
    if ($stmt->execute()) {
        if ($role == "Cadet") header("Location:NCC CHATGPT dup.html ");
        elseif ($role == "NCC Officer") header("Location: NCC OFFICER.html");
        else header("Location: admin_dashboard.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <link rel="stylesheet" href="style.css"> <!-- Linking external CSS file -->
    <style>/* General Page Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Form Container */
.form-container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
}

/* Form Title */
h2 {
    margin-bottom: 20px;
}

/* Dropdown Select */
select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
    <div class="form-container">
        <h2>Select Your Role</h2>
        <form method="POST" action="role_select.php">
            <select name="role" required>
                <option value="Cadet">Cadet</option>
                <option value="NCC Officer">NCC Officer</option>
                <option value="Admin">Admin</option>
            </select>
            <button type="submit">Proceed</button>
        </form>
    </div>
</body>
</html>
