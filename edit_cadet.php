<?php
// Database connection
require_once 'db.php';

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM cadets WHERE id=$id");
$cadet = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $rank = $_POST['rank'];

    $sql = "UPDATE cadets SET name='$name', phone='$phone', rank='$rank' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cadet updated successfully!'); window.location='cadet_management.php';</script>";
    } else {
        echo "Error updating cadet: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Cadet</title>
    <style>
 /* ---------- Page Styling ---------- */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    text-align: center;
}

/* ---------- Form Styling ---------- */
form {
    background: white;
    padding: 20px;
    width: 40%;
    margin: 30px auto;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    text-align: left;
}

label {
    font-weight: bold;
    display: block;
    margin: 10px 0 5px;
}

input {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* ---------- Responsive Design ---------- */
@media (max-width: 768px) {
    form {
        width: 80%;
    }
}


</style>
</head>
<body>
    <h2>Edit Cadet</h2>
    <form method="POST">
        <label>Name:</label> <input type="text" name="name" value="<?= $cadet['name'] ?>" required><br>
        <label>Phone:</label> <input type="text" name="phone" value="<?= $cadet['phone'] ?>" required><br>
        <label>Rank:</label> <input type="text" name="rank" value="<?= $cadet['rank'] ?>" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
