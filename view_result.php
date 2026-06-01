<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (!isset($_SESSION["user_id"])) {
    header('location:login.php');
    exit();
}

// Check if registration ID is provided
if (!isset($_GET['reg_id']) || empty($_GET['reg_id'])) {
    header('location:result.php');
    exit();
}

$registration_id = $_GET['reg_id'];
$user_id = $_SESSION['user_id'];

// Verify that the registration belongs to the logged-in user
$verify_sql = "SELECT gr.*, g.title, g.description 
               FROM game_registration gr 
               JOIN games g ON gr.game_id = g.game_id 
               WHERE gr.registration_id = ? AND gr.user_id = ?";
$verify_stmt = mysqli_prepare($db, $verify_sql);
mysqli_stmt_bind_param($verify_stmt, "ii", $registration_id, $user_id);
mysqli_stmt_execute($verify_stmt);
$verify_result = mysqli_stmt_get_result($verify_stmt);

if (mysqli_num_rows($verify_result) == 0) {
    header('location:result.php');
    exit();
}

$game_data = mysqli_fetch_assoc($verify_result);

// Get evaluation details
$eval_sql = "SELECT * FROM evaluation_sheet WHERE registration_id = ?";
$eval_stmt = mysqli_prepare($db, $eval_sql);
mysqli_stmt_bind_param($eval_stmt, "i", $registration_id);
mysqli_stmt_execute($eval_stmt);
$eval_result = mysqli_stmt_get_result($eval_stmt);
$eval_data = mysqli_fetch_assoc($eval_result);

// Get faculty information
$faculty_sql = "SELECT f.username, f.email 
                FROM evaluation_sheet es
                JOIN faculty f ON es.evaluated_by = f.faculty_id
                WHERE es.registration_id = ?";
$faculty_stmt = mysqli_prepare($db, $faculty_sql);
mysqli_stmt_bind_param($faculty_stmt, "i", $registration_id);
mysqli_stmt_execute($faculty_stmt);
$faculty_result = mysqli_stmt_get_result($faculty_stmt);
$faculty_data = mysqli_fetch_assoc($faculty_result);

// Get winner status
$winner_sql = "SELECT 
                CASE 
                    WHEN es.total_marks = (
                        SELECT MAX(es2.total_marks) 
                        FROM evaluation_sheet es2 
                        JOIN game_registration gr2 ON es2.registration_id = gr2.registration_id 
                        WHERE gr2.game_id = gr.game_id
                    ) THEN 'Winner' 
                    ELSE 'Participant' 
                END as status,
                es.total_marks
                FROM game_registration gr 
                JOIN evaluation_sheet es ON gr.registration_id = es.registration_id
                WHERE gr.registration_id = ?";
$winner_stmt = mysqli_prepare($db, $winner_sql);
mysqli_stmt_bind_param($winner_stmt, "i", $registration_id);
mysqli_stmt_execute($winner_stmt);
$winner_result = mysqli_stmt_get_result($winner_stmt);
$winner_data = mysqli_fetch_assoc($winner_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Detailed evaluation results for your competition performance">
    <meta name="author" content="VVWU">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Evaluation Results | VVWU</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: #444;
            background-color: #000;
            background-image: url('images/circle-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            padding: 25px;
            border-bottom: none;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
            font-size: 1.5rem;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border-radius: 4px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: #f0f4f8;
            color: #333;
            font-weight: 600;
            border-top: none;
            padding: 15px;
            font-size: 1.05rem;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .badge-winner {
            background: linear-gradient(to right, #FF8008 0%, #FFC837 100%);
            color: #fff;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(255, 128, 8, 0.3);
            animation: pulse 2s infinite;
        }

        .badge-participant {
            background: linear-gradient(to right, #4776E6 0%, #8E54E9 100%);
            color: #fff;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(71, 118, 230, 0.3);
        }

        .progress {
            height: 12px;
            border-radius: 6px;
            margin-top: 8px;
            overflow: hidden;
            background-color: #e9ecef;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            background: linear-gradient(to right, #4776E6 0%, #8E54E9 100%);
            border-radius: 6px;
            transition: width 1.5s ease;
        }

        .game-info {
            background-color: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .game-info:hover {
            transform: translateY(-3px);
        }

        .judge-info {
            background-color: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .judge-info:hover {
            transform: translateY(-3px);
        }

        .btn-back {
            background: linear-gradient(to right, #4776E6 0%, #8E54E9 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(71, 118, 230, 0.3);
        }

        .btn-back:hover {
            box-shadow: 0 8px 20px rgba(71, 118, 230, 0.5);
            transform: translateY(-3px);
            color: white;
        }

        .page-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 50px;
            border-radius: 0 0 50% 50% / 30px;
            box-shadow: 0 10px 30px rgba(106, 17, 203, 0.3);
        }

        .page-header h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .text-primary {
            color: #6a11cb !important;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .bg-light {
            background-color: #f0f4f8 !important;
        }

        .rounded {
            border-radius: 15px !important;
        }

        .p-3 {
            padding: 1.5rem !important;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 128, 8, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 128, 8, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 128, 8, 0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 1s ease-in-out;
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
    </style>
</head>

<body>
    <?php include("inc/navbar.php"); ?>

    <div class="page-header text-center animate__animated animate__fadeIn">
        <div class="container">
            <h1><i class="fa fa-trophy"></i> Evaluation Results</h1>
            <p>Detailed performance evaluation for your competition</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center mb-4 animate__animated animate__fadeInUp">
                    <a href="result.php" class="btn btn-back" style="margin-bottom: 20px;"><i class="fa fa-arrow-left mr-2"></i> Back to Results</a>
                </div>

                <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="card-header">
                        <h4 class="card-title mb-0 text-white"><i class="fa fa-gamepad mr-2"></i><?php echo $game_data['title']; ?> - Evaluation Results</h4>
                    </div>
                    <div class="card-body">
                        <div class="game-info mb-4 animate-fadeIn">
                            <h5 class="section-title">Game Information</h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <p><strong>Description:</strong> <?php echo $game_data['description']; ?></p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <?php if ($winner_data['status'] == 'Winner'): ?>
                                        <div class="badge-winner mb-3"><i class="fa fa-trophy mr-1"></i> Winner</div>
                                    <?php else: ?>
                                        <div class="badge-participant mb-3"><i class="fa fa-user mr-1"></i> Participant</div>
                                    <?php endif; ?>
                                    <h4 class="text-primary"><?php echo $winner_data['total_marks']; ?> Points</h4>
                                </div>
                            </div>
                        </div>

                        <?php if ($faculty_data): ?>
                            <div class="judge-info mb-4 animate-fadeIn" style="animation-delay: 0.3s">
                                <h5 class="section-title">Evaluated By</h5>
                                <p><strong><i class="fa fa-user-circle mr-2"></i><?php echo $faculty_data['username']; ?></strong></p>
                                <p class="text-muted"><?php echo $faculty_data['email']; ?></p>
                            </div>
                        <?php endif; ?>

                        <h5 class="section-title animate-fadeIn" style="animation-delay: 0.4s; margin-left: 20px;">Evaluation Breakdown</h5>
                        <div class="table-responsive animate-fadeIn" style="animation-delay: 0.5s; margin-left: 20px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($eval_data): ?>
                                        <tr>
                                            <td><strong>Lines of Code</strong></td>
                                            <td><?php echo $eval_data['lines_of_code_marks']; ?>/10</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Logic</strong></td>
                                            <td><?php echo $eval_data['logic_marks']; ?>/20</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Errors</strong></td>
                                            <td><?php echo $eval_data['errors_marks']; ?>/10</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Output</strong></td>
                                            <td><?php echo $eval_data['output_marks']; ?>/10</td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No evaluation data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th colspan="1">Total</th>
                                        <th colspan="1"><?php echo $eval_data ? $eval_data['total_marks'] : 0; ?>/50</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <?php if ($eval_data && $eval_data['remarks']): ?>
                            <div class="mt-4 animate-fadeIn" style="animation-delay: 0.6s">
                                <h5 class="section-title">Remarks</h5>
                                <div class="p-3 bg-light rounded">
                                    <?php echo $eval_data['remarks']; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mt-5 animate-fadeIn" style="animation-delay: 0.7s">
                            <a href="result.php" class="btn btn-back" style="margin-bottom: 20px; margin-left: 20px;"><i class="fa fa-arrow-left mr-2"></i> Back to Results</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("inc/footer.php"); ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <script src="inc/script.js"></script>
    <script>
        // Initialize animations for elements with animate-fadeIn class
        $(document).ready(function() {
            $('.animate-fadeIn').each(function(i) {
                var $this = $(this);
                setTimeout(function() {
                    $this.css({
                        'animation': 'fadeIn 1s ease-in-out forwards',
                        'opacity': '0'
                    });
                }, i * 200);
            });

            // Animate progress bars on load
            setTimeout(function() {
                $('.progress-bar').each(function() {
                    var width = $(this).css('width');
                    $(this).css('width', '0');
                    $(this).animate({
                        width: width
                    }, 1000);
                });
            }, 500);
        });
    </script>
</body>

</html>