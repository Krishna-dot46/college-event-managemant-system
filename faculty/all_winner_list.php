<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION["faculty_id"])) {
    header('location:index.php');
}
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
    <title>Winners List</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <?php include("inc/header.php"); ?>
        <?php include("inc/sidebar.php"); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Technical Games - Winners</h4>
                                <div class="table-responsive m-t-20">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Game Name</th>
                                                <th>Student Name</th>
                                                <th>College</th>
                                                <th>Department</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Total Marks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Get winners for each technical game (highest marks for each game)
                                            $sql = "SELECT gr.registration_id, g.title, gr.student_name, gr.college, gr.department, 
                                                    gr.contact, gr.email, es.total_marks 
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
                                            $query = mysqli_query($db, $sql);
                                            if(!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="9" class="text-center">No Winners Found</td></tr>';
                                            } else {
                                                $i = 1;
                                                while($rows = mysqli_fetch_array($query)) {
                                                    echo '<tr>
                                                        <td>'.$i.'</td>
                                                        <td>'.$rows['title'].'</td>
                                                        <td>'.$rows['student_name'].'</td>
                                                        <td>'.$rows['college'].'</td>
                                                        <td>'.$rows['department'].'</td>
                                                        <td>'.$rows['contact'].'</td>
                                                        <td>'.$rows['email'].'</td>
                                                        <td>'.$rows['total_marks'].'</td>
                                                        <td><a href="view_evaluation.php?reg_id='.$rows['registration_id'].'" class="btn btn-info btn-sm">View Details</a></td>
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
                    </div>
                    
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Non-Technical Games - Winners</h4>
                                <div class="table-responsive m-t-20">
                                    <table id="myTable2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Game Name</th>
                                                <th>Student Name</th>
                                                <th>College</th>
                                                <th>Department</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT gr.registration_id, g.title, gr.student_name, gr.college, gr.department, 
                                                    gr.contact, gr.email
                                                    FROM game_registration gr 
                                                    JOIN games g ON gr.game_id = g.game_id 
                                                    JOIN categories c ON g.c_name = c.c_name 
                                                    WHERE c.c_name = 'Non-Technical' AND gr.winner_status = 'winner'
                                                    ORDER BY g.title";
                                            $query = mysqli_query($db, $sql);
                                            if(!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="8" class="text-center">No Winners Found</td></tr>';
                                            } else {
                                                $i = 1;
                                                while($rows = mysqli_fetch_array($query)) {
                                                    echo '<tr>
                                                        <td>'.$i.'</td>
                                                        <td>'.$rows['title'].'</td>
                                                        <td>'.$rows['student_name'].'</td>
                                                        <td>'.$rows['college'].'</td>
                                                        <td>'.$rows['department'].'</td>
                                                        <td>'.$rows['contact'].'</td>
                                                        <td>'.$rows['email'].'</td>
                                                        <td><a href="view_evaluation.php?reg_id='.$rows['registration_id'].'" class="btn btn-info btn-sm">View Details</a></td>
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
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
            $('#myTable2').DataTable();
        });
    </script>
</body>
</html>
