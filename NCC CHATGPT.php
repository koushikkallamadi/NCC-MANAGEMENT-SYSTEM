<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCC MANAGEMENT SYSTEM</title>
    <style>
        body {
            font-family: Arial,sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        /* Navigation Bar Styling */
        nav {
            background-color: #0047AB;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            font-size: 18px;
            font-weight: bold;
            display: inline-block;
        }
        nav a:hover {
            background-color: gold;
            color: black;
            border-radius: 5px;
        }

        #h11 {
            color: white;
            background-color: #0047AB;
            font-size:40px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            margin: 0;
        }
footer {
      background-color: #0047AB;
      color: white;
      text-align: center;
      padding: 15px;
      font-size: 14px;
    }
    footer p {
      margin: 5px 0;
    }


    /* Aim Section styling */
 .aim-section {
    position: relative;  /* Position relative to its normal location */
    max-width: 600px;
    margin: 20px 20px 20px 20px;   /* Adjust top margin to position vertically */
    padding: 15px;
    background-color: #f4f4f4;
    border: 1px solid #ddd;
    text-align: left;
    transition: background-color 0.3s ease;
  }
    .aim-section:hover {
      background-color: #e9e9e9;
    }
    .aim-section h2 {
      color: #0047AB;
      margin-bottom: 10px;
    }
    .aim-section p {
      font-size: 16px;
      line-height: 1.5;
    }


/* PLEDGESection styling */
 .PLEDGE {
    position: relative;  /* Position relative to its normal location */
    max-width: 600px;
    margin: 20px 20px 20px 20px;   /* Adjust top margin to position vertically */
    padding: 15px;
    background-color: #f4f4f4;
    border: 1px solid #ddd;
    text-align: left;
    transition: background-color 0.3s ease;
  }
    .aim-section:hover {
      background-color: #e9e9e9;
    }
    .aim-section h2 {
      color: #0047AB;
      margin-bottom: 10px;
    }
    .aim-section p {
      font-size: 16px;
      line-height: 1.5;
    }




    .container {
    display: flex;
    gap: 40px;
    justify-content: center;
    flex-wrap: wrap;
    padding: 40px;
}

.round-box {
    text-align: center;
    width: 100px;
    height: 150px;
    text-decoration: none;
    font-family: Arial, sans-serif;
    color: #333;
    cursor: pointer;
}

.circle-image {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #333;
    margin: 0 auto;
    transition: transform 0.3s ease;
}

.circle-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.circle-image:hover {
    transform: scale(1.1);
}

.round-box p {
    font-size: 19px;
    font-weight: 600;
    margin-top: 8px;
}



.details {
    display: none; /* Hidden by default */
    text-align: center;
    margin-top: 20px;
}

.details img {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
}





    </style>





  
</head>
<body>
<img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2020/01/2020010996.png" alt="NCC Logo" class="logo">

<style>
    .logo {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 100px;
        height: 130px;
    }
</style>
<!-- First Image (Top Right) -->
<img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2020/01/2020010935.png" 
     alt="First Image" 
     class="top-right-image">

<!-- Second Image (to the left of the first image) -->
<img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2021/12/2021122727.png" 
     alt="Second Image" 
     class="second-top-right-image">

<style>
  .top-right-image {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 90px; /* adjust as needed */
    height: auto;
  }
  
  .second-top-right-image {
    position: absolute;
    top: 35px;
    right: 120px; /* adjust spacing between images */
    width: 160px; /* adjust as needed */
    height: auto;
  }
</style>


 <h1 id="h11">NCC MANAGEMENT SYSTEM</h1>

    <nav>
    <a href="NCC CHATGPT.php">HOME</a>
      <a href="#" onclick="checkLogin('cadets.php')">Cadets</a>
<a href="#" onclick="checkLogin('events.php')">Events</a>
<a href="gallery page.html">Gallery</a>
<a href="contact page.html">Contact</a>
<a href="#" onclick="checkLogin('alumni.php')">Alumni</a>

    </nav>
<div class="slider">
    <div class="slides">
        <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2021/12/2021122414.jpeg" alt="Image 1">
        <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2022/02/2022020112.jpg" alt="Image 2">
        <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2022/07/2022072142.jpg" alt="Image 3">
        <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2022/02/2022020174.jpg" alt="Image 4">
    </div>
</div>

<style>
  .slider {
    max-width: 100%;
    height: 500px;
    overflow: hidden;
    margin: auto;
}

    .slides {
        display: flex;
        width: 200%;
        height:65%;
        animation: slideAnimation 24s infinite;
    }
    .slides img {
        width: 50%;
        height: 180%;
    }@keyframes slideAnimation {
    /* First Image */
    0%   { transform: translateX(0%); }
    20%  { transform: translateX(0%); }
    25%  { transform: translateX(0%); }
    
    /* Second Image */
    25%  { transform: translateX(-50%); }
    45%  { transform: translateX(-50%); }
    50%  { transform: translateX(-50%); }
    
    /* Third Image */
    50%  { transform: translateX(-100%); }
    70%  { transform: translateX(-100%); }
    75%  { transform: translateX(-100%); }
    
    /* Fourth Image */
    75%  { transform: translateX(-150%); }
    95%  { transform: translateX(-150%); }
    100% { transform: translateX(0%); }
  }
</style>

 <!-- Aim of NCC Section -->
      <section class="aim-section">
        <h2>AIM OF NCC</h2>
        <p>
          The ‘Aims’ of the NCC laid out in 1988 have stood the test of time and continue to meet the requirements expected of it in the current socio–economic scenario of the country. The NCC aims at developing character, comradeship, discipline, a secular outlook, and the spirit.
        </p>
      </section>
    </div>


<!-- PLEDGE -->
      <section class="PLEDGE">
        <h2>PLEDGE</h2>
        <p>
          We the cadets of the National Cadet Corps,
do solemnly pledge that we shall always uphold the unity of India.
We resolve to be disciplined and responsible citizens of our nation.
We shall undertake positive community service in the spirit of selflessness
and concern for our fellow beings.
        </p>
      </section>
    </div>


    <div class="container">
    <a href="ncc_training.html" class="round-box">
        <div class="circle-image">
            <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2021/09/2021091624.png" alt="NCC Training">
        </div>
        <p>Training</p>
    </a>

    <a href="ncc_ranks.html" class="round-box">
        <div class="circle-image">
            <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2021/09/2021091624.png" alt="NCC Ranks">
        </div>
        <p>Ranks</p>
    </a>

    <a href="ncc_certificates.html" class="round-box">
        <div class="circle-image">
            <img src="https://cdnbbsr.s3waas.gov.in/s307811dc6c422334ce36a09ff5cd6fe71/uploads/2021/09/2021091624.png" alt="NCC Certificates">
        </div>
        <p>Certificates</p>
    </a>
</div>


<!-- Details section, hidden initially -->
<div id="details" class="details">
    <img id="details-img" src="" alt="Detail Image">
    <p id="details-text"></p>
</div>


<footer>
      <p>Content Owned by JNTUA NCC</p>
      <p>Developed and hosted by JNTUA NCC,<br>
         Ministry of Electronics & Information Technology, Government of India</p>
      <p>Last Updated: Feb 20, 2025</p>
    </footer>
    
    <?php 
 // Make sure session is started
?>
<script>
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    
    function checkLogin(page) {
        if (!isLoggedIn) {
            window.location.href = "signup.php"; // Redirect to signup/login page
        } else {
            window.location.href = page; // Redirect to the requested page
        }
    }
</script>

</body>
</html>
