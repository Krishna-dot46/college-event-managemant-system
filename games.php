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
    <meta name="description" content="Technical Fest 2025 Games - Participate in exciting technical and non-technical games">
    <meta name="author" content="College Fest Team">
    <meta name="theme-color" content="#667eea">
    <link rel="icon" href="images/favicon.ico">
    <title>Games | Technical Fest 2025</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --hover-gradient: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-primary: #2c3e50;
            --text-secondary: #666;
            --border-radius: 20px;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

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
        }

        .navbar {
            background: rgba(33, 33, 33, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
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

        .game-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            transition: var(--transition);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .game-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .game-card img {
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .game-card:hover img {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .game-card h5 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 15px 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .game-card p {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
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

        .btn-play {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-play:hover {
            background: var(--hover-gradient);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .rules-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }

        .rules-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .rules-content {
            padding: 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            margin-top: 10px;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 25px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .btn-register {
            background: var(--hover-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-left: 10px;
        }

        .btn-register:hover {
            background: var(--primary-gradient);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .game-card {
                padding: 20px;
            }

            .game-card h5 {
                font-size: 1.25rem;
            }

            .container {
                padding: 0 10px;
            }
        }

        @media (max-width: 576px) {
            .game-card {
                margin-bottom: 20px;
            }

            .btn-play, .btn-register {
                width: 100%;
                justify-content: center;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <?php include "inc/navbar.php"; ?>

    <div class="container" style="margin-top: 100px; margin-bottom: 50px;">
        <h2 class="section-title">Technical Games</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM games WHERE c_name='technical' ORDER BY title ASC";
            $query = mysqli_query($db, $sql);
            
            if(!mysqli_num_rows($query) > 0) {
                echo '<div class="col-12"><div class="alert alert-info">No technical games found</div></div>';
            } else {
                while($row = mysqli_fetch_array($query)) {
                    ?>
                    <div class="col-md-6">
                        <div class="game-card">
                            <img src="admin/games_img/games/<?php echo htmlspecialchars($row['img']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                 class="img-fluid mb-3" 
                                 style="border-radius: 10px; width: 100%; height: 250px; object-fit: cover;">
                            <h5>
                                <i class="fas fa-gamepad me-2" style="font-size: 1.5rem; margin-right:10px;"></i>
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h5>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="rules-section">
                                <h6 class="rules-title">
                                    <i class="fas fa-scroll"></i> Rules:
                                </h6>
                                <div class="rules-content">
                                    <?php echo nl2br(htmlspecialchars($row['registration_rules'])); ?>
                                </div>
                            </div>
                            <div class="button-group mt-3">
                                <a href="<?php echo isset($_SESSION['user_id']) ? 'game_register.php?game_id=' . $row['game_id'] : 'login.php'; ?>" class="btn btn-register">
                                    <i class="fas fa-user-plus"></i>
                                    Register Now
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <h2 class="section-title">Non-Technical Games</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM games WHERE c_name='non-technical' ORDER BY title ASC";
            $query = mysqli_query($db, $sql);
            
            if(!mysqli_num_rows($query) > 0) {
                echo '<div class="col-12"><div class="alert alert-info">No non-technical games found</div></div>';
            } else {
                while($row = mysqli_fetch_array($query)) {
                    ?>
                    <div class="col-md-6">
                        <div class="game-card">
                            <img src="admin/games_img/games/<?php echo htmlspecialchars($row['img']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                 class="img-fluid mb-3" 
                                 style="border-radius: 10px; width: 100%; height: 250px; object-fit: cover;">
                            <h5>
                                <i class="fas fa-gamepad me-2" style="font-size: 1.5rem; margin-right:10px;"></i>
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h5>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="rules-section">
                                <h6 class="rules-title">
                                    <i class="fas fa-scroll"></i> Rules:
                                </h6>
                                <div class="rules-content">
                                    <?php echo nl2br(htmlspecialchars($row['registration_rules'])); ?>
                                </div>
                            </div>
                            <div class="button-group mt-3">
                                <a href="<?php echo isset($_SESSION['user_id']) ? 'game_register.php?game_id=' . $row['game_id'] : 'login.php'; ?>" class="btn btn-register">
                                    <i class="fas fa-user-plus"></i>
                                    Register Now
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <?php include "inc/footer.php"; ?>

    <script src="inc/script.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.game-card');
            cards.forEach((card, index) => {
                card.style.animation = `fadeIn 0.5s ease-out ${index * 0.1}s forwards`;
                card.style.opacity = '0';
            });
        });
    </script>
</body>
</html>