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
  <title>Events - NCC Management System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 20px;
    }
    h1 {
      text-align: center;
      color: #0047AB;
      margin-bottom: 20px;
    }
    .event-table {
      width: 100%;
      max-width: 1000px;
      margin: 0 auto;
      border-collapse: collapse;
      background-color: #fff;
    }
    .event-table th, .event-table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 14px;
    }
    .event-table th {
      background-color: #0047AB;
      color: #fff;
    }
    .event-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    /* Responsive table styling */
    @media screen and (max-width: 768px) {
      .event-table, .event-table thead, .event-table tbody, .event-table th, .event-table td, .event-table tr {
        display: block;
      }
      .event-table thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }
      .event-table tr {
        margin: 0 0 1rem 0;
      }
      .event-table td {
        border: none;
        position: relative;
        padding-left: 50%;
        text-align: right;
      }
      .event-table td:before {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: bold;
        text-align: left;
      }
      /* Label the data */
      .event-table td:nth-of-type(1):before { content: "S.No"; }
      .event-table td:nth-of-type(2):before { content: "Date"; }
      .event-table td:nth-of-type(3):before { content: "Event Name"; }
    }
  </style>
</head>
<body>
  <h1>Events</h1>
  <table class="event-table">
    <thead>
      <tr>
        <th>S.No</th>
        <th>Date</th>
        <th>Event Name</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>21/06/2016</td>
        <td>International Yoga Day.</td>
      </tr>
      <tr>
        <td>2</td>
        <td>13/07/2016</td>
        <td>Meeting To Discuss Events For The New Academic Year.</td>
      </tr>
      <tr>
        <td>3</td>
        <td>14/07/2016 To 15/08/2016</td>
        <td>Yogathan-A Daily Training Program.</td>
      </tr>
      <tr>
        <td>4</td>
        <td>15/07/2016</td>
        <td>NSS Audio Songs CD Release.</td>
      </tr>
      <tr>
        <td>5</td>
        <td>25/07/2016</td>
        <td>Marking For Plantation At Examination Block & Meeting About Further Activities.</td>
      </tr>
      <tr>
        <td>6</td>
        <td>29/07/2016</td>
        <td>Vanam-Manam Mass Plantation & Meeting About Special Camp.</td>
      </tr>
      <tr>
        <td>7</td>
        <td>01/08/2016 To 06/08/2016</td>
        <td>Village Special Camp At GangulaKunta.</td>
      </tr>
      <tr>
        <td>8</td>
        <td>12/08/2016 To 15/08/2016</td>
        <td>Volunteers Serving At Bachupalli During Krishna Pushkara’s.</td>
      </tr>
      <tr>
        <td>9</td>
        <td>29/08/2016</td>
        <td>Selections For Rain Guns Training Program For Farmers.</td>
      </tr>
      <tr>
        <td>10</td>
        <td>30/08/2016 To 03/09/2016</td>
        <td>Volunteers Trained Farmers About Rain Guns At Allotted Villages.</td>
      </tr>
      <tr>
        <td>11</td>
        <td>23/09/2016</td>
        <td>Awareness Rally On Cleanliness Of Our Surroundings.</td>
      </tr>
      <tr>
        <td>12</td>
        <td>02/10/2016</td>
        <td>Mass Plantation & Swatch Bharat On The Occasion Of Gandhi Jayanthi.</td>
      </tr>
      <tr>
        <td>13</td>
        <td>24/10/2016</td>
        <td>University Level Selections For PRDC At SK University & Meeting About Orientation Program For New Volunteers.</td>
      </tr>
      <tr>
        <td>14</td>
        <td>03/12/2016 To 09/12/2016</td>
        <td>Volunteers Represented JNTUA At NIC, Bellary.</td>
      </tr>
      <tr>
        <td>15</td>
        <td>28/12/2016</td>
        <td>Convocation.</td>
      </tr>
      <tr>
        <td>16</td>
        <td>17/01/2017</td>
        <td>NSS District Level Youth Festival 2017 At JNTUACEA.</td>
      </tr>
      <tr>
        <td>17</td>
        <td>24/01/2017</td>
        <td>Selections For National Women Parliament 2017 Conducted By Legislative Assembly Of Andhra Pradesh.</td>
      </tr>
      <tr>
        <td>18</td>
        <td>28/01/2017 , 29/01/2017 to 31/02/2017</td>
        <td>Awareness Rally On Polio Immunization & Success Meet Of ULYF 2017.</td>
      </tr>
      <tr>
        <td>19</td>
        <td>30/01/2017 To 31/01/2017</td>
        <td>Two Volunteers Of JNTUA Participated In State Level Youth Festival -2017 At Aadi Kavi Nannaya University Rajamundry.</td>
      </tr>
      <tr>
        <td>20</td>
        <td>04/02/2017</td>
        <td>Personality Development Program By Dr. A.P. Shiva Kumar.</td>
      </tr>
      <tr>
        <td>21</td>
        <td>10/02/2017 to 12/02/2017</td>
        <td>Volunteers Participated In National Women Parliament At Pavitra Sangamam.</td>
      </tr>
      <tr>
        <td>22</td>
        <td>17/02/2017 to 23/02/2017</td>
        <td>Awareness campaigning on locked house monitoring system by ATP Police.</td>
      </tr>
      <tr>
        <td>23</td>
        <td>08/03/2017</td>
        <td>Elocution competition on Digital India.</td>
      </tr>
      <tr>
        <td>24</td>
        <td>21/03/2017</td>
        <td>Role of youth in promoting child right under leadership training programme.</td>
      </tr>
      <tr>
        <td>25</td>
        <td>17/04/2017</td>
        <td>Lecture on “HIV –AIDS”.</td>
      </tr>
      <tr>
        <td>26</td>
        <td>27-09-2018</td>
        <td>Enrollment of Cadets.</td>
      </tr>
      <tr>
        <td>27</td>
        <td>24-09-2018</td>
        <td>Poster competition on open defecation.</td>
      </tr>
      <tr>
        <td>28</td>
        <td>02-10-2018</td>
        <td>Swatch Bharat.</td>
      </tr>
      <tr>
        <td>29</td>
        <td>02-10-2018</td>
        <td>Event on Surgical Strike: Video telecasting.</td>
      </tr>
      <tr>
        <td>30</td>
        <td>03-10-2018</td>
        <td>Lecture on Map reading class at 5.30am to 7.00am.</td>
      </tr>
      <tr>
        <td>31</td>
        <td>06-10-2018</td>
        <td>Drill: Marching, turns at 5.30am to 7.00am & Field Signals: 5.30pm to 7.00pm.</td>
      </tr>
      <tr>
        <td>32</td>
        <td>29-10-2018</td>
        <td>Weapon training theory class: 5.30am to 7.00am & obstacles: 5.30pm to 7.00pm.</td>
      </tr>
      <tr>
        <td>33</td>
        <td>31-10-2018</td>
        <td>Rashtriya Ekta Diwas: Pledge.</td>
      </tr>
      <tr>
        <td>34</td>
        <td>26-01-2019</td>
        <td>REPUBLIC DAY celebrations.</td>
      </tr>
      <tr>
        <td>35</td>
        <td>21-06-2019</td>
        <td>INTERNATIONAL YOGA DAY celebrations.</td>
      </tr>
      <tr>
        <td>36</td>
        <td>27/07/2019</td>
        <td>Kargil diwas.</td>
      </tr>
      <tr>
        <td>37</td>
        <td>31/10/2019</td>
        <td>Rashtriya Ekta Diwas rally was organized at the college premises by our NCC cadets.</td>
      </tr>
      <tr>
        <td>38</td>
        <td>31/10/2019</td>
        <td>Rashtriya Ekta Diwas rally was organized at the college premises by our NCC cadets.</td>
      </tr>
      <tr>
        <td>39</td>
        <td>22-06-2020</td>
        <td>National webinar on women health-COVID19 online.</td>
      </tr>
      <tr>
        <td>40</td>
        <td>15-07-2020</td>
        <td>Spit free India.</td>
      </tr>
      <tr>
        <td>41</td>
        <td>31-10-2020</td>
        <td>Rashtriya Ektha Diwas.</td>
      </tr>
      <tr>
        <td>42</td>
        <td>09-08-2021</td>
        <td>Literacy in a Digital World.</td>
      </tr>
      <tr>
        <td>43</td>
        <td>26-07-2022</td>
        <td>Celebrated Kargil diwas.</td>
      </tr>
      <tr>
        <td>44</td>
        <td>26-07-2022</td>
        <td>Celebrated Kargil diwas.</td>
      </tr>
      <tr>
        <td>45</td>
        <td>21-07-2022</td>
        <td>Conducted International Yoga Day by Jawaharlal Nehru Technological University Anantapur. Total no. of Students attended 350.</td>
      </tr>
      <tr>
        <td>46</td>
        <td>13-08-2022</td>
        <td>Conducted Har Ghar Tiranga Rally Programme at JNTUA.</td>
      </tr>
      <tr>
        <td>47</td>
        <td>14th August 2022</td>
        <td>Conducted Freedom Run Programme at JNTUA.</td>
      </tr>
      <tr>
        <td>48</td>
        <td>14-08-2022</td>
        <td>Conducted Freedom Run Programme at JNTUA.</td>
      </tr>
      <tr>
        <td>49</td>
        <td>14-08-2022 to 26-08-2022</td>
        <td>06 cadets Attended CATC-V programme.</td>
      </tr>
      <tr>
        <td>50</td>
        <td>24-01-2023</td>
        <td>National Girl Child Day.</td>
      </tr>
      <tr>
        <td>51</td>
        <td>22-03-2023</td>
        <td>National water day.</td>
      </tr>
    </tbody>
  </table>
</body>
</html>
