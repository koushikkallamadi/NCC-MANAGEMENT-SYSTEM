<?php
// Database connection
require_once 'db.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cadets data
$sql = "SELECT * FROM cadets ORDER BY year, name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadet Management</title>
    <style>
    /* -------------- Global Styling -------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
    padding: 20px;
}

/* -------------- Table Styling -------------- */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
th {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

/* Hover Effect */
tr:hover {
    background-color: #f1f1f1;
}

/* -------------- Buttons -------------- */
button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s ease-in-out;
}

/* Edit Button */
.edit-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn:hover {
    background-color: #218838;
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* -------------- Form Styling (Login & Signup) -------------- */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
    margin: auto;
}

/* Form Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* -------------- Links -------------- */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* -------------- Navigation Bar (If needed) -------------- */
.navbar {
    background-color: #007bff;
    padding: 15px;
    text-align: center;
}

.navbar a {
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
}

.navbar a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* -------------- Responsive Design -------------- */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }
    
    .form-container {
        width: 90%;
    }
}
/* -------------- Global Styling -------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
    padding: 20px;
}

/* -------------- Table Styling -------------- */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
th {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

/* Hover Effect */
tr:hover {
    background-color: #f1f1f1;
}

/* -------------- Buttons -------------- */
button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s ease-in-out;
}

/* Edit Button */
.edit-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn:hover {
    background-color: #218838;
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* -------------- Form Styling (Login & Signup) -------------- */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
    margin: auto;
}

/* Form Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* -------------- Links -------------- */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* -------------- Navigation Bar (If needed) -------------- */
.navbar {
    background-color: #007bff;
    padding: 15px;
    text-align: center;
}

.navbar a {
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
}

.navbar a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* -------------- Responsive Design -------------- */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }
    
    .form-container {
        width: 90%;
    }
}
/* -------------- Global Styling -------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
    padding: 20px;
}

/* -------------- Table Styling -------------- */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
th {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

/* Hover Effect */
tr:hover {
    background-color: #f1f1f1;
}

/* -------------- Buttons -------------- */
button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s ease-in-out;
}

/* Edit Button */
.edit-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn:hover {
    background-color: #218838;
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* -------------- Form Styling (Login & Signup) -------------- */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
    margin: auto;
}

/* Form Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* -------------- Links -------------- */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* -------------- Navigation Bar (If needed) -------------- */
.navbar {
    background-color: #007bff;
    padding: 15px;
    text-align: center;
}

.navbar a {
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
}

.navbar a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* -------------- Responsive Design -------------- */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }
    
    .form-container {
        width: 90%;
    }
}
/* -------------- Global Styling -------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
    padding: 20px;
}

/* -------------- Table Styling -------------- */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
th {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

/* Hover Effect */
tr:hover {
    background-color: #f1f1f1;
}

/* -------------- Buttons -------------- */
button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s ease-in-out;
}

/* Edit Button */
.edit-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn:hover {
    background-color: #218838;
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* -------------- Form Styling (Login & Signup) -------------- */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
    margin: auto;
}

/* Form Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* -------------- Links -------------- */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* -------------- Navigation Bar (If needed) -------------- */
.navbar {
    background-color: #007bff;
    padding: 15px;
    text-align: center;
}

.navbar a {
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
}

.navbar a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* -------------- Responsive Design -------------- */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }
    
    .form-container {
        width: 90%;
    }
}
/* -------------- Global Styling -------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
    padding: 20px;
}

/* -------------- Table Styling -------------- */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
th {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

/* Hover Effect */
tr:hover {
    background-color: #f1f1f1;
}

/* -------------- Buttons -------------- */
button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s ease-in-out;
}

/* Edit Button */
.edit-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn:hover {
    background-color: #218838;
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* -------------- Form Styling (Login & Signup) -------------- */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
    margin: auto;
}

/* Form Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* -------------- Links -------------- */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* -------------- Navigation Bar (If needed) -------------- */
.navbar {
    background-color: #007bff;
    padding: 15px;
    text-align: center;
}

.navbar a {
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
}

.navbar a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* -------------- Responsive Design -------------- */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }
    
    .form-container {
        width: 90%;
    }
}
/* -------------- Global Styling -------------- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
    padding: 20px;
}

/* -------------- Table Styling -------------- */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
th {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

/* Hover Effect */
tr:hover {
    background-color: #f1f1f1;
}

/* -------------- Buttons -------------- */
button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s ease-in-out;
}

/* Edit Button */
.edit-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn:hover {
    background-color: #218838;
}

/* Delete Button */
.delete-btn {
    background-color: #dc3545;
    color: white;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* -------------- Form Styling (Login & Signup) -------------- */
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
    margin: auto;
}

/* Form Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
button {
    background-color: #007bff;
    color: white;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

/* -------------- Links -------------- */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* -------------- Navigation Bar (If needed) -------------- */
.navbar {
    background-color: #007bff;
    padding: 15px;
    text-align: center;
}

.navbar a {
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
}

.navbar a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

/* -------------- Responsive Design -------------- */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }
    
    .form-container {
        width: 90%;
    }
}


    </style>
</head>
<body>
    <h2>Cadet Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Year</th>
            <th>Regiment No</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Rank</th>
            <th>Join Date</th>
            <th>Actions</th>
        </tr>
        <?php 
$sn = 1; // Start Serial Number

while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $sn++ ?></td> <!-- This will generate serial numbers automatically -->
    <td><?= $row['name'] ?></td>
    <td><?= $row['year'] ?></td>
    <td><?= $row['regiment_no'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td><?= $row['rank'] ?></td>
    <td><?= $row['join_date'] ?></td>
    <td>
        <a href="edit_cadet.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
        <a href="delete_cadet.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>

    </table>
</body>
</html>

<?php $conn->close(); ?>
