<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if faculty is logged in
if(empty($_SESSION["faculty_id"])) {
    header('location:index.php');
    exit();
}

// Check if registration ID is provided
if(!isset($_GET['reg_id'])) {
    header("location: all_winner_list.php");
    exit();
}

$registration_id = $_GET['reg_id'];

// Get registration details with evaluation data
$sql = "SELECT gr.*, g.title as game_title, g.c_name, 
        es.lines_of_code_marks, es.logic_marks, es.errors_marks, 
        es.output_marks, es.total_marks, es.remarks, 
        f.username as evaluator_name
        FROM game_registration gr
        JOIN games g ON gr.game_id = g.game_id
        LEFT JOIN evaluation_sheet es ON gr.registration_id = es.registration_id
        LEFT JOIN faculty f ON es.evaluated_by = f.faculty_id
        WHERE gr.registration_id = ?";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $registration_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header("location: all_winner_list.php");
    exit();
}

$row = mysqli_fetch_assoc($result);
$is_technical = ($row['c_name'] == 'Technical');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Evaluation Details</title>
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
            margin-bottom: 30px;
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
        .evaluation-score {
            font-size: 18px;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #ebf8ff;
        }
        .total-score {
            font-size: 24px;
            font-weight: bold;
            color: #3182ce;
            padding: 15px;
            background: #ebf8ff;
            border-radius: 8px;
            margin: 20px 0;
        }
        .remarks-box {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #3182ce;
        }
        .btn-back {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: white;
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
        <?php include("inc/header.php"); ?>
        <?php include("inc/sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Evaluation Details</h3>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="all_winner_list.php">Winners</a></li>
                            <li class="breadcrumb-item active">Evaluation Details</li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-b-0" style="color: white;">Participant Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row detail-row">
                                    <div class="col-md-3 detail-label">Game Name</div>
                                    <div class="col-md-9 detail-value"><?php echo $row['game_title']; ?></div>
                                </div>
                                <div class="row detail-row">
                                    <div class="col-md-3 detail-label">Student Name</div>
                                    <div class="col-md-9 detail-value"><?php echo $row['student_name']; ?></div>
                                </div>
                                <div class="row detail-row">
                                    <div class="col-md-3 detail-label">College</div>
                                    <div class="col-md-9 detail-value"><?php echo $row['college']; ?></div>
                                </div>
                                <div class="row detail-row">
                                    <div class="col-md-3 detail-label">Department</div>
                                    <div class="col-md-9 detail-value"><?php echo $row['department']; ?></div>
                                </div>
                                <div class="row detail-row">
                                    <div class="col-md-3 detail-label">Contact</div>
                                    <div class="col-md-9 detail-value"><?php echo $row['contact']; ?></div>
                                </div>
                                <div class="row detail-row">
                                    <div class="col-md-3 detail-label">Email</div>
                                    <div class="col-md-9 detail-value"><?php echo $row['email']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if($is_technical && isset($row['lines_of_code_marks'])): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-b-0" style="color: white;">Evaluation Scores</h4>
                            </div>
                            <div class="card-body">
                                <div class="evaluation-score">
                                    <div class="row">
                                        <div class="col-md-6">Lines of Code</div>
                                        <div class="col-md-6 text-right"><?php echo $row['lines_of_code_marks']; ?> marks</div>
                                    </div>
                                </div>
                                <div class="evaluation-score">
                                    <div class="row">
                                        <div class="col-md-6">Logic</div>
                                        <div class="col-md-6 text-right"><?php echo $row['logic_marks']; ?> marks</div>
                                    </div>
                                </div>
                                <div class="evaluation-score">
                                    <div class="row">
                                        <div class="col-md-6">Errors</div>
                                        <div class="col-md-6 text-right"><?php echo $row['errors_marks']; ?> marks</div>
                                    </div>
                                </div>
                                <div class="evaluation-score">
                                    <div class="row">
                                        <div class="col-md-6">Output</div>
                                        <div class="col-md-6 text-right"><?php echo $row['output_marks']; ?> marks</div>
                                    </div>
                                </div>
                                
                                <div class="total-score text-center">
                                    Total Score: <?php echo $row['total_marks']; ?> marks
                                </div>
                                
                                <?php if(!empty($row['remarks'])): ?>
                                <div class="remarks-box">
                                    <h5>Remarks:</h5>
                                    <p><?php echo nl2br($row['remarks']); ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mt-4">
                                    <p><strong>Evaluated by:</strong> <?php echo $row['evaluator_name']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif(!$is_technical): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="alert alert-info">
                                    <h4>Non-Technical Game Winner</h4>
                                    <p>This participant was selected as a winner for a non-technical game.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <a href="all_winner_list.php" class="btn btn-back">
                            <i class="fa fa-arrow-left mr-2"></i> Back to Winners List
                        </a>
                    </div>
                </div>
            </div>
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
