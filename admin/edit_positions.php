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
    $positionTitle = $_POST["position_title"];
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    
    $updateSql = "UPDATE VotingPositions SET PositionTitle = '$positionTitle', Description = '$description'
                  WHERE PositionID = $positionID";
    
    if ($conn->query($updateSql) === TRUE) {
        $updateMessage = "Voting position updated successfully!";
    } else {
        $updateError = "Error updating voting position: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $positionID = $_GET['id'];
    $selectSql = "SELECT * FROM VotingPositions WHERE PositionID = $positionID";
    $result = $conn->query($selectSql);
    $position = $result->fetch_assoc();
} else {
    header("Location: view_voting_positions.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Voting Position</title>
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
        <h2>Edit Voting Position</h2>
        <?php if (isset($updateMessage)) { ?>
            <p class="success-message"><?php echo $updateMessage; ?></p>
        <?php } ?>
        <?php if (isset($updateError)) { ?>
            <p class="error-message"><?php echo $updateError; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="position_id" value="<?php echo $position['PositionID']; ?>">
            
            <div class="form-group">
                <label for="position_title">Position Title:</label>
                <input type="text" class="form-control" id="position_title" name="position_title" value="<?php echo $position['PositionTitle']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $position['Description']; ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Voting Position</button>
        </form>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
