<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if admin is logged in
if(!isset($_SESSION['adm_id'])) {
    header("location: login.php");
    exit();
}

// Check if registration ID is provided
if(!isset($_GET['id'])) {
    header("location: all_game_registration.php");
    exit();
}

$registration_id = $_GET['id'];

// Get registration details with user and game info
$sql = "SELECT gr.*, g.title as game_title, g.price, g.description, g.img,
        u.username, u.f_name, u.l_name, u.email, u.phone 
        FROM game_registration gr
        JOIN games g ON gr.game_id = g.game_id
        JOIN users u ON gr.user_id = u.u_id
        WHERE gr.registration_id = ?";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $registration_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header("location: all_game_registration.php");
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
    <meta name="description" content="Registration Details">
    <title>Registration Details</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        .card-header {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .detail-row {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: 600;
            color: #2d3748;
        }
        .detail-value {
            color: #4a5568;
        }
        .game-image {
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-b-0" style="color: white;">Registration Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">Registration ID</div>
                                            <div class="detail-value">#<?php echo $row['registration_id']; ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Game Title</div>
                                            <div class="detail-value"><?php echo $row['game_title']; ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">User Name</div>
                                            <div class="detail-value">
                                                <?php echo $row['f_name'] . ' ' . $row['l_name']; ?>
                                                <small class="text-muted">(@<?php echo $row['username']; ?>)</small>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Email</div>
                                            <div class="detail-value"><?php echo $row['email']; ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Phone</div>
                                            <div class="detail-value"><?php echo $row['phone']; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">Registration Date</div>
                                            <div class="detail-value">
                                                <?php echo date('d M Y H:i', strtotime($row['registration_date'])); ?>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Registration Status</div>
                                            <div class="detail-value">
                                                <?php
                                                $status = $row['registration_status'];
                                                $statusInfo = [
                                                    'approved' => [
                                                        'class' => 'success',
                                                        'bg' => 'linear-gradient(135deg, #28a745 0%, #20c997 100%)',
                                                        'icon' => 'fa-check-circle'
                                                    ],
                                                    'rejected' => [
                                                        'class' => 'danger',
                                                        'bg' => 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)',
                                                        'icon' => 'fa-times-circle'
                                                    ],
                                                    'pending' => [
                                                        'class' => 'warning',
                                                        'bg' => 'linear-gradient(135deg, #ffc107 0%, #fd7e14 100%)',
                                                        'icon' => 'fa-clock'
                                                    ]
                                                ];
                                                
                                                $currentStatus = $statusInfo[$status] ?? $statusInfo['pending'];
                                                ?>
                                                <span class="status-badge" style="
                                                    display: inline-flex;
                                                    align-items: center;
                                                    padding: 8px 16px;
                                                    border-radius: 50px;
                                                    font-size: 14px;
                                                    font-weight: 600;
                                                    text-transform: uppercase;
                                                    letter-spacing: 0.5px;
                                                    background: <?php echo $currentStatus['bg']; ?>;
                                                    color: #fff;
                                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                                    border: none;
                                                    transition: all 0.3s ease;
                                                    gap: 6px;">
                                                    <i class="fa <?php echo $currentStatus['icon']; ?>"></i>
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Amount</div>
                                            <div class="detail-value">₹<?php echo $row['price']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="detail-row">
                                            <div class="detail-label">Game Description</div>
                                            <div class="detail-value"><?php echo $row['description']; ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <a href="all_game_registration.php" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Back to All Registrations
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
