<?php
// Database connection
require_once 'db.php';

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = $_GET['id'];
$sql = "DELETE FROM cadets WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Cadet deleted successfully!'); window.location='cadet_management.php';</script>";
} else {
    echo "Error deleting cadet: " . $conn->error;
}

$conn->close();
?>
