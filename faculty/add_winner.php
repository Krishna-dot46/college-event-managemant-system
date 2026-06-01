<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if faculty is logged in
if(!isset($_SESSION['faculty_id'])) {
    header("location: login.php");
    exit();
}

// Check if registration ID is provided
if(!isset($_GET['id']) && !isset($_GET['reg_id'])) {
    header("location: all_game_registration.php");
    exit();
}

// Get registration ID from either parameter
$registration_id = isset($_GET['id']) ? $_GET['id'] : $_GET['reg_id'];
$faculty_id = $_SESSION['faculty_id'];

// Handle form submission for evaluation
if(isset($_POST['submit_evaluation'])) {
    $lines_of_code_marks = $_POST['lines_of_code_marks'];
    $logic_marks = $_POST['logic_marks'];
    $errors_marks = $_POST['errors_marks'];
    $output_marks = $_POST['output_marks'];
    $total_marks = $lines_of_code_marks + $logic_marks + $errors_marks + $output_marks;
    $remarks = mysqli_real_escape_string($db, $_POST['remarks']);
    
    // Insert into evaluation_sheet
    $eval_sql = "INSERT INTO evaluation_sheet (registration_id, lines_of_code_marks, logic_marks, 
                errors_marks, output_marks, total_marks, remarks, evaluated_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $eval_stmt = mysqli_prepare($db, $eval_sql);
    mysqli_stmt_bind_param($eval_stmt, "iiiiiisi", $registration_id, $lines_of_code_marks, 
                          $logic_marks, $errors_marks, $output_marks, $total_marks, $remarks, $faculty_id);
    
    if(mysqli_stmt_execute($eval_stmt)) {
        // Update winner status in game_registration
        $update_sql = "UPDATE game_registration SET winner_status = 'winner' WHERE registration_id = ?";
        $update_stmt = mysqli_prepare($db, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $registration_id);
        mysqli_stmt_execute($update_stmt);
        
        // Redirect to winner list
        header("location: all_winner_list.php");
        exit();
    } else {
        $error = "Error adding evaluation: " . mysqli_error($db);
    }
}

// Get registration details with user and game info
$sql = "SELECT gr.*, g.title as game_title, g.price, g.description, g.img, g.c_name,
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
    // Try alternative query for participants data
    $sql2 = "SELECT gr.*, g.title as game_title, g.price, g.description, g.img, g.c_name
            FROM game_registration gr
            JOIN games g ON gr.game_id = g.game_id
            WHERE gr.registration_id = ?";
            
    $stmt2 = mysqli_prepare($db, $sql2);
    mysqli_stmt_bind_param($stmt2, "i", $registration_id);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    
    if(mysqli_num_rows($result) == 0) {
        header("location: all_game_registration.php");
        exit();
    }
}

$row = mysqli_fetch_assoc($result);
$is_technical = ($row['c_name'] == 'Technical');

// Handle direct winner selection for non-technical games
if(isset($_GET['mark_winner']) && !$is_technical) {
    $update_sql = "UPDATE game_registration SET winner_status = 'winner' WHERE registration_id = ?";
    $update_stmt = mysqli_prepare($db, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $registration_id);
    
    if(mysqli_stmt_execute($update_stmt)) {
        $_SESSION['success'] = "Participant marked as winner successfully!";
        header("location: all_winner_list.php");
        exit();
    } else {
        $error = "Error updating winner status. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Add Winner">
    <title>Add Winner</title>
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
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .total-marks {
            font-size: 24px;
            font-weight: bold;
            color: #3182ce;
            padding: 15px;
            background: #ebf8ff;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn-mark-winner {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-mark-winner:hover {
            background: linear-gradient(135deg, #2f855a 0%, #276749 100%);
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
                        <h3 class="text-themecolor">Add Winner</h3>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="participants.php">Participants</a></li>
                            <li class="breadcrumb-item active">Add Winner</li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-b-0" style="color: white;">Registration Details</h4>
                            </div>
                            <div class="card-body">
                                <?php if(isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <?php if(isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <?php 
                                        echo $_SESSION['success'];
                                        unset($_SESSION['success']);
                                    ?>
                                </div>
                                <?php endif; ?>
                                
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
                                            <div class="detail-label">Game Category</div>
                                            <div class="detail-value"><?php echo $row['c_name']; ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Student Name</div>
                                            <div class="detail-value">
                                                <?php 
                                                if(isset($row['f_name']) && isset($row['l_name'])) {
                                                    echo $row['f_name'] . ' ' . $row['l_name']; 
                                                    if(isset($row['username'])) {
                                                        echo '<small class="text-muted">(@' . $row['username'] . ')</small>';
                                                    }
                                                } else if(isset($row['student_name'])) {
                                                    echo $row['student_name'];
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">Email</div>
                                            <div class="detail-value">
                                                <?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Phone</div>
                                            <div class="detail-value">
                                                <?php 
                                                if(isset($row['phone'])) {
                                                    echo $row['phone'];
                                                } else if(isset($row['contact'])) {
                                                    echo $row['contact'];
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Registration Date</div>
                                            <div class="detail-value">
                                                <?php 
                                                if(isset($row['registration_date'])) {
                                                    echo date('d M Y H:i', strtotime($row['registration_date']));
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <?php if($is_technical): ?>
                                        <form method="post" action="">
                                            <h4 class="mb-4">Evaluation Sheet</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="lines_of_code_marks">Lines of Code Marks (0-10)</label>
                                                        <input type="number" class="form-control" id="lines_of_code_marks" name="lines_of_code_marks" min="0" max="10" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="logic_marks">Logic Marks (0-20)</label>
                                                        <input type="number" class="form-control" id="logic_marks" name="logic_marks" min="0" max="20" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="errors_marks">Errors Marks (0-10)</label>
                                                        <input type="number" class="form-control" id="errors_marks" name="errors_marks" min="0" max="10" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="output_marks">Output Marks (0-10)</label>
                                                        <input type="number" class="form-control" id="output_marks" name="output_marks" min="0" max="10" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="total-marks text-center">
                                                        Total Marks: <span id="total_marks">0</span>/50
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="submit_evaluation" class="btn btn-primary">
                                                    <i class="fa fa-trophy"></i> Submit Evaluation
                                                </button>
                                                <a href="participants.php" class="btn btn-secondary">
                                                    <i class="fa fa-arrow-left"></i> Back to Participants
                                                </a>
                                            </div>
                                        </form>
                                        <?php else: ?>
                                        <div class="alert alert-info">
                                            <strong>Note:</strong> This is a Non-Technical game. You can directly mark this participant as a winner.
                                        </div>
                                        <div class="form-group">
                                            <a href="add_winner.php?id=<?php echo $row['registration_id']; ?>&mark_winner=1" 
                                               class="btn btn-mark-winner"
                                               onclick="return confirm('Are you sure you want to mark this participant as a winner?');">
                                                <i class="fa fa-trophy"></i> Mark as Winner
                                            </a>
                                            <a href="participants.php" class="btn btn-secondary ml-2">
                                                <i class="fa fa-arrow-left"></i> Back to Participants
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script>
        // Calculate total marks automatically
        $(document).ready(function() {
            $('input[type=number]').on('input', function() {
                let total = 0;
                $('input[type=number]').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                $('#total_marks').text(total);
                
                // Change color based on marks
                if(total < 40) {
                    $('#total_marks').css('color', '#e53e3e'); // red
                } else if(total < 70) {
                    $('#total_marks').css('color', '#dd6b20'); // orange
                } else {
                    $('#total_marks').css('color', '#38a169'); // green
                }
            });
            
            // Form validation
            $('form').on('submit', function(e) {
                <?php if($is_technical): ?>
                let total = 0;
                $('input[type=number]').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                
                if(total > 100) {
                    e.preventDefault();
                    alert('Total marks cannot exceed 100!');
                    return false;
                }
                <?php endif; ?>
                
                return true;
            });
        });
    </script>
</body>
</html>
