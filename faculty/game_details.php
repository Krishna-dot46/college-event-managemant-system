<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if faculty is logged in
if(!isset($_SESSION['faculty_id'])) {
    header("location: login.php");
    exit();
}

// Check if game ID is provided
if(!isset($_GET['id'])) {
    header("location: all_game.php");
    exit();
}

$game_id = $_GET['id'];

// Get game details
$sql = "SELECT * FROM games WHERE game_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header("location: all_game.php");
    exit();
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Game Details">
    <title>Game Details</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: #2d3748;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 20px;
            background: white;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .card-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        }
        .card-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .card-body {
            padding: 30px;
        }
        .detail-row {
            padding: 20px 0;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .detail-row:hover {
            background: #f8fafc;
            padding-left: 10px;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.95rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            color: #2d3748;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .game-image {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .game-image:hover {
            transform: scale(1.02);
        }
        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-secondary {
            background: #64748b;
            border: none;
            color: white;
        }
        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-2px);
        }
        .btn i {
            margin-right: 8px;
        }
        .preloader {
            background-color: rgba(255,255,255,0.98);
        }
        .page-wrapper {
            padding: 30px;
            min-height: calc(100vh - 60px);
        }
        @media (max-width: 768px) {
            .page-wrapper {
                padding: 15px;
            }
            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <div class="header">
            <?php include('inc/navbar.php'); ?>
        </div>

        <?php include('inc/sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-b-0" style="color: white;">Game Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">Game Title</div>
                                            <div class="detail-value"><?php echo htmlspecialchars($row['title']); ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Category</div>
                                            <div class="detail-value"><?php echo htmlspecialchars($row['c_name']); ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Price</div>
                                            <div class="detail-value">
                                                <span class="badge badge-success" style="font-size: 1rem; padding: 8px 15px; background: #48bb78;">
                                                    ₹<?php echo number_format($row['price'], 2); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Registration Date</div>
                                            <div class="detail-value">
                                                <i class="fa fa-calendar-o mr-2"></i>
                                                <?php echo date('d M Y H:i', strtotime($row['registration_date'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                                        <?php if(!empty($row['img']) && file_exists("../admin/games_img/games/" . $row['img'])): ?>
                                            <div class="text-center">
                                                <img src="../admin/games_img/games/<?php echo htmlspecialchars($row['img']); ?>" 
                                                     class="game-image img-fluid" 
                                                     alt="<?php echo htmlspecialchars($row['title']); ?>">
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center p-4 bg-light rounded">
                                                <i class="fa fa-picture-o fa-3x text-muted"></i>
                                                <p class="mt-3 text-muted">No image available</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="detail-row">
                                            <div class="detail-label">Game Description</div>
                                            <div class="detail-value">
                                                <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <a href="all_game.php" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Back to All Games
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("inc/footer.php"); ?>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
