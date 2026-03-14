<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cadets - NCC Management System</title>
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
    
    .content-wrapper {
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .intro {
      max-width: 800px;
      margin: 0 auto 30px;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .intro p {
      font-size: 18px;
      color: #333;
    }

    /* Registration Form Styles */
    .registration-form {
      max-width: 600px;
      margin: 0 auto 40px;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .registration-form h2 {
      color: #0047AB;
      margin-bottom: 20px;
      text-align: center;
      font-size: 28px;
    }
    .registration-form label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }
    .registration-form input, 
    .registration-form select {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
    }
    .registration-form button {
      background-color: #0047AB;
      color: #fff;
      padding: 12px;
      border: none;
      width: 100%;
      cursor: pointer;
      border-radius: 5px;
      font-size: 18px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .registration-form button:hover {
      background-color: gold;
      color: black;
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

    /* Modal styles */
    .modal {
      display: none; 
      position: fixed; 
      z-index: 1000; 
      left: 0;
      top: 0;
      width: 100%; 
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      overflow: auto; 
    }
    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover {
      color: black;
    }
  </style>
</head>
<body>
  <header>
    <h1>NCC CADETS</h1>
  </header>

  <div class="content-wrapper">
    <!-- Introduction Section -->
    <section class="intro">
      <p>Welcome to the Cadets page of the NCC Management System. Here, you can register as a cadet and stay updated on upcoming training and events. Join us in building leadership, discipline, and camaraderie.</p>
    </section>
    
    <!-- Registration Form -->
    <section class="registration-form">
      <h2>Register as a Cadet</h2>
      <form id="cadetForm">
        <label for="cadetName">Cadet Name:</label>
        <input type="text" id="cadetName" name="cadetName" placeholder="Enter your full name" required>
        
        <label for="cadetRank">Rank:</label>
        <select id="cadetRank" name="cadetRank" required>
          <option value="">Select Rank</option>
          <option value="cadet">Cadet</option>
          <option value="sergeant">Sergeant</option>
          <option value="lieutenant">Lieutenant</option>
        </select>

        <label for="cadetEmail">Email Address:</label>
        <input type="email" id="cadetEmail" name="cadetEmail" placeholder="Enter your email" required>
        
        <label for="cadetPhone">Phone Number:</label>
        <input type="tel" id="cadetPhone" name="cadetPhone" placeholder="Enter your phone number" required>

        <label for="cadetPhoto">Upload Photo:</label>
        <input type="file" id="cadetPhoto" name="cadetPhoto" accept="image/*" required>

        <button type="submit">Register Now</button>
      </form>
    </section>
  </div>

  <!-- Success Modal -->
  <div id="successModal" class="modal">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <h2 style="color: #28a745;">Registration Successful!</h2>
      <p>Your cadet registration has been submitted successfully.</p>
    </div>
  </div>

  <footer>
      <p>Content Owned by JNTUA NCC</p>
      <p>Developed and hosted by JNTUA NCC,<br>
         Ministry of Electronics & Information Technology, Government of India</p>
      <p>Last Updated: March 13, 2026</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Handle form submission
      document.getElementById("cadetForm").addEventListener("submit", function(event) {
        event.preventDefault();
        
        // In a real application, you would send this data to a server
        // For now, we just show a success message
        showSuccessModal();
        document.getElementById("cadetForm").reset();
      });

      function showSuccessModal() {
        document.getElementById("successModal").style.display = "block";
      }

      document.getElementById("closeModal").addEventListener("click", function() {
        document.getElementById("successModal").style.display = "none";
      });

      window.onclick = function(event) {
        if (event.target == document.getElementById("successModal")) {
          document.getElementById("successModal").style.display = "none";
        }
      }
    });
  </script>
</body>
</html>
