<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Technical Fest 2025 Schedule - Event timings and venue details">
    <meta name="author" content="College Fest Team">
    <link rel="icon" href="images/favicon.ico">
    <title>Schedule | Technical Fest 2025</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000;
            background-image: url('images/circle-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            color: #333;
        }

        .navbar {
            background: rgba(33, 33, 33, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .container {
            animation: fadeIn 1s ease-in-out;
            padding: 0 15px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .schedule-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .schedule-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            color: white;
            font-weight: 600;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
            font-size: 2.5rem;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #fff, transparent);
        }

        .table thead th {
            background: linear-gradient(135deg, #764ba2, #667eea);
            color: white;
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .guidelines-list li {
            margin-bottom: 10px;
            color: #666;
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .schedule-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include "inc/navbar.php"; ?>

    <div class="container" style="margin-top: 100px; margin-bottom: 50px;">
        <h2 class="section-title">Event Schedule</h2>
        <div class="schedule-card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Venue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>9:00 AM - 10:30 AM</td>
                        <td>Code Trorika War</td>
                        <td>Lab 101</td>
                    </tr>
                    <tr>
                        <td>10:45 AM - 12:15 PM</td>
                        <td>Sprint Web using AI</td>
                        <td>Lab 102</td>
                    </tr>
                    <tr>
                        <td>1:00 PM - 2:30 PM</td>
                        <td>Querying DB</td>
                        <td>Lab 103</td>
                    </tr>
                    <tr>
                        <td>2:45 PM - 4:15 PM</td>
                        <td>Blind Coding</td>
                        <td>Lab 104</td>
                    </tr>
                    <tr>
                        <td>4:30 PM - 6:00 PM</td>
                        <td>Prize Distribution</td>
                        <td>Main Hall</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class="section-title">Competition Guidelines</h2>
        <div class="schedule-card">
            <h5 class="mb-4">General Rules</h5>
            <ul class="guidelines-list">
                <li>All participants must register at least 30 minutes before their event</li>
                <li>Participants must bring their own laptops with required software installed</li>
                <li>Internet access will be provided for relevant events</li>
                <li>Decision of judges will be final and binding</li>
                <li>Any form of plagiarism will lead to immediate disqualification</li>
            </ul>
        </div>

        <h2 class="section-title">Certificate Policy</h2>
        <div class="schedule-card">
            <ul class="guidelines-list">
                <li>Participation certificates will be provided to all registered participants</li>
                <li>Merit certificates will be awarded to top 3 winners in each category</li>
                <li>Digital certificates will be emailed within 7 working days after the event</li>
                <li>Physical certificates can be collected from the college office</li>
            </ul>
        </div>

        <h2 class="section-title">Venue Details</h2>
        <div class="schedule-card">
            <h5 class="mb-4">Computer Labs</h5>
            <p><strong>Lab 101:</strong> Main Building, First Floor - Capacity: 40 participants</p>
            <p><strong>Lab 102:</strong> Main Building, First Floor - Capacity: 40 participants</p>
            <p><strong>Lab 103:</strong> Main Building, Second Floor - Capacity: 35 participants</p>
            <p><strong>Lab 104:</strong> Main Building, Second Floor - Capacity: 35 participants</p>
            
            <h5 class="mt-4 mb-4">Main Hall</h5>
            <p>Ground Floor, Main Building - Capacity: 200 people</p>
            
            <h5 class="mt-4 mb-4">Facilities</h5>
            <ul class="guidelines-list">
                <li>High-speed internet connectivity</li>
                <li>Air-conditioned rooms</li>
                <li>Power backup</li>
                <li>Drinking water</li>
                <li>Rest rooms on each floor</li>
            </ul>
        </div>
    </div>

    <?php include "inc/footer.php"; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="inc/script.js"></script>
</body>
</html>