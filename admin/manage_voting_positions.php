<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventID = $_POST["event_id"];
    $positionTitle = $_POST["position_title"];
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    
    $insertSql = "INSERT INTO VotingPositions (EventID, PositionTitle, Description)
                  VALUES ('$eventID', '$positionTitle', '$description')";
    
    if ($conn->query($insertSql) === TRUE) {
        $message = "Voting position created successfully!";
    } else {
        $error = "Error creating voting position: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Voting Position</title>
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
        <h2>Create Voting Position</h2>
        <?php if (isset($message)) { ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="event_id">Event:</label>
                <select class="form-control" id="event_id" name="event_id" required>
                    <?php
                    $eventQuery = "SELECT EventID, Title FROM Events";
                    $eventResult = $conn->query($eventQuery);
                    while ($eventRow = $eventResult->fetch_assoc()) {
                        echo "<option value=\"" . $eventRow["EventID"] . "\">" . $eventRow["Title"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="position_title">Position Title:</label>
                <input type="text" class="form-control" id="position_title" name="position_title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary mr-2 ">Create Voting Position</button>
            <a href="view_voting_positions.php">View Voting Positions</a>
        </form>
    </div>

    <!-- Add Bootstrap JS scripts at the end of the body -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
