<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="manifest" href="assets/site.webmanifest">
    <title>QR Code Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h3 class="mb-4 text-center">QR Code Generator</h3>
        <form method="post" action="">
            <fieldset class="border p-4 rounded">
                <div class="mb-3 text-center">
                    <label for="qr_code_data" class="form-label">Masukan text atau URL yang akan di Generate</label>
                    <center><input type="text" name="qr_code_data" id="qr_code_data" class="form-control text-center" minlength="3" required
                        value="<?php $val = isset($_POST['generate']) ? htmlspecialchars($_POST['qr_code_data']) : ''; echo $val; ?>" placeholder="Masukan text atau URL"></center>
                </div>
                <center><button type="submit" name="generate" id="btn_submit" class="btn btn-primary w-40">Generate QR Code</button></center>
            </fieldset>
        </form>
        <div class="border p-4 rounded" >
            <?php
            if (isset($_POST['generate'])) {
                include "phpqrcode/qrlib.php";

                // Define directories
                $cache = "phpqrcode/cache/";
                $temp = "img-qrcode/";

                // Create cache directory if it doesn't exist
                if (!file_exists($cache)) {
                    mkdir($cache, 0755, true);
                }

                // Create temp directory if it doesn't exist
                if (!file_exists($temp)) {
                    mkdir($temp, 0755, true);
                }

                $file_name = rand() . ".fdprinting.png";
                $file_path = $temp . $file_name;

                QRcode::png($_POST['qr_code_data'], $file_path, "H", 12, 1);
                /* param (1)qrcontent, (2)filename, (3)errorcorrectionlevel, (4)pixelwidth, (5)margin */

                // Resize QR Code to fixed dimensions
                $desired_width = 200; // Set desired width
                $desired_height = 200; // Set desired height

                // Load the original QR Code image
                $source_image = imagecreatefrompng($file_path);
                $original_width = imagesx($source_image);
                $original_height = imagesy($source_image);
            
                // Create a new true color image with desired dimensions
                $resized_image = imagecreatetruecolor($desired_width, $desired_height);
            
                // Preserve transparency
                imagealphablending($resized_image, false);
                imagesavealpha($resized_image, true);
                $transparent = imagecolorallocatealpha($resized_image, 255, 255, 255, 127);
                imagefill($resized_image, 0, 0, $transparent);
            
                // Resize the original image into the new image
                imagecopyresampled($resized_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $original_width, $original_height);
            
                // Save the resized image
                imagepng($resized_image, $file_path);
            
                // Free memory
                imagedestroy($source_image);
                imagedestroy($resized_image);
            

                echo "<div class='mt-4 text-center'>";
                echo "<p class='fs-5'>Hasil:</p>";
                echo "<img src='" . $file_path . "' class='img-fluid' alt='QR Code' />";
                echo "<div class='mt-3'>";
                echo "<a href='" . $file_path . "' download id='btn_submit' class='btn btn-success w-40'>Download QR Code</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <<footer class="text-center mt-5">
        <p>&copy; <span id="currentYear"></span> QR Code Generator By: FD Printing</p>
    </footer>

    <script>
        document.getElementById("currentYear").textContent = new Date().getFullYear();
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>
