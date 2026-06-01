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
    <title>Event Registration</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #000;
            background-image: url('images/circle-bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .navbar {
            background: rgba(33, 33, 33, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
        }

        .hero-section {
            padding: 150px 0;
            text-align: center;
            color: white;
            position: relative;
            background: rgba(0, 0, 0, 0.4);
        }

        .event-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .register-btn {
            background: linear-gradient(45deg, #6c5ce7, #a55eea);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .register-btn:hover {
            background: linear-gradient(45deg, #5d4ed6, #9346e8);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(108,92,231,0.2);
            color: white;
        }

        .countdown-timer {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .countdown-box {
            background: rgba(0,0,0,0.3);
            padding: 20px;
            border-radius: 10px;
            margin: 0 10px;
            min-width: 100px;
        }

        .testimonial-card {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .category-badge {
            background: linear-gradient(45deg, #6c5ce7, #a55eea);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-right: 10px;
        }

        .social-icons {
            margin-top: 30px;
        }

        .social-icons a {
            color: white;
            font-size: 24px;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            transform: scale(1.2);
        }

        .stats-box {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: white;
            margin: 20px 0;
        }

        .stats-box h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .newsletter-form {
            max-width: 500px;
            margin: 30px auto;
        }

        .newsletter-form input {
            background: rgba(255,255,255,0.9);
            border: none;
            padding: 15px;
            border-radius: 25px;
            width: 70%;
            margin-right: 10px;
        }

        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(45deg, #6c5ce7, #a55eea);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .scroll-to-top:hover {
            transform: translateY(-5px);
        }

        .loading-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #6c5ce7;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body>
    <div class="loading-animation">
        <div class="spinner"></div>
    </div>

    <div style="background-image: url('images/event-bg.jpg'); width: 100%; background-attachment: fixed; background-size: cover; min-height: 100vh; display: flex; flex-direction: column; position: relative;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(3px);"></div>

        <?php include "inc/navbar.php"; ?>

        <section class="hero-section" style="background-image: url('images/HeroBg.jpg'); background-size: cover; background-position: center; padding: 100px 0; position: relative;">
            <div class="container" style="position: relative; z-index: 2; text-align: center;">
                <img src="images/vbytelogo.webp" alt="VByte Logo" style="max-width: 300px; margin-bottom: 30px;">
                <h1 style="font-size: 56px; font-weight: 800; margin-bottom: 20px; color: white;">Technical Fest 2025</h1>
                <p style="font-size: 24px; margin-bottom: 40px; color: white;">Join us for the biggest cultural and technical festival</p>

                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5);"></div>
        </section>

        <section class="stats-section" style="padding: 50px 0;">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-box text-center" style="background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                            <h3 style="font-size: 42px; font-weight: 700; color: #6c5ce7; margin-bottom: 10px;">50+</h3>
                            <p style="font-size: 18px; color: #2d3436; margin: 0;">Events</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-box text-center" style="background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                            <h3 style="font-size: 42px; font-weight: 700; color: #6c5ce7; margin-bottom: 10px;">1000+</h3>
                            <p style="font-size: 18px; color: #2d3436; margin: 0;">Participants</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-box text-center" style="background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                            <h3 style="font-size: 42px; font-weight: 700; color: #6c5ce7; margin-bottom: 10px;">20+</h3>
                            <p style="font-size: 18px; color: #2d3436; margin: 0;">Colleges</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-box text-center" style="background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                            <h3 style="font-size: 42px; font-weight: 700; color: #6c5ce7; margin-bottom: 10px;">₹1L+</h3>
                            <p style="font-size: 18px; color: #2d3436; margin: 0;">Prize Pool</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="categories-section" style="padding: 50px 0;">
            <div class="container">
                <h2 class="text-center mb-5" style="color: #ffffff; font-weight: 600; position: relative; z-index: 1; padding-bottom: 20px;">Game Categories</h2>
                <div class="row">
                    <?php
                    $query = mysqli_query($db, "SELECT * FROM categories");
                    while($category = mysqli_fetch_array($query)) {
                        echo '<div class="col-md-4 mb-4">
                            <div class="event-card text-center" style="background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                                <i class="fas fa-gamepad fa-3x mb-4" style="color: #6c5ce7;"></i>
                                <h3 style="color: #2d3436; font-size: 24px; margin: 15px 0;">'.$category['c_name'].'</h3>
                                <p style="color: #636e72; margin-bottom: 20px;">Explore '.$category['c_name'].' Games</p>
                                <a href="games.php?category='.$category['c_id'].'" class="btn btn-primary" style="background: #6c5ce7; border: none; padding: 10px 25px; border-radius: 25px;">Explore</a>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <section class="events-section" style="padding: 50px 0;">
            <div class="container">
                <h2 class="text-center text-white mb-5" style="color: #ffffff; font-weight: 600; position: relative; z-index: 1; padding-bottom: 20px;">Featured Games</h2>
                <div class="row">
                    <?php
                    $query = mysqli_query($db, "SELECT * FROM games LIMIT 6");
                    while($event = mysqli_fetch_array($query)) {
                        echo '<div class="col-md-4">
                            <div class="event-card">
                                <img src="admin/games_img/games/'.$event['img'].'" alt="'.$event['title'].'" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;">
                                <span class="category-badge">'.$event['c_name'].'</span>
                                <h3 style="color: #2d3436; font-size: 24px; margin: 15px 0;">'.$event['title'].'</h3>
                                <div style="margin-bottom: 20px;">
                                    <p><i class="fas fa-rupee-sign"></i> Entry Fee: ₹'.$event['price'].'</p>
                                    <p><i class="far fa-calendar-alt"></i> Registration Date: '.date('d M Y', strtotime($event['registration_date'])).'</p>
                                </div>
                                <div class="text-center">
                                    <a href="game_register.php?game_id='.$event['game_id'].'" class="register-btn">Register Now</a>
                                </div>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <section class="testimonials" style="padding: 80px 0; background: rgba(255,255,255,0.1);">
            <div class="container">
                <h2 class="text-center text-white mb-5" style="color: #ffffff; font-weight: 600; position: relative; z-index: 1; padding-bottom: 20px;">What Past Participants Say</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <p>"An amazing experience! The events were well organized and fun."</p>
                            <h4>- John Doe</h4>
                            <p>Dance Competition Winner 2023</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <p>"Great platform to showcase technical skills and meet new people."</p>
                            <h4>- Jane Smith</h4>
                            <p>Hackathon Participant</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <p>"The workshops were informative and helped me learn new skills."</p>
                            <h4>- Mike Johnson</h4>
                            <p>Workshop Attendee</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include "inc/footer.php"; ?>
    </div>

    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- JavaScript -->
    <script src="inc/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Loading Animation
        window.addEventListener('load', function() {
            document.querySelector('.loading-animation').style.display = 'none';
        });

        // Scroll to Top
        document.querySelector('.scroll-to-top').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Show/Hide Scroll to Top button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                document.querySelector('.scroll-to-top').style.display = 'flex';
            } else {
                document.querySelector('.scroll-to-top').style.display = 'none';
            }
        });

        // Countdown Timer
        const countDownDate = new Date("March 15, 2024 00:00:00").getTime();
        
        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownDate - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;
            
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);

        // Newsletter Form
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for subscribing!');
            this.reset();
        });
    </script>
</body>
</html>