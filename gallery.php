<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Gallery | Technical Fest 2025</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000000;
            background-image: url('images/circle-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            color: #333;
        }

        .navbar {
            background: rgba(33, 33, 33, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
        }

        .container {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gallery-container, .video-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .video-container {
            margin-bottom: 50px;
        }

        .gallery-item, .video-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-item:hover, .video-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img, .video-item video {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img, .video-item:hover video {
            transform: scale(1.1);
        }

        .section-title {
            color: white;
            font-weight: 600;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
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
            background: linear-gradient(90deg, transparent, #fff, transparent);
        }

        /* Lightbox styles */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .lightbox img, .lightbox video {
            max-width: 90%;
            max-height: 90vh;
            border-radius: 5px;
        }

        .close-lightbox {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "inc/navbar.php"; ?>

    <div class="container" style="margin-top: 100px;">
        <h1 class="section-title">Event Videos</h1>
        <div class="video-container">
            <?php
            $video_dir = "images/gallery/video/";
            $videos = glob($video_dir . "*.{mp4,webm,ogg}", GLOB_BRACE);
            
            foreach($videos as $video) {
                echo '<div class="video-item" onclick="openLightbox(\'' . $video . '\', true)">';
                echo '<video src="' . $video . '" muted loop></video>';
                echo '</div>';
            }
            ?>
        </div>

        <h1 class="section-title">Event Gallery</h1>
        <div class="gallery-container">
            <?php
            $gallery_dir = "images/gallery/";
            $images = glob($gallery_dir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
            
            foreach($images as $image) {
                $filename = basename($image);
                echo '<div class="gallery-item" onclick="openLightbox(\'' . $image . '\', false)">';
                echo '<img src="' . $image . '" alt="Gallery Image" loading="lazy">';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <span class="close-lightbox">&times;</span>
        <img id="lightbox-img" src="" alt="Lightbox Image" style="display: none;">
        <video id="lightbox-video" controls style="display: none;"></video>
    </div>

    <?php include "inc/footer.php"; ?>

    <script src="inc/script.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        function openLightbox(src, isVideo) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const lightboxVideo = document.getElementById('lightbox-video');
            
            lightbox.style.display = 'flex';
            
            if (isVideo) {
                lightboxVideo.src = src;
                lightboxVideo.style.display = 'block';
                lightboxImg.style.display = 'none';
            } else {
                lightboxImg.src = src;
                lightboxImg.style.display = 'block';
                lightboxVideo.style.display = 'none';
            }
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            const lightboxVideo = document.getElementById('lightbox-video');
            
            lightbox.style.display = 'none';
            lightboxVideo.pause();
        }

        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
</body>
</html>
