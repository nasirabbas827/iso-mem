<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $positionID = $_POST["position_id"];
    $candidateName = $_POST["candidate_name"];
    $statement = mysqli_real_escape_string($conn, $_POST["statement"]);

    // Check if a file is uploaded
    if (isset($_FILES['candidate_picture']) && $_FILES['candidate_picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['candidate_picture'];
        
        // Check file type
        $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png');
        if (in_array($file['type'], $allowedTypes)) {
            $targetDir = 'uploads/';
            $targetFile = $targetDir . basename($file['name']);
            
            // Move the uploaded file to the target directory
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $candidatePicture = $targetFile;

                $insertSql = "INSERT INTO Candidates (PositionID, CandidateName, CandidatePicture, Statement)
                              VALUES ('$positionID', '$candidateName', '$candidatePicture', '$statement')";

                if ($conn->query($insertSql) === TRUE) {
                    $message = "Candidate added successfully!";
                } else {
                    $error = "Error adding candidate: " . $conn->error;
                }
            } else {
                $error = "Error uploading candidate picture.";
            }
        } else {
            $error = "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
        }
    } else {
        $error = "No candidate picture uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Candidate</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
 body{
            background-color: aquamarine;
        }
h1, h2, h3, h4{
    margin-top: 30px;
    margin-bottom: 30px;
    text-align: center;
}
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container">
        <h2>Add Candidate</h2>
        <?php if (isset($message)) { ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="position_id">Position:</label>
                <select class="form-control" id="position_id" name="position_id" required>
                    <?php
                    $positionQuery = "SELECT PositionID, PositionTitle FROM VotingPositions";
                    $positionResult = $conn->query($positionQuery);
                    while ($positionRow = $positionResult->fetch_assoc()) {
                        echo "<option value=\"" . $positionRow["PositionID"] . "\">" . $positionRow["PositionTitle"] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="candidate_name">Candidate Name:</label>
                <input type="text" class="form-control" id="candidate_name" name="candidate_name" required>
            </div>

            <div class="form-group">
                <label for="candidate_picture">Candidate Picture (JPG, JPEG, PNG only):</label>
                <input type="file" class="form-control-file" id="candidate_picture" name="candidate_picture" accept="image/jpeg, image/jpg, image/png" required>
            </div>

            <div class="form-group">
                <label for="statement">Statement:</label>
                <textarea class="form-control" id="statement" name="statement" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Candidate</button>
            <a href="view_candidate.php">View Candidates</a>
        </form>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
