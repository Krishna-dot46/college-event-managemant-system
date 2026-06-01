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
    <meta name="description" content="View winners of competitions and events">
    <meta name="author" content="VVWU">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Winners List | VVWU</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000000;
            background-image: url('images/circle-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .section-title {
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
            text-align: center;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .card {
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .card-title {
            color: #764ba2;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #764ba2;
            padding-bottom: 10px;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            border: none;
        }
        .badge-winner {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 50px;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #333;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-info:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body>
    <?php include "inc/navbar.php"; ?>

    <div class="container" style="margin-top: 100px; margin-bottom: 50px;">
        <h1 class="section-title">Winners List</h1>
        
        <div class="row">
            <div class="col-md-12">
                <!-- Technical Games Winners -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Technical Games - Winners</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Game Name</th>
                                        <th>Student Name</th>
                                        <th>College</th>
                                        <th>Department</th>
                                        <th>Total Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get winners for each technical game (highest marks for each game)
                                    $sql = "SELECT gr.registration_id, g.title, gr.student_name, gr.college, gr.department, 
                                            es.total_marks 
                                            FROM game_registration gr 
                                            JOIN games g ON gr.game_id = g.game_id 
                                            JOIN categories c ON g.c_name = c.c_name 
                                            JOIN evaluation_sheet es ON gr.registration_id = es.registration_id
                                            JOIN (
                                                SELECT g.game_id, MAX(es.total_marks) as max_marks
                                                FROM evaluation_sheet es
                                                JOIN game_registration gr ON es.registration_id = gr.registration_id
                                                JOIN games g ON gr.game_id = g.game_id
                                                JOIN categories c ON g.c_name = c.c_name
                                                WHERE c.c_name = 'Technical'
                                                GROUP BY g.game_id
                                            ) max_scores ON g.game_id = max_scores.game_id AND es.total_marks = max_scores.max_marks
                                            WHERE c.c_name = 'Technical'
                                            ORDER BY g.title, es.total_marks DESC";
                                    
                                    $result = mysqli_query($db, $sql);
                                    
                                    if(!$result || mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="6" class="text-center">No winners announced yet for Technical games.</td></tr>';
                                    } else {
                                        $i = 1;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>
                                                <td>'.$i.'</td>
                                                <td>'.$row['title'].'</td>
                                                <td>'.$row['student_name'].'</td>
                                                <td>'.$row['college'].'</td>
                                                <td>'.$row['department'].'</td>
                                                <td>'.$row['total_marks'].'</td>
                                            </tr>';
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Non-Technical Games Winners -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Non-Technical Games - Winners</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Game Name</th>
                                        <th>Student Name</th>
                                        <th>College</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT gr.registration_id, g.title, gr.student_name, gr.college, gr.department 
                                           FROM game_registration gr 
                                           JOIN games g ON gr.game_id = g.game_id 
                                           JOIN categories c ON g.c_name = c.c_name 
                                           WHERE c.c_name = 'Non-Technical' AND gr.winner_status = 'winner'
                                           ORDER BY g.title";
                                    
                                    $result = mysqli_query($db, $sql);
                                    
                                    if(!$result || mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="5" class="text-center">No winners announced yet for Non-Technical games.</td></tr>';
                                    } else {
                                        $i = 1;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>
                                                <td>'.$i.'</td>
                                                <td>'.$row['title'].'</td>
                                                <td>'.$row['student_name'].'</td>
                                                <td>'.$row['college'].'</td>
                                                <td>'.$row['department'].'</td>
                                            </tr>';
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="result.php" class="btn btn-primary">
                        <i class="fa fa-arrow-left mr-2"></i> Back to Results
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
