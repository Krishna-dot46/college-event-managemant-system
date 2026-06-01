<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if(isset($_POST['submit'])) {
    if(empty($_POST['c_name'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Category name is required!</strong>
        </div>';
    } else {
        $c_name = mysqli_real_escape_string($db, $_POST['c_name']);
        $cat_id = mysqli_real_escape_string($db, $_GET['cat_upd']);
        
        $mql = "UPDATE categories SET c_name='$c_name' WHERE c_id='$cat_id'";
        if(mysqli_query($db, $mql)) {
            $success = '<div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Category updated successfully!</strong>
            </div>';
            header('location:all_category.php');
        } else {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Error updating category!</strong>
            </div>';
        }
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Update Category</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 2px solid #e1e5eb;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            background: #ffffff;
        }
        
        .btn {
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
        }
        
        .btn-inverse {
            background: #fff;
            border: 2px solid #e1e5eb;
            color: #4a5568;
        }
        
        .btn-inverse:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }
        
        .card {
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-radius: 16px;
            margin-bottom: 30px;
            background: #ffffff;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            padding: 24px 32px;
            border: none;
            position: relative;
        }
        
        .card-header:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSIxMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMGgxNDQwdjEyMEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDBoMTQ0MHYxMjBIMHoiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iLjA1Ii8+PC9zdmc+') center/cover;
            opacity: 0.1;
        }
        
        .card-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-body {
            padding: 32px;
        }
        
        .form-group label {
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .footer {
            background: #ffffff;
            padding: 20px;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }
        
        .preloader {
            background: rgba(255,255,255,0.98);
        }
        
        .preloader .circular .path {
            stroke: #4299e1;
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
                <?php  
                echo $error;
                echo $success; 
                ?>
                                    
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Category</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php 
                                                $cat_id = mysqli_real_escape_string($db, $_GET['cat_upd']);
                                                $sql = "SELECT * FROM categories WHERE c_id='$cat_id'";
                                                $result = mysqli_query($db, $sql);
                                                $row = mysqli_fetch_array($result);
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label">Category Name</label>
                                                <input type="text" name="c_name" value="<?php echo htmlspecialchars($row['c_name']); ?>" class="form-control" placeholder="Enter category name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions" style="margin-top: 24px;">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="fa fa-check"></i> Update Category
                                    </button>
                                    <a href="all_category.php" class="btn btn-inverse" style="margin-left: 12px;">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: center; width: 100%;">
                <?php include("inc/footer.php"); ?>
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
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>