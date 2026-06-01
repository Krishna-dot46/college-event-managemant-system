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
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>About Us | Technical Fest 2025</title>
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
            background-color: #000000;
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
        }

        .container {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .team-member {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .team-member:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .team-member img {
            width: 200px;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .team-member:hover img {
            transform: scale(1.05);
        }

        .team-member h3 {
            color: #2c3e50;
            font-weight: 600;
            margin: 15px 0;
        }

        .team-member p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .section-title {
            color: white;
            font-weight: 600;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
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

        .button {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            margin: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 100%;
            text-align: left;
            font-weight: 500;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .button i {
            font-size: 1.2rem;
            color: #764ba2;
        }

        .button:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.98);
        }

        .info {
            display: none;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin: 15px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .guest-container, .guest-honour-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .guest-container:hover, .guest-honour-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .team {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
    </style>
</head>

<body>
    <?php include "inc/navbar.php"; ?>

    <div class="container" style="margin-top: 100px;">
        <h1 class="section-title" style="text-align: center;">About Us</h1>
        <h1 class="section-title" style="text-align: center;">INVITED BY</h1>
        <div class="team">
            <div class="team-member">
                <img src="./images/img/RD.jpg" alt="Dr.R.D.Patel" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                <h3 style="margin-top: 20px; color: #333; text-align: center;">Dr.R.D.Patel</h3>
                <p style="color: #666; font-size: 0.95rem; text-align: center;">Register<br>VVWU, Surat</p>
            </div>
            <div class="team-member">
                <img src="./images/img/nirali.jpg" alt="Dr. Nirali Dave" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                <h3 style="margin-top: 20px; color: #333; text-align: center;">Dr. Nirali Dave</h3>
                <p style="color: #666; font-size: 0.95rem; text-align: center;">Convener & Dean<br>Faculty of Computer Science and Information Technology</p>
            </div>
            <div class="team-member">
                <img src="./images/img/dikshan.webp" alt="Dr. Dikshan N. Shah" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                <h3 style="margin-top: 20px; color: #333; text-align: center;">Dr. Dikshan N. Shah</h3>
                <p style="color: #666; font-size: 0.95rem; text-align: center;">Assistant Professor of Computer Science & Information Technology</p>
            </div>
        </div>

        <h1 class="section-title" style="text-align: center;">Chief Guest</h1>
        <div class="guest-container">
            <img src="./images/img/kriplani.webp" alt="Shri Kriplani T. Desai" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
            <h3 style="margin-top: 20px; color: #333; text-align: center;">Shri Kriplani T. Desai</h3>
            <p style="color: #666; font-size: 0.95rem; text-align: center;">Hon'ble President, Board of Management, VVWU<br>Hon'ble Chairman, Vanita Vishram, Surat</p>
        </div>

        <h2 class="section-title" style="text-align: center;">Guest of Honour</h2>
        <div class="guest-honour-container">
            <img src="./images/img/pravin.webp" alt="Shri Pravin T. Vora" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
            <h3 style="margin-top: 20px; color: #333; text-align: center;">Shri Pravin T. Vora</h3>
            <p style="color: #666; font-size: 0.95rem; text-align: center;">Hon'ble Vice-President, Board of Management, VVWU<br>Hon'ble Vice-Chairman, Vanita Vishram, Surat</p>
        </div>

        <button class="button" onclick="toggleInfo('patronsInfo')">
            <i class="fas fa-users"></i> Patrons
        </button>
        <div id="patronsInfo" class="info">
            <div class="team">
                <div class="team-member">
                    <img src="./images/img/deepak.webp" alt="Shri Deepak N. Khambhat" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                    <h3 style="margin-top: 20px; color: #333; text-align: center;">Shri Deepak N. Khambhat</h3>
                    <p style="color: #666; font-size: 0.95rem; text-align: center;">Hon'ble President, VVWU, Surat</p>
                </div>
                <div class="team-member">
                    <img src="./images/img/kriplani.webp" alt="Shri Kriplani T. Desai" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                    <h3 style="margin-top: 20px; color: #333; text-align: center;">Shri Kriplani T. Desai</h3>
                    <p style="color: #666; font-size: 0.95rem; text-align: center;">Hon'ble President, Board of Management, VVWU<br>Hon'ble Chairman, Vanita Vishram, Surat</p>
                </div>
                <div class="team-member">
                    <img src="./images/img/pravin.webp" alt="Shri Pravin T. Vora" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                    <h3 style="margin-top: 20px; color: #333; text-align: center;">Shri Pravin T. Vora</h3>
                    <p style="color: #666; font-size: 0.95rem; text-align: center;">Hon'ble Vice-President, Board of Management, VVWU<br>Hon'ble Vice-Chairman, Vanita Vishram, Surat</p>
                </div>
                <div class="team-member">
                    <img src="./images/img/Dakshesh.jpg" alt="Dr. Daxesh Thakar" loading="lazy" style="border-radius: 50%; width: 200px; height: 250px; object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: block; margin: 0 auto;">
                    <h3 style="margin-top: 20px; color: #333; text-align: center;">Dr. Daxesh Thakar</h3>
                    <p style="color: #666; font-size: 0.95rem; text-align: center;">Hon'ble Provost<br>VVWU, Surat</p>
                </div>
            </div>
        </div>

        <button class="button" onclick="toggleInfo('vanitaInfo')">
            <i class="fas fa-university"></i> About Vanita Vishram
        </button>
        <div id="vanitaInfo" class="info">
            <p>With the efforts of two social workers – Smt. Bajigauri D. Munshi and Smt. Shivgauri K. Gajjar, an ashram was established on 15th May, 1907 for the welfare of helpless widows, divorcees etc. Today it is recognized as Vanita Vishram Institution...</p>
        </div>

        <button class="button" onclick="toggleInfo('vvwuInfo')">
            <i class="fas fa-graduation-cap"></i> About Vanita Vishram Women's University
        </button>
        <div id="vvwuInfo" class="info">
            <p>Vanita Vishram Women's University (VVWU) is the First & only Women's University of Gujarat...</p>
        </div>

        <button class="button" onclick="toggleInfo('csInfo')">
            <i class="fas fa-laptop-code"></i> About Department of Computer Science
        </button>
        <div id="csInfo" class="info">
            <p>Empowering the next generation of tech leaders, the Department of Computer Science offers innovative programmes...</p>
        </div>

        <button class="button" onclick="toggleInfo('vbytesInfo')">
            <i class="fas fa-microchip"></i> About V-Bytes
        </button>
        <div id="vbytesInfo" class="info">
            <p>V-Bytes is an initiative aimed at enhancing digital literacy and fostering technological innovation among students...</p>
        </div>
    </div>

    <?php include "inc/footer.php"; ?>

    <script src="inc/script.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        function toggleInfo(id) {
            const element = document.getElementById(id);
            if (element.style.display === "none" || element.style.display === "") {
                element.style.display = "block";
                element.style.animation = "slideDown 0.3s ease-out";
            } else {
                element.style.display = "none";
            }
        }

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
