<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if(empty($_SESSION["adm_id"])) {
    header('location:index.php');
    exit();
} else {

if(isset($_POST['submit'])) {
    $error = '';
    $success = '';

    // File upload handling
    $fname = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $fsize = $_FILES['file']['size'];
    $extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
    $fnew = uniqid().'.'.$extension;
    $store = "games_img/games/".basename($fnew);

    $allowed_extensions = ['jpg', 'png', 'gif'];

    if($fname && !in_array($extension, $allowed_extensions)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Invalid extension!</strong> Only png, jpg, gif are accepted.
                </div>';
    } else if($fsize >= 1000000) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Max Image Size is 1024kb!</strong> Try different Image.
                </div>';
    } else {
        try {
            // Get category name safely
            $category_name = '';
            if(!empty($_POST['category'])) {
                $category_id = intval($_POST['category']);
                $cat_query = mysqli_query($db, "SELECT c_name FROM categories WHERE c_id = $category_id");

                if(!$cat_query) {
                    throw new Exception("Error getting category: " . mysqli_error($db));
                }

                $cat_row = mysqli_fetch_array($cat_query);
                $category_name = $cat_row['c_name'];
            }

            // Prepare update query based on whether new image was uploaded
            if($fname) {
                $stmt = mysqli_prepare($db, "UPDATE games SET title=?, description=?, price=?, img=?, c_name=?, registration_date=?, registration_rules=? WHERE game_id=?");
                
                if(!$stmt) {
                    throw new Exception("Prepare failed: " . mysqli_error($db));
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssssi",
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['price'],
                    $fnew,
                    $category_name,
                    $_POST['registration_date'],
                    $_POST['registration_rules'],
                    $_GET['game_id']
                );
            } else {
                $stmt = mysqli_prepare($db, "UPDATE games SET title=?, description=?, price=?, c_name=?, registration_date=?, registration_rules=? WHERE game_id=?");
                
                if(!$stmt) {
                    throw new Exception("Prepare failed: " . mysqli_error($db));
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssi", 
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['price'],
                    $category_name,
                    $_POST['registration_date'],
                    $_POST['registration_rules'],
                    $_GET['game_id']
                );
            }

            if(!mysqli_stmt_execute($stmt)) {
                throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
            }

            // Upload file if provided
            if($fname && !move_uploaded_file($temp, $store)) {
                throw new Exception("Failed to move uploaded file");
            }

            $success = '<div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Game Updated Successfully.
                </div>';

            header('location:all_game.php');
            mysqli_stmt_close($stmt);

        } catch(Exception $e) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Error: ' . htmlspecialchars($e->getMessage()) . '
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
    <title>Update Game</title>
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
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
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
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .footer {
            background: #ffffff;
            padding: 20px;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }

        .preloader {
            background: rgba(255, 255, 255, 0.98);
        }

        .preloader .circular .path {
            stroke: #4299e1;
        }

        .day-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .day-tags input[type="checkbox"] {
            display: none;
        }

        .day-tag {
            padding: 8px 16px;
            background: #f1f5f9;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
        }

        .day-tags input[type="checkbox"]:checked + .day-tag {
            background: #4299e1;
            color: white;
            box-shadow: 0 2px 4px rgba(66, 153, 225, 0.2);
        }

        select.form-control {
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .drop-zone {
            max-width: 100%;
            height: 200px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            color: #718096;
            border: 2px dashed #cbd5e0;
            border-radius: 10px;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .drop-zone:hover {
            background: #edf2f7;
            border-color: #a0aec0;
        }

        .drop-zone.dragover {
            background: #ebf8ff;
            border-color: #4299e1;
        }

        .drop-zone__input {
            display: none;
        }

        .drop-zone__thumb {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            background-color: #cccccc;
            background-size: cover;
            position: relative;
        }

        .drop-zone__thumb::after {
            content: attr(data-label);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 5px 0;
            color: #ffffff;
            background: rgba(0, 0, 0, 0.75);
            font-size: 14px;
            text-align: center;
        }

        /* Category UI Styles */
        .category-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .category-tag {
            padding: 8px 16px;
            background: #f1f5f9;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
            border: 2px solid transparent;
        }

        .category-tag:hover {
            background: #e2e8f0;
        }

        .category-tag.selected {
            background: #4299e1;
            color: white;
            border-color: #3182ce;
        }

        input[name="category"] {
            display: none;
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
                            <h4>Update Game</h4>
                        </div>
                        <div class="card-body">
                            <?php
                            $game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;
                            
                            $sql = "SELECT * FROM games WHERE game_id=?";
                            $stmt = mysqli_prepare($db, $sql);
                            mysqli_stmt_bind_param($stmt, "i", $game_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            
                            if($row = mysqli_fetch_array($result)) {
                            ?>
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Game Title</label>
                                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" placeholder="Enter game title">
                                            </div>
                                        </div>
                                  
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($row['description']); ?>" placeholder="Enter game description">
                                            </div>
                                        </div>
                                    </div>
                              
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input type="number" name="price" class="form-control" readonly value="<?php echo htmlspecialchars($row['price']); ?>" placeholder="Enter price" min="0" step="0.01">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Category</label>
                                                <input type="hidden" name="category" id="selectedCategory" value="">
                                                <div class="category-tags">
                                                    <?php
                                                    $sql = "SELECT * FROM categories ORDER BY c_name ASC";
                                                    $query = mysqli_query($db, $sql);
                                                    while($cat_row = mysqli_fetch_array($query)) {
                                                        $selected = ($cat_row['c_name'] == $row['c_name']) ? 'selected' : '';
                                                        echo '<div class="category-tag '.$selected.'" data-value="'.$cat_row['c_id'].'">'.$cat_row['c_name'].'</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Registration Date</label>
                                                <input type="date" name="registration_date" class="form-control" value="<?php echo htmlspecialchars($row['registration_date']); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Registration Rules</label>
                                                <textarea name="registration_rules" class="form-control" rows="4"><?php echo htmlspecialchars($row['registration_rules']); ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Image <small class="text-muted">(Preferable image height is 250px)</small></label>
                                                <div class="drop-zone">
                                                    <?php if(!empty($row['img'])): ?>
                                                        <div class="drop-zone__thumb" data-label="<?php echo htmlspecialchars($row['img']); ?>" style="background-image: url('games_img/games/<?php echo htmlspecialchars($row['img']); ?>')"></div>
                                                    <?php else: ?>
                                                        <span class="drop-zone__prompt">Drop file here or click to upload</span>
                                                    <?php endif; ?>
                                                    <input type="file" name="file" class="drop-zone__input" accept=".jpg,.png,.gif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions" style="margin-top: 24px; display: flex; justify-content: center;">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="fa fa-check"></i> Update Game
                                    </button>
                                    <a href="all_game.php" class="btn btn-inverse" style="margin-left: 12px; color: #4a5568;">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                            <?php
                            } else {
                                echo '<div class="alert alert-danger">Game not found!</div>';
                            }
                            mysqli_stmt_close($stmt);
                            ?>
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

    <script>
        document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
            const dropZoneElement = inputElement.closest(".drop-zone");

            dropZoneElement.addEventListener("click", (e) => {
                inputElement.click();
            });

            inputElement.addEventListener("change", (e) => {
                if (inputElement.files.length) {
                    updateThumbnail(dropZoneElement, inputElement.files[0]);
                }
            });

            dropZoneElement.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropZoneElement.classList.add("dragover");
            });

            ["dragleave", "dragend"].forEach((type) => {
                dropZoneElement.addEventListener(type, (e) => {
                    dropZoneElement.classList.remove("dragover");
                });
            });

            dropZoneElement.addEventListener("drop", (e) => {
                e.preventDefault();

                if (e.dataTransfer.files.length) {
                    inputElement.files = e.dataTransfer.files;
                    updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
                }

                dropZoneElement.classList.remove("dragover");
            });
        });

        function updateThumbnail(dropZoneElement, file) {
            let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

            if (dropZoneElement.querySelector(".drop-zone__prompt")) {
                dropZoneElement.querySelector(".drop-zone__prompt").remove();
            }

            if (!thumbnailElement) {
                thumbnailElement = document.createElement("div");
                thumbnailElement.classList.add("drop-zone__thumb");
                dropZoneElement.appendChild(thumbnailElement);
            }

            thumbnailElement.dataset.label = file.name;

            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
                };
            } else {
                thumbnailElement.style.backgroundImage = null;
            }
        }

        // Category tag selection
        document.querySelectorAll('.category-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                // Remove selected class from all tags
                document.querySelectorAll('.category-tag').forEach(t => {
                    t.classList.remove('selected');
                });
                
                // Add selected class to clicked tag
                this.classList.add('selected');
                
                // Update hidden input value
                document.getElementById('selectedCategory').value = this.dataset.value;
            });
        });

        // Set initial selected category
        const initialSelected = document.querySelector('.category-tag.selected');
        if (initialSelected) {
            document.getElementById('selectedCategory').value = initialSelected.dataset.value;
        }
    </script>

</body>

</html>
<?php
}
?>