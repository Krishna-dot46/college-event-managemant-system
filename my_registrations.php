<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's registrations
$sql = "SELECT gr.*, g.title, g.img, g.registration_date, g.price 
        FROM game_registration gr
        JOIN games g ON gr.game_id = g.game_id 
        WHERE gr.user_id = ?
        ORDER BY gr.registration_date DESC";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="View your registered games and events for Technical Fest 2025">
    <title>My Registrations | Technical Fest 2025</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000;
            background-image: url('images/circle-bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #fff;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .registrations-container {
            width: 100%;
            max-width: 1400px;
            margin: 100px auto 60px;
            padding: 0 30px;
            animation: fadeIn 0.8s ease-in-out;
            position: relative;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .registration-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            margin-bottom: 35px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.25);
            height: 100%;
        }

        .registration-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 15px 45px rgba(0,0,0,0.25);
        }

        .game-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.6s ease;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .registration-card:hover .game-img {
            transform: scale(1.12);
        }

        .registration-details {
            padding: 30px;
            position: relative;
            z-index: 1;
            background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            height: calc(100% - 280px);
            display: flex;
            flex-direction: column;
        }

        .game-title {
            font-family: 'Onest', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.15);
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .registration-info {
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 15px;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            gap: 10px;
        }

        .registration-info:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .registration-info strong {
            min-width: 150px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .registration-info i {
            margin-right: 12px;
            color: rgba(255,255,255,0.9);
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .price-tag {
            background: linear-gradient(45deg, #00b4db, #0083b0);
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            margin-top: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            gap: 8px;
            align-self: flex-start;
        }

        .status-badge {
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 25px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: translateX(-100%) rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            20%, 100% { transform: translateX(100%) rotate(45deg); }
        }

        .status-pending {
            background: linear-gradient(45deg, #ffd700, #ffa500);
            color: #000;
            border: none;
        }

        .status-approved {
            background: linear-gradient(45deg, #00b09b, #96c93d);
            color: #fff;
            border: none;
        }

        .status-cancelled {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: #fff;
            border: none;
        }

        .no-registrations {
            text-align: center;
            padding: 70px 40px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        .no-registrations h3 {
            color: #fff;
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        .no-registrations p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.25rem;
            margin-bottom: 30px;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background: linear-gradient(45deg, #00b4db, #0083b0);
            border: none;
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #0083b0, #00b4db);
        }

        .page-title {
            font-family: 'Onest', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 50px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.15);
            letter-spacing: 1.5px;
            position: relative;
            padding-bottom: 15px;
            line-height: 1.2;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #00b4db, #0083b0);
            border-radius: 2px;
        }

        .row {
            margin-left: -15px;
            margin-right: -15px;
            display: flex;
            flex-wrap: wrap;
        }

        .col-md-4 {
            padding: 15px;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        @media (max-width: 1200px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 768px) {
            .registrations-container {
                margin: 60px auto 40px;
                padding: 15px;
            }
            
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .game-title {
                font-size: 1.6rem;
            }

            .registration-info {
                font-size: 1rem;
                flex-direction: column;
                align-items: flex-start;
            }

            .registration-info strong {
                margin-bottom: 5px;
            }

            .page-title {
                font-size: 2.2rem;
                margin-bottom: 35px;
            }

            .no-registrations {
                padding: 40px 20px;
            }

            .no-registrations h3 {
                font-size: 1.8rem;
            }

            .no-registrations p {
                font-size: 1.1rem;
            }

            .game-img {
                height: 220px;
            }

            .status-badge {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <?php include("inc/navbar.php"); ?>

    <div class="registrations-container">
        <h2 class="page-title">My Registrations</h2>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="row">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="registration-card">
                            <img src="admin/games_img/games/<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="game-img">
                            <div class="registration-details">
                                <h3 class="game-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                                
                                <?php 
                                    $status_class = '';
                                    $status_icon = '';
                                    switch(strtolower($row['registration_status'])) {
                                        case 'pending':
                                            $status_class = 'status-pending';
                                            $status_icon = 'fa-hourglass-half';
                                            break;
                                        case 'approved':
                                            $status_class = 'status-approved';
                                            $status_icon = 'fa-check-circle';
                                            break;
                                        case 'cancelled':
                                            $status_class = 'status-cancelled';
                                            $status_icon = 'fa-times-circle';
                                            break;
                                    }
                                ?>
                                <div class="status-badge <?php echo $status_class; ?>">
                                    <i class="fas <?php echo $status_icon; ?> fa-lg"></i>
                                    <?php echo ucfirst(htmlspecialchars($row['registration_status'])); ?>
                                </div>

                                <p class="registration-info">
                                    <strong><i class="fas fa-user"></i> Student:</strong> <?php echo htmlspecialchars($row['student_name']); ?>
                                </p>
                                <p class="registration-info">
                                    <strong><i class="fas fa-university"></i> College:</strong> <?php echo htmlspecialchars($row['college']); ?>
                                </p>
                                <p class="registration-info">
                                    <strong><i class="fas fa-book"></i> Department:</strong> <?php echo htmlspecialchars($row['department']); ?>
                                </p>
                                <p class="registration-info">
                                    <strong><i class="fas fa-calendar"></i> Event Date:</strong> <?php echo date('d M Y', strtotime($row['registration_date'])); ?>
                                </p>
                                <p class="registration-info">
                                    <strong><i class="fas fa-credit-card"></i> Payment:</strong> <?php echo htmlspecialchars($row['payment_mode']); ?>
                                </p>
                                <div class="price-tag">
                                    <i class="fas fa-rupee-sign"></i> <?php echo htmlspecialchars($row['price']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-registrations">
                <h3><i class="fas fa-calendar-times mb-4" style="font-size: 3.5rem; color: rgba(255,255,255,0.9);"></i><br>No registrations found</h3>
                <p>You haven't registered for any games yet. Start your gaming journey today!</p>
                <a href="games.php" class="btn btn-primary">
                    <i class="fas fa-gamepad"></i>Browse Games
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php include("inc/footer.php"); ?>

    <script src="inc/script.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        // Add smooth scroll animation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add fade-in animation for cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.registration-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.animation = `fadeIn 0.5s ease-out ${index * 0.2}s forwards`;
            });
        });
    </script>
</body>
</html>
