<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (!isset($_SESSION["user_id"])) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View your competition results and achievements">
    <meta name="author" content="VVWU">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>My Results | VVWU</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000;
            background-image: url('images/circle-bg.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
            margin-bottom: 30px;
            margin-left: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            padding: 20px;
            border-bottom: none;
        }
        .card-title {
            font-weight: 600;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            margin-left: 20px;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #f2f4f8;
            color: #333;
            font-weight: 600;
            border-top: none;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        .btn-info {
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .btn-info:hover {
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s;
            margin-bottom: 20px;
            margin-left: 20px;
        }
        .btn-primary:hover {
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.3);
            transform: translateY(-2px);
        }
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            margin-left: 20px;
            font-weight: 600;
            color: #333;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            border-radius: 3px;
        }
        .badge-winner {
            background: linear-gradient(to right, #f6d365 0%, #fda085 100%);
            color: #fff;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .badge-participant {
            background: linear-gradient(to right, #84fab0 0%, #8fd3f4 100%);
            color: #fff;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .badge-pending {
            background: linear-gradient(to right, #e0c3fc 0%, #8ec5fc 100%);
            color: #fff;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .page-header {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
            border-radius: 0 0 50% 50% / 20px;
        }
    </style>
</head>

<body>
    <?php include("inc/navbar.php"); ?>
    
    <div class="page-header text-center">
        <div class="container">
            <h1><i class="fa fa-trophy"></i> My Competition Results</h1>
            <p>View your performance and achievements in various competitions</p>
        </div>
    </div>
    
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0 text-white"><i class="fa fa-laptop mr-2"></i>Technical Games Results</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="section-title" style="margin-top: 20px;">Performance Summary</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-gamepad mr-2"></i>Game Name</th>
                                        <th><i class="fa fa-star mr-2"></i>Total Marks</th>
                                        <th><i class="fa fa-certificate mr-2"></i>Status</th>
                                        <th><i class="fa fa-eye mr-2"></i>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $user_id = $_SESSION['user_id'];
                                    $sql = "SELECT gr.registration_id, g.title, es.total_marks, 
                                            CASE 
                                                WHEN es.total_marks = (
                                                    SELECT MAX(es2.total_marks) 
                                                    FROM evaluation_sheet es2 
                                                    JOIN game_registration gr2 ON es2.registration_id = gr2.registration_id 
                                                    WHERE gr2.game_id = gr.game_id
                                                ) THEN 'Winner' 
                                                ELSE 'Participant' 
                                            END as status
                                            FROM game_registration gr 
                                            JOIN games g ON gr.game_id = g.game_id 
                                            JOIN categories c ON g.c_name = c.c_name 
                                            LEFT JOIN evaluation_sheet es ON gr.registration_id = es.registration_id
                                            WHERE c.c_name = 'Technical' AND gr.user_id = ?
                                            ORDER BY g.title";
                                    
                                    $stmt = mysqli_prepare($db, $sql);
                                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    
                                    if(mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="4" class="text-center">No results found</td></tr>';
                                    } else {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            $status_badge = '';
                                            if($row['total_marks']) {
                                                if($row['status'] == 'Winner') {
                                                    $status_badge = '<span class="badge-winner"><i class="fa fa-trophy mr-1"></i> '.$row['status'].'</span>';
                                                } else {
                                                    $status_badge = '<span class="badge-participant"><i class="fa fa-user mr-1"></i> '.$row['status'].'</span>';
                                                }
                                            } else {
                                                $status_badge = '<span class="badge-pending"><i class="fa fa-clock-o mr-1"></i> Pending</span>';
                                            }
                                            
                                            echo '<tr>
                                                <td><strong>'.$row['title'].'</strong></td>
                                                <td>'.($row['total_marks'] ? '<span class="text-primary font-weight-bold">'.$row['total_marks'].'</span>' : '<span class="text-muted">Not evaluated yet</span>').'</td>
                                                <td>'.$status_badge.'</td>
                                                <td>'.($row['total_marks'] ? '<a href="view_result.php?reg_id='.$row['registration_id'].'" class="btn btn-info btn-sm"><i class="fa fa-eye mr-1"></i> View Details</a>' : '<span class="text-muted"><i class="fa fa-hourglass-half mr-1"></i> Pending Evaluation</span>').'</td>
                                            </tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <h5 class="section-title mt-5">Non-Technical Games Results</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-gamepad mr-2"></i>Game Name</th>
                                        <th><i class="fa fa-certificate mr-2"></i>Status</th>
                                        <th><i class="fa fa-eye mr-2"></i>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT gr.registration_id, g.title, 
                                            CASE WHEN gr.winner_status = 'winner' THEN 'Winner' ELSE 'Participant' END as status
                                            FROM game_registration gr 
                                            JOIN games g ON gr.game_id = g.game_id 
                                            JOIN categories c ON g.c_name = c.c_name 
                                            WHERE c.c_name = 'Non-Technical' AND gr.user_id = ?
                                            ORDER BY g.title";
                                    
                                    $stmt = mysqli_prepare($db, $sql);
                                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    
                                    if(mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="3" class="text-center">No results found</td></tr>';
                                    } else {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            $status_badge = '';
                                            if($row['status'] == 'Winner') {
                                                $status_badge = '<span class="badge-winner"><i class="fa fa-trophy mr-1"></i> '.$row['status'].'</span>';
                                            } else {
                                                $status_badge = '<span class="badge-participant"><i class="fa fa-user mr-1"></i> '.$row['status'].'</span>';
                                            }
                                            
                                            echo '<tr>
                                                <td><strong>'.$row['title'].'</strong></td>
                                                <td>'.$status_badge.'</td>
                                                <td><a href="view_result.php?reg_id='.$row['registration_id'].'" class="btn btn-info btn-sm"><i class="fa fa-eye mr-1"></i> View Details</a></td>
                                            </tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="games.php" class="btn btn-primary">
                        <i class="fa fa-arrow-left mr-2"></i> Back to Games
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include("inc/footer.php"); ?>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="inc/script.js"></script>
    <script>
        $(document).ready(function() {
            // Add smooth scrolling
            $('a[href*="#"]').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate(
                    {
                        scrollTop: $($(this).attr('href')).offset().top,
                    },
                    500,
                    'linear'
                );
            });
            
            // Add table row hover effect
            $('.table-hover tbody tr').hover(
                function() {
                    $(this).css('background-color', 'rgba(118, 75, 162, 0.05)');
                },
                function() {
                    $(this).css('background-color', '');
                }
            );
        });
    </script>
</body>
</html>
