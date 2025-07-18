<?php
include "db.php";
include "functions.php";
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 0); // Unlimited time

if (isset($_POST["Import"])) {
    $filename = $_FILES["file"]["tmp_name"];

    // Check if the file is a CSV file
    if (pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION) != "csv") {
        echo "Please upload a CSV file.";
        exit;
    }
    importCSV($filename, $conn);
	
}

// Function to import CSV data
function importCSV($filename, $conn) {
    // Use LOAD DATA INFILE approach (faster for large files)

    // Enable local infile (optional, depending on server config)
    mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);

    // Real path and proper slashes
    $filepath = str_replace("\\", "/", realpath($filename));
    //$sql = "INSERT INTO users (firstname, lastname, email) VALUES (?, ?, ?)";
    //$stmt = $conn->prepare($sql);
    $sql = "
        LOAD DATA LOCAL INFILE '$filepath'
        INTO TABLE users
        FIELDS TERMINATED BY ',' 
        ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
        IGNORE 1 ROWS
        (firstname, lastname, email);
    ";

    if (!$conn->query($sql)) {
        die("Error importing CSV using LOAD DATA: " . $conn->error);
    }

    $conn->close();

    echo "<script type='text/javascript'>
            alert('CSV File has been successfully Imported.');
            window.location = 'index.php';
          </script>";
		  
		  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSV Import/Export</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">CSV Import & Export</h2>

    <!-- Import CSV Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Choose CSV File</label>
                    <input type="file" class="form-control" id="formFile" name="file" accept=".csv" required>
                </div>
                <button type="submit" name="Import" class="btn btn-primary">Import CSV</button>
            </form>
        </div>
    </div>

    <!-- Export to Excel Form -->
    <div class="card shadow-sm">
	
	<?php get_all_records(); ?>
        <div class="card-body">
            <form class="text-center" action="functions.php" method="post" enctype="multipart/form-data">
                <button type="submit" name="Export" class="btn btn-success">Export to Excel</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional, for interactive components like modals/dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

